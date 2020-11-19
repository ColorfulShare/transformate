<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;
use App\Models\EventSubscription; use App\Models\Event; use App\Models\EventImage; use App\Models\Purchase; 
use App\Models\Commission; use App\Models\BankAccount; use App\Models\User; use App\Models\Testimony;
use DB; use Auth; use Mail; use MercadoPago;

class EventController extends Controller{

	public function index(){
		if ( (Auth::guest()) || (Auth::user()->role_id != 3) ){
			$eventos = Event::where('status', '=', 1)
						->with('images')
						->orderBy('date', 'ASC')
						->get();

			$cantEventos = $eventos->count();

			return view('landing.events')->with(compact('eventos', 'cantEventos'));
		}else{
			$eventos = Event::where('status', '=', 1)
						->withCount(['subscriptions' => function ($query){
							$query->where('disabled', '=', 0);
						}])->orderBy('date', 'ASC')
						->get();

			$cantEventos = $eventos->count();

			$mentores = DB::table('users')
							->select('id', 'names', 'last_names', 'email')
							->where('role_id', '=', 2)
							->where('status', '=', 1)
							->orderBy('names', 'ASC')
							->get();

			return view('admins.events.index')->with(compact('eventos', 'cantEventos', 'mentores'));
		}
	}
	
	public function subscribe(Request $request){
		$check = DB::table('event_subscriptions')
					->where('event_id', '=', $request->event_id)
					->where('email', '=', $request->email)
					->where('disabled', '=', 0)
					->first();

		if (is_null($check)){
			$suscriptor = new EventSubscription($request->all());
			if ($request->event_type == 'pay'){
				$suscriptor->status = 0;
			}else{
				$suscriptor->status = 1;
			}
			$suscriptor->save();

			if ($request->event_type == 'pay'){
				return redirect('t-events/payment/'.$suscriptor->id)->with('msj-exitoso', 'Gracias por registrarte para el evento. Por favor completa el pago para finalizar la suscripción.');
			}

			return redirect('t-events')->with('msj-exitoso', 'Gracias por registrarte para el evento. Te contactaremos a tu correo electrónico con la información correspondiente.');	
		}else{
			if ($request->event_type == 'pay'){
				if ($check->status == 0){
					if (is_null($check->payment_method)){
						return redirect('t-events/payment/'.$check->id)->with('msj-exitoso', 'Ya te habías registrado anteriormente pero aún no has completado el pago. Por favor completa el pago para finalizar la suscripción.');
					}else{
						return redirect('t-events/payment-bill/'.$check->id)->with('msj-exitoso', 'Ya te encuentras registrado y tienes un pago en proceso. Por favor espera que aprueben tu transacción.');
					}
				}else if ($check->status == 1){
					return redirect('t-events')->with('msj-erroneo', 'Ya tu correo se encuentra registrado para el evento. Te contactaremos a la brevedad con la información correspondiente.');
				}else{
					return redirect('t-events')->with('msj-erroneo', 'Ya tu correo se encuentra registrado para el evento pero el pago que realizaste fue rechazado. Por favor, contáctanos para ayudarte.');
				}
			}else{
				return redirect('t-events')->with('msj-erroneo', 'Ya tu correo se encuentra registrado para el evento. Te contactaremos a la brevedad con la información correspondiente.');
			}
		}
	}

	public function payment($id_suscriptor){
		$suscriptor = EventSubscription::where('id', '=', $id_suscriptor)
						->with('instructor')
						->first();

		$evento = Event::find($suscriptor->event_id);

		if ( (!is_null($suscriptor->instructor_code)) || (!is_null($suscriptor->partner_code)) ){
			if ($evento->presale == 1){
				$evento->amount = ($evento->presale_price*90)/100;
			}else{
				$evento->amount = ($evento->price*90)/100;
			}
		}else{
			if ($evento->presale == 1){
				$evento->amount = $evento->presale_price;
			}else{
				$evento->amount = $evento->price;
			}
		}

		\MercadoPago\SDK::setAccessToken("APP_USR-1902410051285318-043020-57263f6c7aa781de7dba01fd37458c14-515837620");
        $payment_methods = \MercadoPago\SDK::get("/v1/payment_methods");
        $bancosDisponibles = $payment_methods['body'][8]['financial_institutions'];

		return view('landing.paymentEvent')->with(compact('evento', 'suscriptor', 'bancosDisponibles'));
	}

	public function add_instructor_code(Request $request){
		$instructor = DB::table('users')
						->where('afiliate_code', '=', strtolower($request->codigo))
						->first();

		if (!is_null($instructor)){
			$suscripcion = EventSubscription::find($request->suscriptor_id);
			$suscripcion->instructor_code = $instructor->id;
			$suscripcion->save();

			return redirect('t-events/payment/'.$request->suscriptor_id)->with('msj-exitoso', 'El código de instructor fue aplicado con éxito.');
		}else{
			return redirect('t-events/payment/'.$request->suscriptor_id)->with('msj-erroneo', 'El código de instructor que ingresó es incorrecto.');
		}
	}

	public function add_partner_code(Request $request){
		$partner = DB::table('users')
						->where('partner_code', '=', strtolower($request->codigo))
						->first();

		if (!is_null($partner)){
			$suscripcion = EventSubscription::find($request->suscriptor_id);
			$suscripcion->partner_code = $partner->id;
			$suscripcion->save();

			return redirect('t-events/payment/'.$request->suscriptor_id)->with('msj-exitoso', 'El código de T-Partner fue aplicado con éxito.');
		}else{
			return redirect('t-events/payment/'.$request->suscriptor_id)->with('msj-erroneo', 'El código de T-Partner que ingresó es incorrecto.');
		}
	}

	public function mercado_pago_checkout(Request $request){
        \MercadoPago\SDK::setAccessToken("APP_USR-1902410051285318-043020-57263f6c7aa781de7dba01fd37458c14-515837620");
        $notificacion = new NotificationController();

        try{
            $payment = new MercadoPago\Payment();
            $payment->transaction_amount = $request->amount;
            $payment->token = $request->token;
            $payment->description = "Pago de Evento TransfórmatePro";
            $payment->installments = 1;
            $payment->payment_method_id = $request->paymentMethodId;
            $payment->payer = array("email" => $request->payer_email);
            $payment->save();

            $suscripcion =  EventSubscription::find($request->suscriptor_id);

            if ($payment->status == 'approved'){
            	$suscripcion->payment_method = 'MercadoPago';
            	$suscripcion->payment_id = $payment->id;
            	$suscripcion->payment_amount = $request->amount;
            	$suscripcion->payment_date = date('Y-m-d');
            	$suscripcion->status = 1;
            	$suscripcion->save();

            	$montoFinal = (($suscripcion->payment_amount * 94.5) / 100);
            	if ( (!is_null($suscripcion->instructor_code)) && (!is_null($suscripcion->partner_code)) ){
            		//**** Comisón al instructor por Código Aplicado ***//
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

        			//**** Comisón al T-partner por Código Aplicado ***//
        			$comision2 = new Commission();
        			$comision2->user_id = $suscripcion->partner_code;
                	$comision2->amount = (($montoFinal * 10) / 100);
					$comision2->type = 'Código T-Partner Aplicado en T-Event';
					$comision2->wallet = 'T-Events';
        			$comision2->event_subscription_id = $suscripcion->id;
        			$comision2->status = 0;
        			$comision2->date = date('Y-m-d');
        			$comision2->save();

			        $usuario2 = User::find($suscripcion->partner_code);
			        $usuario2->event_balance = $usuario2->event_balance + $comision2->amount;
			        $usuario2->save();

			        //**** Notificar al Partner ***//
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
            		//**** Comisón al instructor por Código Aplicado ***//
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

			        //**** Notificar al instructor ***//
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
					        $usuario2->event_balance = $usuario2->event_balance + $comision->amount;
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

			        //**** Notificar al instructor ***//
        			$notificacion->store($suscripcion->event->user_id, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision2->type.'</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision2->id);
            	}else{
					$comision = new Commission();
        			$comision->user_id = $suscripcion->event->user_id;
                	$comision->amount = (($montoFinal * 40) / 100);
					$comision->type = 'Compra Orgánica de T-Event';
					$comision->wallet = 'T-Events';
        			$comision->event_subscription_id = $suscripcion->id;
        			$comision->status = 0;
        			$comision->date = date('Y-m-d');
        			$comision->save();

			        $usuario = User::find($suscripcion->event->user_id);
			        $usuario->event_balance = $usuario->event_balance + $comision->amount;
			        $usuario->save();

			        //**** Notificar al instructor ***//
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
	            
                return redirect('t-events/payment-bill/'.$suscripcion->id)->with('msj-exitoso', 'El pago del evento ha sido completado con éxito.');
            }else if ($payment->status == 'in_process'){
            	$suscripcion->payment_method = 'MercadoPago';
            	$suscripcion->payment_id = $payment->id;
            	$suscripcion->payment_amount = $request->amount;
            	$suscripcion->payment_date = date('Y-m-d');
            	$suscripcion->status = 0;
            	$suscripcion->save();

            	return redirect('t-events/payment-bill/'.$suscripcion->id)->with('msj-exitoso', 'Su compra está siendo procesada. En unas horas le avisaremos a su correo el resultado de la misma.');
            }else{
                switch ($payment->status_detail) {
                    case 'cc_rejected_bad_filled_card_number':
                        $msj = 'Por favor verifique el número de tarjeta ingresado.';
                    break;
                    case 'cc_rejected_bad_filled_date':
                        $msj = 'Por favor verifique la fecha de vencimiento de su tarjeta.';
                    break;
                    case 'cc_rejected_bad_filled_other':
                        $msj = 'Por favor verifique los datos ingresados.';
                    break;
                    case 'cc_rejected_bad_filled_security_code':
                        $msj = 'Por favor verifique el código de seguridad de su tarjeta.';
                    break;
                    case 'cc_rejected_blacklist':
                        $msj = 'Su tarjeta se encuentra en la Lista Negra.';
                    break;
                    case 'cc_rejected_call_for_authorize':
                        $msj = 'Su pago requiere autorización por parte del banco emisor. Póngase en contacto con el mismo para más detalles.';
                    break;
                    case 'cc_rejected_card_disabled':
                        $msj = 'Su tarjeta se encuentra inactiva. Póngase en contacto con el banco emisor para más detalles.';
                    break;
                    case 'cc_rejected_duplicated_payment':
                        $msj = 'Ya realizó un pago por el mismo monto recientemente. Por motivos de seguridad su pago ha sido cancelado.';
                    break;
                    case 'cc_rejected_high_risk':
                        $msj = 'Su pago no es seguro. Ha sido rechazado por razones de seguridad.';
                    break;
                     case 'cc_rejected_insufficient_amount':
                        $msj = 'Su tarjeta no posee fondos suficientes para completar el pago.';
                    break;
                     case 'cc_rejected_invalid_installments':
                        $msj = 'Su tarjeta no se encuentra habilitada para realizar pagos en cuotas.  Póngase en contacto con el banco emisor para más detalles.';
                    break;
                     case 'cc_rejected_max_attempts':
                        $msj = 'Has superado el límite de intentos permitidos con tu tarjeta.';
                    break;
                    default:
                        $msj = 'Por favor, intente más tarde.';
                    break;
                }

                return redirect('t-events/payment/'.$suscripcion->id)->with('msj-erroneo', 'Su compra no ha podido ser procesada. '.$msj);
            }
        }catch (\Exception $e) {
            return redirect('t-events/payment/'.$suscripcion->id)->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor intente más tarde.');
        }
    }

    public function bank_transfer_checkout(Request $request){
        \MercadoPago\SDK::setAccessToken("APP_USR-1902410051285318-043020-57263f6c7aa781de7dba01fd37458c14-515837620");

        try{
	       $suscripcion =  EventSubscription::find($request->suscriptor_id);
			if ($suscripcion->gift == 1){
				$email = $suscripcion->gift_buyer;
			}else{
				$email = $suscripcion->email;
			}

	        $payment = new MercadoPago\Payment();
	        $payment->transaction_amount = $request->amount;
	        $payment->description = "Pago de Evento en TransfórmatePro";
	        $payment->payer = array (
	                "email" => $email,
	                "identification" => array(
	                    "type" => $request->document_type,
	                    "number" => $request->document_number
	                ),
	                "entity_type" => "individual"
	            );
	        $payment->transaction_details = array(
	                "financial_institution" => $request->bank_id
	            );
	        $payment->additional_info = array(
	            "ip_address" => '127.0.0.1'
	        );
	        $payment->callback_url = "https://www.transformatepro.com/t-events/payment-bill/".$suscripcion->id;
	        $payment->payment_method_id = "pse";
	        $payment->save();

	        if ($payment->status == 'pending'){
	            $suscripcion->payment_method = 'Transferencia Bancaria';
	            $suscripcion->payment_id = $payment->id;
	            $suscripcion->payment_amount = $request->amount;
	            $suscripcion->payment_date = date('Y-m-d');
	            $suscripcion->payment_url = $payment->transaction_details->external_resource_url;
	            $suscripcion->status = 0;
	            $suscripcion->save();

	            return redirect('t-events/payment-bill/'.$suscripcion->id)->with('msj-exitoso', 'La transacción ha sido creada con éxito. Debe completar el pago en el botón que aparece a continuación para disfrutar de su compra.');
	        }else{
	           	return redirect('t-events/payment/'.$suscripcion->id)->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
	        }
	    }catch (\Exception $e) {
            return redirect('t-events/payment/'.$suscripcion->id)->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor intente más tarde.');
        }
    }

    public function efecty_checkout(Request $request){
        \MercadoPago\SDK::setAccessToken("APP_USR-1902410051285318-043020-57263f6c7aa781de7dba01fd37458c14-515837620");

        try{
        	$suscripcion =  EventSubscription::find($request->suscriptor_id);
			if ($suscripcion->gift == 1){
				$email = $suscripcion->gift_buyer;
			}else{
				$email = $suscripcion->email;
			}

            $payment = new MercadoPago\Payment();
            $payment->transaction_amount = $request->amount;
            $payment->description = "Pago de Evento en TransfórmatePro";
            $payment->payment_method_id = "efecty";
            $payment->payer = array(
                "email" => $email
            );
            $payment->save();

            if ($payment->status == 'pending'){
            	$suscripcion->payment_method = 'Efectivo';
	            $suscripcion->payment_id = $payment->id;
	            $suscripcion->payment_amount = $request->amount;
	            $suscripcion->payment_date = date('Y-m-d');
	            $suscripcion->payment_url = $payment->transaction_details->external_resource_url;
	            $suscripcion->status = 0;
	            $suscripcion->save();

	            return redirect('t-events/payment-bill/'.$suscripcion->id)->with('msj-exitoso', 'La transacción ha sido creada con éxito. Debe completar el pago en el link que aparece a continuación para disfrutar de su compra.');
            }else{
                return redirect('t-events/payment/'.$suscripcion->id)->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
            }
        }catch (\Exception $e) {
            return redirect('t-events/payment/'.$suscripcion->id)->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
        }
    }

    public function payment_bill($suscripcion){
    	$datosSuscripcion = EventSubscription::find($suscripcion);

    	$cuentasDisponibles = BankAccount::where('status', '=', 1)
                                    ->orderBy('bank', 'ASC')
                                    ->get();

    	return view('landing.paymentBill')->with(compact('datosSuscripcion', 'cuentasDisponibles'));
    }

    public function buy_to_gift(Request $request){
    	$check = DB::table('event_subscriptions')
					->where('event_id', '=', $request->event_id)
					->where('gift_buyer', '=', $request->gift_buyer)
					->where('disabled', '=', 0)
					->where('status', '=', 0)
					->first();

		if (is_null($check)){
			$suscriptor = new EventSubscription($request->all());
	    	$suscriptor->gift = 1;
			$suscriptor->status = 0;
			$suscriptor->save();
		
			return redirect('t-events/payment/'.$suscriptor->id)->with('msj-exitoso', 'Gracias por interesarte en el evento. Por favor completa el pago para finalizar la compra de la T-Gift.');
		}else{
			if (is_null($check->payment_method)){
				return redirect('t-events/payment/'.$check->id)->with('msj-exitoso', 'Ya tienes una compra de T-GIFT pendiente pero aún no has completado el pago. Por favor completa el pago para finalizar la compra.');
			}else{
				return redirect('t-events/payment-bill/'.$check->id)->with('msj-exitoso', 'Ya tienes una compra de T-GIFT en proceso. Por favor espera que aprueben tu transacción.');
			}
		}
    }

    public function redeem_gift(Request $request){
    	$check = DB::table('event_subscriptions')
					->where('gift_code', '=', $request->gift_code)
					->first();

		if (!is_null($check)){
			if ( ($check->disabled == 0) && ($check->status == 1) && ($check->gift_status == 0) ){
				$suscriptor = EventSubscription::find($check->id);
				$suscriptor->fill($request->all());
		    	$suscriptor->gift_status = 1;
				$suscriptor->save();
			
				return redirect('t-events/payment-bill/'.$suscriptor->id)->with('msj-exitoso', '¡Felicidades! Tu T-Gift ha sido aplicada con éxito. Ya te encuentras inscrito en el evento.');
			}else{
				return redirect('t-events')->with('msj-erroneo', 'Disculpe. El código ingresado no se encuentra disponible.');
			}
		}else{
			return redirect('t-events')->with('msj-erroneo', 'Disculpe. El código ingresado no es correcto.');		
		}
    }

	public function store(Request $request){
		$evento = new Event($request->all());
		$evento->slug = Str::slug($request->title);
		if (!is_null($request->benefits)){
			$evento->benefits = json_encode($request->benefits);
		}else{
			$evento->benefits = NULL;
		}

		if ($request->hasFile('image')){
            $file = $request->file('image');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/uploads/events/images', $name);
            $evento->image = $name;
        }

        if ($request->hasFile('image_movil')){
            $file2 = $request->file('image_movil');
            $name2 = time().$file2->getClientOriginalName();
            $file2->move(public_path().'/uploads/events/images', $name2);
            $evento->image_movil = $name2;
        }

        if ($request->hasFile('informative_pdf')){
            $file3 = $request->file('informative_pdf');
            $name3 = time().$file3->getClientOriginalName();
            $file3->move(public_path().'/uploads/events/documents', $name3);
            $evento->informative_pdf = $name3;
        }

        if ($request->hasFile('video')){
            $file4 = $request->file('video');
            $name4 = time().$file4->getClientOriginalName();
            $file4->move(public_path().'/uploads/events/videos', $name4);
            $evento->video = $name4;
        }
		
		if ($request->hasFile('benefits_img')){
            $file6 = $request->file('benefits_img');
            $name6 = time().$file6->getClientOriginalName();
            $file6->move(public_path().'/uploads/events/benefits', $name6);
            $evento->benefits_img = $name6;
        }

		$evento->save();
		
		if (!is_null($request->testimony)){
			$testimonio = new Testimony();
			$testimonio->event_id = $evento->id;
			$testimonio->text = $request->testimony;
			$testimonio->autor = $request->testimony_autor;
			if ($request->hasFile('testimony_img')){
				$file5 = $request->file('testimony_img');
				$name5 = time().$file5->getClientOriginalName();
				$file5->move(public_path().'/uploads/events/testimonies', $name5);
				$testimonio->image = $name5;
			}
			$testimonio->save();
		}

        $fecha = date('Y-m-d H:i:s');
        $imagenesSlider = $request->file('slider_images');

		if($request->hasFile('slider_images')){
		    foreach ($imagenesSlider as $imagenSlider) {
		    	$imagenSliderNombre = time().$imagenSlider->getClientOriginalName();
		        $imagenSlider->move(public_path().'/uploads/events/images', $imagenSliderNombre);
		        

		        DB::table('events_images')
		        	->insert(['event_id' => $evento->id, 'image' => $imagenSliderNombre, 'movil' => 0, 'created_at' => $fecha, 'updated_at' => $fecha]);
		    }
		}

        $imagenesSliderMovil = $request->file('slider_images_movil');

		if($request->hasFile('slider_images_movil')){
		    foreach ($imagenesSliderMovil as $imagen) {
		    	$imagenNombre = time().$imagen->getClientOriginalName();
		        $imagen->move(public_path().'/uploads/events/images', $imagenNombre);
		        
		        DB::table('events_images')
		        	->insert(['event_id' => $evento->id, 'image' => $imagenNombre, 'movil' => 1, 'created_at' => $fecha, 'updated_at' => $fecha]);
		    }
		}

        return redirect("admins/t-events")->with('msj-exitoso', 'El evento ha sido creado con éxito.');
	}

	public function show($slug, $id){
		if ( (Auth::guest()) || (Auth::user()->role_id != 3) ){
			$evento = Event::where('id', '=', $id)
						->withCount('images', 'testimonies', 'subscriptions')
						->first();
			
			$cantImagenesMentores = DB::table('events_images')
										->where('event_id', '=', $id)
										->where('instructor_section', '=', 1)
										->count();

			$countdown_limit = NULL;
			if ($evento->presale == 1){
				$countdown_limit = date('M j\, Y H:i:s', strtotime($evento->presale_datetime));
			}

			return view('landing.showEvent')->with(compact('evento', 'countdown_limit', 'cantImagenesMentores'));
		}else{
			$evento = Event::where('id', '=', $id)
						->withCount('images')
						->first();

			$imagenes = $evento->images;
			$imagenes = $imagenes->sortBy('id');

			$mentores = DB::table('users')
							->select('id', 'names', 'last_names', 'email')
							->where('role_id', '=', 2)
							->where('status', '=', 1)
							->orderBy('names', 'ASC')
							->get();

			return view('admins.events.show')->with(compact('evento', 'imagenes', 'mentores'));
		}
	}

	public function update(Request $request){
		if (isset($request->testimony_id)){
			$name = 'text-'.$request->testimony_id;
			$testimonio = Testimony::find($request->testimony_id);
			$testimonio->text = $request->$name;
			$testimonio->fill($request->all());

			if ($request->hasFile('image')){
				$file = $request->file('image');
				$name = time().$file->getClientOriginalName();
				$file->move(public_path().'/uploads/events/testimonies', $name);
				$testimonio->image = $name;
			}
			$testimonio->save();

			return redirect('admins/t-events/show/'.$testimonio->event->slug.'/'.$testimonio->event_id)->with('msj-exitoso', 'Los datos del testimonio han sido actualizados con éxito.');
		}

		if (isset($request->mentor_image)){
			if ($request->hasFile('image')){
				$evento = Event::find($request->event_id);
				
				$file = $request->file('image');
				$name = time().$file->getClientOriginalName();
				$file->move(public_path().'/uploads/events/images', $name);

				if (!is_null($evento->mentor_section_img)){
					$imagen = new EventImage();
					$imagen->event_id = $request->event_id;
					$imagen->movil = 0;
					$imagen->instructor_section = 1;
					$imagen->image = $name;
					$imagen->save();
				}else{
					$evento->mentor_section_img = $name;
					$evento->save();
				}
			}

			return redirect('admins/t-events/show/'.$request->event_slug.'/'.$request->event_id)->with('msj-exitoso', 'La imagen del mentor ha sido cargada con éxito.');
		}

		$evento = Event::find($request->event_id);
		$evento->fill($request->all());
		$evento->slug = Str::slug($evento->title);

		if ($request->hasFile('mentor_section_img')){
			$file2 = $request->file('mentor_section_img');
			$name2 = time().$file2->getClientOriginalName();
			$file2->move(public_path().'/uploads/events/images', $name2);
			$evento->mentor_section_img = $name2;
		}

		if ($request->hasFile('credits_img')){
			$file3 = $request->file('credits_img');
			$name3 = time().$file3->getClientOriginalName();
			$file3->move(public_path().'/uploads/events/images', $name3);
			$evento->credits_img = $name3;
		}

		$evento->save();
		
		return redirect('admins/t-events/show/'.$evento->slug.'/'.$evento->id)->with('msj-exitoso', 'Los datos del evento han sido actualizados con éxito');
	}

	public function delete_image($id){
		$imagen = EventImage::find($id);

		$imagenVieja = public_path().'/uploads/events/images/'.$imagen->image;
    	if (@getimagesize($imagenVieja)) {
    		unlink($imagenVieja);
		}
		$imagen->delete();

		return redirect('admins/t-events/show/'.$imagen->event->slug.'/'.$imagen->event_id)->with('msj-exitoso', 'La imagen del mentor ha sido eliminada con éxito');
	}

	public function change_file(Request $request){
		$evento = Event::find($request->event_id);

		if ($request->file_type == 'pc'){
			$imagenVieja = public_path().'/uploads/events/images/'.$evento->image;
    		if (@getimagesize($imagenVieja)) {
    			unlink($imagenVieja);
    		}

			if ($request->hasFile('image')){
	            $file = $request->file('image');
	            $name = time().$file->getClientOriginalName();
	            $file->move(public_path().'/uploads/events/images', $name);
	            $evento->image = $name;
	            $evento->save();
	        }
		}else if ($request->file_type == 'movil'){
			$imagenVieja = public_path().'/uploads/events/images/'.$evento->image_movil;
    		if (@getimagesize($imagenVieja)) {
    			unlink($imagenVieja);
    		}

			if ($request->hasFile('image')){
	            $file = $request->file('image');
	            $name = time().$file->getClientOriginalName();
	            $file->move(public_path().'/uploads/events/images', $name);
	            $evento->image_movil = $name;
	            $evento->save();
	        }
		}else if ($request->file_type == 'pdf'){
			$imagenVieja = public_path().'/uploads/events/documents/'.$evento->informative_pdf;
    		if (@getimagesize($imagenVieja)) {
    			unlink($imagenVieja);
    		}

			if ($request->hasFile('image')){
	            $file = $request->file('image');
	            $name = time().$file->getClientOriginalName();
	            $file->move(public_path().'/uploads/events/documents', $name);
	            $evento->informative_pdf = $name;
	            $evento->save();
	        }
		}else if ($request->file_type == 'video'){
			$imagenVieja = public_path().'/uploads/events/videos/'.$evento->video;
    		if (@getimagesize($imagenVieja)) {
    			unlink($imagenVieja);
    		}

			if ($request->hasFile('image')){
	            $file = $request->file('image');
	            $name = time().$file->getClientOriginalName();
	            $file->move(public_path().'/uploads/events/videos', $name);
	            $evento->video = $name;
	            $evento->save();
	        }
		}else if ($request->file_type == 'benefits'){
			$imagenVieja = public_path().'/uploads/events/benefits/'.$evento->benefits_img;
    		if (@getimagesize($imagenVieja)) {
    			unlink($imagenVieja);
    		}

			if ($request->hasFile('image')){
	            $file = $request->file('image');
	            $name = time().$file->getClientOriginalName();
	            $file->move(public_path().'/uploads/events/benefits', $name);
	            $evento->benefits_img = $name;
	            $evento->save();
	        }
		}else{
			$imagenEvento = EventImage::find($request->file_type);
			$imagenVieja = public_path().'/uploads/events/images/'.$imagenEvento->image;
    		if (@getimagesize($imagenVieja)) {
    			unlink($imagenVieja);
    		}

			if ($request->hasFile('image')){
	            $file = $request->file('image');
	            $name = time().$file->getClientOriginalName();
	            $file->move(public_path().'/uploads/events/images', $name);
	            $imagenEvento->image = $name;
	            $imagenEvento->save();
	        }
		}

		return redirect('admins/t-events/show/'.$evento->slug.'/'.$evento->id)->with('msj-exitoso', 'La imagen ha sido cambiada con éxito');
	}

	public function add_testimony(Request $request){
		$testimonio = new Testimony($request->all());
		if ($request->hasFile('image')){
			$file = $request->file('image');
			$name = time().$file->getClientOriginalName();
			$file->move(public_path().'/uploads/events/testimonies', $name);
			$testimonio->image = $name;
		}
		$testimonio->save();

		return redirect('admins/t-events/show/'.$request->event_slug.'/'.$request->event_id)->with('msj-exitoso', 'El nuevo testimonio ha sido agregado con éxito.');
	}

	public function show_subscriptions($slug, $id){
		$evento = Event::where('id', '=', $id)
					->with(['subscriptions' => function ($query){
						$query->where('disabled', '=', 0);
					}])->first();

		return view('admins.events.subscriptions')->with(compact('evento'));
	}

	public function show_subscription($id){
		$suscripcion = EventSubscription::find($id);

		return view('admins.events.showSubscription')->with(compact('suscripcion'));
	}

	public function delete_subscription($id){
		$suscripcion = EventSubscription::find($id);
		$suscripcion->disabled = 1;
		$suscripcion->save();

		return redirect("admins/t-events/subscriptions/".$suscripcion->event->slug.'/'.$suscripcion->event->id)->with('msj-exitoso', 'El suscriptor ha sido eliminado con éxito.');
	}

	public function disabled($id, $status){
		DB::table('events')
			->where('id', '=', $id)
			->update(['status' => $status]);
		
		if ($status == 0){
			return redirect("admins/t-events")->with('msj-exitoso', 'El evento ha sido deshabilitado con éxito.');
		}else{
			return redirect("admins/t-events/record")->with('msj-exitoso', 'El evento ha sido habilitado con éxito.');
		}
	}

	public function record(){
		$eventos = Event::where('status', '=', 0)
						->withCount(['subscriptions' => function ($query){
							$query->where('disabled', '=', 0);
						}])->orderBy('date', 'DESC')
						->get();

		return view('admins.events.record')->with(compact('eventos'));
	}

	public function send_mail(Request $request){
		if ($request->selection == 0){
			$usuarios = DB::table('event_subscriptions')
						->select('email')
						->where('event_id', '=', $request->event_id)
						->where('disabled', '=', 0)
						->get();
		}else{
			$usuarios = DB::table('event_subscriptions')
						->select('email')
						->whereIn('id', $request->suscriptors_selected)
						->get();
		}

		$data['adjunto'] = NULL;
		if ($request->hasFile('adjunto')){
            $file = $request->file('adjunto');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/documents/events/', $name);
            $data['adjunto'] = $name;
        }

        $data['content'] = $request->description;
        $data['title'] = $request->title;

        foreach ($usuarios as $usuario){
        	$data['correo'] = $usuario->email; 
        	Mail::send('emails.eventSubscription',['data' => $data], function($msg) use ($data){
                $msg->to($data['correo']);
                $msg->subject($data['title']);
                if (!is_null($data['adjunto'])){
                	$msg->attach('https://www.transformatepro.com/documents/events/'.$data['adjunto']);
                }
            });
        } 

        return redirect("admins/t-events/subscriptions/".$request->event_slug."/".$request->event_id)->with('msj-exitoso', 'El correo ha sido enviado a los suscriptores con éxito.'); 
	}

	public function add_video_view_counter($id){
		$evento = Event::find($id);
		$evento->video_view_counter = $evento->video_view_counter + 1;
		$evento->save();

		return response()->json(
			$evento->video_view_counter
		);
	}

	public function add_presale(Request $request){
		$evento = Event::find($request->event_id);
		$evento->fill($request->all());
		$evento->presale_datetime = $request->presale_date."T".$request->presale_time;
		$evento->presale = 1;
		$evento->save();

		return redirect("admins/t-events/show/".$evento->slug."/".$evento->id)->with('msj-exitoso', 'La preventa ha sido creada con éxito.'); 
	}

	public function delete_presale($id){
		$evento = Event::find($id);
		$evento->presale_price = NULL;
		$evento->presale_datetime = NULL;
		$evento->presale = 0;
		$evento->save();

		return redirect("admins/t-events/show/".$evento->slug."/".$evento->id)->with('msj-exitoso', 'La preventa ha sido quitada con éxito.'); 
	}
}