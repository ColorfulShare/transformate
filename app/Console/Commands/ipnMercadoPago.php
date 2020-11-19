<?php
namespace App\Console\Commands;
 
use Illuminate\Console\Command;
use App\Models\EventSubscription; use App\Models\Commission; use App\Models\User;
use App\Http\Controllers\NotificationController;
use DB; use Carbon\Carbon; use MercadoPago; use Mail;
 
class ipnMercadoPago extends Command{

  	protected $signature = 'ipnMercadoPago:notifications';
 
  	protected $description = 'Verifica las transferencias bancarias, pagos en efectivo y pagos con tarjeta pendientes y actualiza el estado si es necesario.';

  	public function __construct(){
    	parent::__construct();
  	}
 
  	public function handle(){
      \MercadoPago\SDK::setAccessToken("APP_USR-1902410051285318-043020-57263f6c7aa781de7dba01fd37458c14-515837620");
      $app = new \App\Http\Controllers\BankController();
      $carrito = new \App\Http\Controllers\ShoppingCartController();

  		$transferencias = DB::table('bank_transfers')
                     	   ->where('status', '=', 0)
                           ->where('payment_id', '<>', NULL)
   	               	   ->orderBy('ID', 'ASC')
   	               		->get();

      foreach ($transferencias as $transferencia){
         $payment = MercadoPago\Payment::find_by_id($transferencia->payment_id);

         if (!is_null($payment)){
            if ($payment->status == 'approved'){
               \Log::info("Pago por Transferencia #".$transferencia->id.". Aprobado.");
               $app->update_payment('Transferencia', $transferencia->id, 1);
            }else if ($payment->status == 'rejected'){
               \Log::info("Pago por Transferencia #".$transferencia->id.". Rechazado.");
               $app->update_payment('Transferencia', $transferencia->id, 2);
            }
         }
      }

      $efectivos = DB::table('efecty_payments')
                     ->where('status', '=', 0)
                     ->orderBy('ID', 'ASC')
                     ->get();

      foreach ($efectivos as $efectivo){
         $payment = MercadoPago\Payment::find_by_id($efectivo->payment_id);

         if (!is_null($payment)){
            if ($payment->status == 'approved'){
               \Log::info("Pago por Efecty #".$efectivo->id.". Aprobado.");
               $app->update_payment('Efectivo', $efectivo->id, 1);
            }else if ($payment->status == 'rejected'){
               \Log::info("Pago por Efecty #".$efectivo->id.". Rechazado.");
               $app->update_payment('Efectivo', $efectivo->id, 2);
            }
         }
      }

      $pagosTarjeta = DB::table('purchases')
                           ->where('status', '=', 0)
                           ->orderBy('ID', 'ASC')
                           ->get();

      foreach ($pagosTarjeta as $pago){
         $payment = MercadoPago\Payment::find_by_id($pago->payment_id);

         if (!is_null($payment)){
            if ($payment->status == 'approved'){
               \Log::info("Pago Pendiente TDC #".$pago->id.". Aprobado.");
               $carrito->update_pending_payment($pago->id, 1);
            }else if ($payment->status == 'rejected'){
               \Log::info("Pago Pendiente TDC #".$pago->id.". Rechazado.");
               $carrito->update_pending_payment($pago->id, 2);
            }
         }
      }

      $pagosEventos = DB::table('event_subscriptions')
                           ->where('status', '=', 0)
                           ->where('payment_method', '<>', NULL)
                           ->where('payment_method', '<>', 'Paypal')
                           ->orderBy('ID', 'ASC')
                           ->get();

      foreach ($pagosEventos as $pagoEvento){
         $payment = MercadoPago\Payment::find_by_id($pagoEvento->payment_id);

         if (!is_null($payment)){
            if ($payment->status == 'approved'){
               \Log::info("Pago Evento #".$pagoEvento->id.". Aprobado.");
               $this->approve_event_payment($pagoEvento->id, 1);
            }else if ($payment->status == 'rejected'){
               \Log::info("Pago Evento #".$pagoEvento->id.". Rechazado.");
               $this->approve_event_payment($pagoEvento->id, 2);
            }
         }
      }
   }

   public function approve_event_payment($suscriptor_id, $status){
      $notificacion = new NotificationController();

      $suscripcion =  EventSubscription::find($suscriptor_id);
      $suscripcion->status = $status;
      $suscripcion->save();

      if ($status == 1){
         $montoFinal = (($suscripcion->payment_amount * 94.5) / 100);
         
         if ( (!is_null($suscripcion->instructor_code)) && (!is_null($suscripcion->partner_code)) ){
            //**** Comisión al instructor ***//
            $comision = new Commission();
            $comision->user_id = $suscripcion->instructor_code;
            $comision->amount = (($montoFinal * 50) / 100);
            $comision->type = 'Código T-Mentor Aplicado en T-Event';
            $comision->wallet = 'T-Events';
            $comision->event_subscription_id = $suscripcion->id;
            $comision->status = 0;
            $comision->date = date('Y-m-d');
            $comision->save();

            $usuario = User::find($suscripcion->instructor_code);
            $usuario->event_balance = $usuario->event_balance + $comision->amount;
            $usuario->save();

            $notificacion->store($suscripcion->instructor_code, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision->type.'</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision->id);

            //**** Comisión al partner ***//
            $comision2 = new Commission();
            $comision2->user_id = $suscripcion->partner_code;
            $comision2->amount = (($montoFinal * 10) / 100);
            $comision2->type = 'Código T-Partner  Aplicado en T-Event';
            $comision2->wallet = 'T-Events';
            $comision2->event_subscription_id = $suscripcion->id;
            $comision2->status = 0;
            $comision2->date = date('Y-m-d');
            $comision2->save();

            $usuario2 = User::find($suscripcion->partner_code);
            $usuario2->event_balance = $usuario2->event_balance + $comision2->amount;
            $usuario2->save();

            $notificacion->store($suscripcion->partner_code, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision2->type.'</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision2->id);

            if (!is_null($usuario2->sponsor_id)){
               if ($usuario2->sponsor->role_id == 2){
                  $comision3 = new Commission();
                  $comision3->user_id = $usuario2->sponsor_id;
                  $comision3->amount = (($comision2->amount * 10) / 100);
                  $comision3->type = 'Plan de Afiliado (T-Event)';
                  $comision3->wallet = 'T-Events';
                  $comision3->referred_id = $usuario2->id;
                  $comision3->event_subscription_id = $suscripcion->id;
                  $comision3->status = 0;
                  $comision3->date = date('Y-m-d');
                  $comision3->save();

                  $usuario3 = User::find($usuario2->sponsor_id);
                  $usuario3->event_balance = $usuario3->event_balance + $comision3->amount;
                  $usuario3->save();

                  //**** Notificar al Patrocinador ***//s
                  $notificacion->store($usuario2->sponsor_id, 'Nueva Comisión', 'Tiene una nueva comisión por <b>Plan de Afiliados</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision3->id);
               }
            }
         }else if (!is_null($suscripcion->instructor_code)){
            $comision = new Commission();
            $comision->user_id = $suscripcion->instructor_code;
            $comision->amount = (($montoFinal * 60) / 100);
            $comision->type = 'Código T-Mentor Aplicado en T-Event';
            $comision->wallet = 'T-Events';
            $comision->event_subscription_id = $suscripcion->id;
            $comision->status = 0;
            $comision->date = date('Y-m-d');
            $comision->save();

            $usuario = User::find($suscripcion->instructor_code);
            $usuario->event_balance = $usuario->event_balance + $comision->amount;
            $usuario->save();

            $notificacion->store($suscripcion->instructor_code, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision->type.'</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision->id);
         }else if (!is_null($suscripcion->partner_code)){
            $comision = new Commission();
            $comision->user_id = $suscripcion->partner_code;
            $comision->amount = (($montoFinal * 10) / 100);
            $comision->type = 'Código T-Partner Aplicado en T-Event';
            $comision->wallet = 'T-Events';
            $comision->event_subscription_id = $suscripcion->id;
            $comision->status = 0;
            $comision->date = date('Y-m-d');
            $comision->save();

            $usuario = User::find($suscripcion->partner_code);
            $usuario->event_balance = $usuario->event_balance + $comision->amount;
            $usuario->save();
            
            $notificacion->store($suscripcion->partner_code, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision->type.'</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision->id);

            if (!is_null($usuario->sponsor_id)){
               if ($usuario->sponsor->role_id == 2){
                  $comision2 = new Commission();
                  $comision2->user_id = $usuario->sponsor_id;
                  $comision2->amount = (($comision->amount * 10) / 100);
                  $comision2->type = 'Plan de Afiliado (T-Event)';
                  $comision2->wallet = 'T-Events';
                  $comision2->referred_id = $usuario->id;
                  $comision2->event_subscription_id = $suscripcion->id;
                  $comision2->status = 0;
                  $comision2->date = date('Y-m-d');
                  $comision2->save();

                  $usuario2 = User::find($usuario->sponsor_id);
                  $usuario2->event_balance = $usuario2->event_balance + $comision2->amount;
                  $usuario2->save();

                  //**** Notificar al Patrocinador ***//s
                  $notificacion->store($usuario->sponsor_id, 'Nueva Comisión', 'Tiene una nueva comisión por <b>Plan de Afiliados</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision2->id);
               }
            }

            $comision3 = new Commission();
            $comision3->user_id = $suscripcion->event->user_id;
            $comision3->amount = (($montoFinal * 40) / 100);
            $comision3->type = 'Compra de T-Event';
            $comision3->wallet = 'T-Events';
            $comision3->event_subscription_id = $suscripcion->id;
            $comision3->status = 0;
            $comision3->date = date('Y-m-d');
            $comision3->save();

            $usuario3 = User::find($suscripcion->event->user_id);
            $usuario3->event_balance = $usuario3->event_balance + $comision3->amount;
            $usuario3->save();

            $notificacion->store($suscripcion->event->user_id, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision3->type.'</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision3->id);
         }else{
            $comision = new Commission();
            $comision->user_id = $suscripcion->event->user_id;
            $comision->amount = (($montoFinal * 40) / 100);
            $comision->type = 'Compra de T-Event';
            $comision->wallet = 'T-Events';
            $comision->event_subscription_id = $suscripcion->id;
            $comision->status = 0;
            $comision->date = date('Y-m-d');
            $comision->save();

            $usuario = User::find($suscripcion->event->user_id);
            $usuario->event_balance = $usuario->event_balance + $comision->amount;
            $usuario->save();

            $notificacion->store($suscripcion->event->user_id, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision->type.'</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision->id);
         }

         if ($suscripcion->gift == 1){
            $suscripcion->gift_code = 'T-EVENT-GIFT-'.$suscripcion->id;
            $suscripcion->save();
         }

         //*** Enviar Correo con información del pago y del evento ***//
         $data['infoSuscripcion'] = $suscripcion;

         Mail::send('emails.newEventSubscriptor',['data' => $data], function($msg) use ($data){
            $msg->to($data['infoSuscripcion']->email);
            $msg->subject('Inscripción en Evento TransfórmatePRO');
         });
      }
   }
}