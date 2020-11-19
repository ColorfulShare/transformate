<?php
namespace App\Console\Commands;
 
use Illuminate\Console\Command;
use App\Models\EventSubscription;
use DB; use Carbon\Carbon; use Mail;
 
class eventReminderEmail extends Command{

  	protected $signature = 'eventReminderEmail:mail';
 
  	protected $description = 'Envía correos electrónicos recordando que deben completar el pago para disfrutar del evento.';

  	public function __construct(){
    	parent::__construct();
  	}
 
  	public function handle(){
        $eventosDisponibles = DB::table('events')
                                ->select('id', 'date', 'title')
                                ->where('status', '=', 1)
                                ->where('type', '=', 'pay')
                                ->get();

        $fechaActual = Carbon::now();
        
        foreach ($eventosDisponibles as $evento){
            $fechaEvento = new Carbon($evento->date);

            $usuarios = EventSubscription::where('event_id', '=', $evento->id)
                            ->where('status', '=', 0)
                            ->where('payment_method', '=', NULL)
                            ->where('disabled', '=', 0)
                            ->orderBy('ID', 'ASC')
                            ->get();
            
            $data['dias_restantes'] = $fechaEvento->diffInDays($fechaActual);
            $data['evento'] = $evento->title;
            foreach ($usuarios as $usuario){
                //*** Enviar Correo con información del pago y del evento ***//
                $data['usuario'] = $usuario;

                Mail::send('emails.eventPaymentReminder',['data' => $data], function($msg) use ($data){
                    $msg->to($data['usuario']->email);
                    $msg->subject('Recordatorio de Pago para Evento TransfórmatePRO');
                });
            }
        }
    }
   
}