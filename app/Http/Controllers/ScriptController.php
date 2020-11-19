<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;
use App\Models\Transaction; use App\Models\Commission; use App\Models\EventSubscription;
use App\Models\Liquidation;
use App\Models\User;
use Auth; use DB; use Carbon\Carbon;

class ScriptController extends Controller{
   public function corregir_comisiones_angelica_carrillo(){
      $comisiones = Commission::where('user_id', '=', 658)->where('liquidation_id', '<>', NULL)->orderBy('id')->get();

      foreach ($comisiones as $comision){
         $montoRestante = 0;
         $montoNuevaLiquidacion = 0;
         if ($comision->liquidation_id == 55){
            $comision->status = 2;
            $comision->save();
         }else{
            $datosEvento = DB::table('event_subscriptions')
                              ->where('id', '=', $comision->event_subscription_id)
                              ->first();

            if ($datosEvento->event_id == 5){
               $comision->liquidation_id = 55;
               $comision->status = 2;
               $comision->save();

               $montoRestante = $montoRestante + $comision->amount;
            }else{
               $montoNuevaLiquidacion = $montoNuevaLiquidacion + $comision->amount;
            }
         }
      }

      DB::table('liquidations')
         ->where('id', '=', 65)
         ->update(['amount' => $montoNuevaLiquidacion]);

      $liquidacion = Liquidation::find(55);
      $liquidacion = $liquidacion->amount + $montoRestante;
      $liquidacion->save();


      $usuario = User::find(658);
      $usuario->event_balance = $usuario->event_balance - $montoRestante;
      $usuario->save();

   }

   public function asignar_comisiones_eventos_mentores(){
      $eventos = DB::table('events')
                  ->orderBy('id')
                  ->get();
      
      foreach ($eventos as $evento){
         $suscripciones = EventSubscription::where('event_id', '=', $evento->id)
                     ->where('status', '=', 1)
                     ->where('payment_amount', '<>', 0)
                     ->get();
         
         foreach ($suscripciones as $suscripcion){
            $montoFinal = (($suscripcion->payment_amount * 94.5) / 100);
            if (is_null($suscripcion->instructor_code)){
               $comision = new Commission();
        			$comision->user_id = $evento->user_id;
               $comision->amount = (($montoFinal * 40) / 100);
               $comision->type = 'Compra de T-Event';
        			$comision->event_subscription_id = $suscripcion->id;
        			$comision->status = 0;
        			$comision->date = date('Y-m-d');
        			$comision->save();

			      $usuario = User::find($evento->user_id);
			      $usuario->event_balance = $usuario->event_balance + $comision->amount;
			      $usuario->save();
            }
         }
      }
   }

   public function corregir_comisiones(){
      $compras = DB::table('purchases')
                              ->select('id')
                              ->where('payment_method', '<>', 'MercadoPago')
                              ->get();

      foreach ($comprasMercadoPago as $compra){
         $detallesCompra = DB::table('purchase_details')
                              ->select('id')
                              ->where('purchase_id', '=', $compra->id)
                              ->get();

         foreach ($detallesCompra as $detalle){
            $comisiones = Commission::where('purchase_detail_id', '=', $detalle->id)
                           ->where('status', '=', 0)
                           ->get();

            foreach ($comisiones as $comision){
               $montoFinal = (($comision->amount * 94.5) / 100);
               $comision->amount = $montoFinal;
               $comision->save();
            }       
         }
      }

      $comprasEventos = DB::table('event_subscriptions')
                           ->select('id')
                           ->where('payment_method', '<>', 'MercadoPago')
                           ->get();

      foreach ($comprasEventos as $compraEvento){
         $comisionesEvento = Commission::where('event_subscription_id', '=', $compraEvento->id)
                                 ->where('status', '=', 0)
                                 ->get();

         foreach ($comisionesEvento as $comisionEvento){
            $montoFinalEvento = (($comisionEvento->amount * 94.5) / 100);
            $comisionEvento->amount = $montoFinalEvento;
            $comisionEvento->save();
         }      
      }

      echo "Script Ejecutado Correctamente";
   }
   
   public function reiniciar_billeteras(){
      DB::table('users')
         ->update(['balance' => 0,
                   'event_balance' => 0]);

      $usuarios = DB::table('users')
                     ->select('id')
                     ->get();
      
      foreach ($usuarios as $usuario){
         $comisiones = DB::table('commissions')
                           ->where('user_id', '=', $usuario->id)
                           ->where('status', '=', 0)
                           ->get();

         $totalComisiones = 0;
         $totalComisionesEventos = 0;
         foreach ($comisiones as $comision){
            if (!is_null($comision->purchase_detail_id)){
               $totalComisiones = $totalComisiones + $comision->amount;
            }else{
               $totalComisionesEventos = $totalComisionesEventos + $comision->amount;
            }
         }

         DB::table('users')
               ->where('id', '=', $usuario->id)
               ->update(['balance' => $totalComisiones,
                         'event_balance' => $totalComisionesEventos]);
      }

      echo "Script Ejecutado Correctamente";
   }


   public function desbloquear_lecciones(){
      $lecciones = DB::table('lessons')
                     ->select('id', 'video')
                     ->orderBy('id')
                     ->get();

      foreach ($lecciones as $leccion){
         $cadena = explode(".lock", $leccion->video);

         DB::table('lessons')
            ->where('id', '=', $leccion->id)
            ->update(['video' => $cadena[0]]);
      }
     
      echo "Script Ejecutado Correctamente";
   }

   public function bloquear_lecciones(){
      $lecciones = DB::table('lessons')
                     ->select('id', 'video')
                     ->orderBy('id')
                     ->get();

      foreach ($lecciones as $leccion){
         DB::table('lessons')
            ->where('id', '=', $leccion->id)
            ->update(['video' => $leccion->video.".lock"]);
      }
     
      echo "Script Ejecutado Correctamente";
   }
   public function actualizar_codigos(){
      $usuarios = DB::table('users')
                     ->select('id', 'names', 'last_names')
                     ->orderBy('id', 'ASC')
                     ->get();

      foreach ($usuarios as $usuario){
         $nombreCompleto = $usuario->names." ".$usuario->last_names;
         $slug = Str::slug($nombreCompleto);
         $pic = explode("-", $slug);
         $afiliate_code = $pic[0]."pro".$usuario->id;
         $partner_code = $pic[0]."tp".$usuario->id;
         DB::table('users')
            ->where('id', '=', $usuario->id)
            ->update(['slug' => $slug,
                      'afiliate_code' => $afiliate_code,
                      'partner_code' => $partner_code]);
      }

      echo "Script Ejecutado Correctamente";
   }

   public function actualizar_progresos_cursos(){
      $usuariosConCursos = DB::table('courses_students')
                              ->get();

      foreach ($usuariosConCursos as $usuarioConCurso){
         $modulosCurso = DB::table('modules')
                                ->select('id')
                                ->where('course_id', '=', $usuarioConCurso->course_id)
                                ->get();

         $cantLecciones = 0;
         $leccionesCurso = collect();
         foreach ($modulosCurso as $modulo){
            $leccionesModulo = DB::table('lessons')
                                 ->select('id')
                                 ->where('module_id', '=', $modulo->id)
                                 ->get();

            foreach ($leccionesModulo as $lec){
               $leccionesCurso->push($lec);
            }
         }
         $cantLecciones = $leccionesCurso->count();

         $cantLeccionesFinalizadas = 0; 
         foreach ($leccionesCurso as $leccionCurso){
            $progresoLeccion = DB::table('lesson_progress')
                                 ->where('user_id', '=', $usuarioConCurso->user_id)
                                 ->where('lesson_id', '=', $leccionCurso->id)
                                 ->first();

            if (!is_null($progresoLeccion)){
               if ($progresoLeccion->finished == 1){
                  $cantLeccionesFinalizadas++;
               }
            }
         }

         $progreso = (($cantLeccionesFinalizadas * 100) / $cantLecciones);

         if ($progreso == 100){
            DB::table('courses_students')
               ->where('user_id', '=', $usuarioConCurso->user_id)
               ->where('course_id', '=', $usuarioConCurso->course_id)
               ->update(['progress' => number_format($progreso),
                         'ending_date' => date('Y-m-d')]);
         }else{
            DB::table('courses_students')
               ->where('user_id', '=', $usuarioConCurso->user_id)
               ->where('course_id', '=', $usuarioConCurso->course_id)
               ->update(['progress' => number_format($progreso)]);
         }
      }

      echo "Listo";
   }
   
   public function codigos_amigables(){
      $usuarios = DB::table('users')
                     ->select('id', 'slug')
                     ->get();

      foreach ($usuarios as $usuario){
         if (!is_null($usuario->slug)){
            $pic = explode("-", $usuario->slug);
            $codigo = $pic[0].$usuario->id."transformate2020";
         }else{
            $codigo = $usuario->id."transformate2020";
         }

         DB::table('users')
            ->where('id', '=', $usuario->id)
            ->update(['afiliate_code' => Str::slug($codigo)]);
      }

   }

	public function send_expiration_notifications(){
  		$notificacion = new NotificationController();

      $fechaActual = Carbon::now();
      $fecha15 =  Carbon::now();
      $fecha15->addDay(15);

  		$usuarios = DB::table('users')
                     ->select('id', 'names', 'last_names', 'membership_expiration')
                  	->where('membership_id', '<>', NULL)
   	              	->where('membership_expiration', '>=', $fechaActual)
   	            	->where('membership_expiration', '<=', $fecha15)
   	            	->orderBy('ID', 'ASC')
   	            	->get();

      foreach ($usuarios as $usuario){
   		$fechaVencimiento = new Carbon($usuario->membership_expiration);
   		$dias = $fechaVencimiento->diffInDays($fechaActual); 
      	if ($dias <= 1){
      		$notificacion->store($usuario->id, 'Vencimiento de Suscripción', 'Su suscripción se vence hoy.', 'fas fa-gem', 'students/membership');
   		}else{
         	$notificacion->store($usuario->id, 'Vencimiento de Suscripción', 'Su suscripción se vence en <b>'.$dias.' Días.</b>.', 'fas fa-gem', 'students/membership');
	      }
      }

      echo "Script Ejecutado Correctamente";
   }

   public function update_transactions(){
      $ingresos = DB::table('purchases')
                     ->where('payment_method', '<>', 'Free')
                     ->orderBy('created_at', 'ASC')
                     ->get();

      $egresos = DB::table('liquidations')
                     ->orderBy('created_at', 'ASC')
                     ->get();

      $balance = collect();
      foreach ($ingresos as $ingreso){
         $ingreso->type = 'Ingreso';
         $balance->push($ingreso);
      }

      foreach ($egresos as $egreso){
         $egreso->type = 'Egreso';
         $balance->push($egreso);
      }

      $balance = $balance->sortByDesc('created_at');

      $acum = 0;
      foreach ($balance as $movimiento){
         $transaccion = new Transaction();
         $transaccion->type = $movimiento->type;
         $transaccion->amount = $movimiento->amount;
         $transaccion->operation_id = $movimiento->id;
         $transaccion->date = $movimiento->date;
         if ($movimiento->type == 'Ingreso'){
            $transaccion->balance = $acum + $movimiento->amount;
            $acum = $acum + $movimiento->amount;
         }else{
            $transaccion->balance = $acum - $movimiento->amount;
            $acum = $acum - $movimiento->amount;
         }
         $transaccion->save();
      }

      echo "Script Ejecutado Correctamente";
   }

   public function create_slugs(){
      $cursos = DB::table('courses')
                  ->select('id', 'title')
                  ->orderBy('id')
                  ->get();

      foreach ($cursos as $curso){
         DB::table('courses')
            ->where('id', '=', $curso->id)
            ->update(['slug' => Str::slug($curso->title)]);
      }
      $certificaciones = DB::table('certifications')
                           ->select('id', 'title')
                           ->orderBy('id')
                           ->get();

      foreach ($certificaciones as $certificacion){
         DB::table('certifications')
            ->where('id', '=', $certificacion->id)
            ->update(['slug' => Str::slug($certificacion->title)]);
      }

      $podcasts = DB::table('podcasts')
                  ->select('id', 'title')
                  ->orderBy('id')
                  ->get();

      foreach ($podcasts as $podcast){
         DB::table('podcasts')
            ->where('id', '=', $podcast->id)
            ->update(['slug' => Str::slug($podcast->title)]);
      }

      $usuarios = DB::table('users')
                  ->select('id', 'names', 'last_names')
                  ->orderBy('id')
                  ->get();

      foreach ($usuarios as $usuario){
         $nombre = $usuario->names." ".$usuario->last_names;
         DB::table('users')
            ->where('id', '=', $usuario->id)
            ->update(['slug' => Str::slug($nombre)]);
      }

      $discusiones = DB::table('discussions')
                        ->select('id', 'title')
                        ->orderBy('id')
                        ->get();

      foreach ($discusiones as $discusion){
         DB::table('discussions')
            ->where('id', '=', $discusion->id)
            ->update(['slug' => Str::slug($discusion->title)]);
      }

      echo "Script Ejecutado Correctamente";
   }
}