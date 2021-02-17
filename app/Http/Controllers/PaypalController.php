<?php 
 
namespace App\Http\Controllers;
 
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
 
use PayPal\Rest\ApiContext; use PayPal\Auth\OAuthTokenCredential; use PayPal\Api\Amount;
use PayPal\Api\Details; use PayPal\Api\Item; use PayPal\Api\ItemList; use PayPal\Api\Payer;
use PayPal\Api\Payment; use PayPal\Api\RedirectUrls; use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution; use PayPal\Api\Transaction;
use GuzzleHttp\Client;
use App\Models\Product; use App\Models\Purchase; use App\Models\PurchaseDetail; use App\Models\User;
use App\Models\Membership; use App\Models\Transaction as Trans; use App\Models\EventSubscription; use App\Models\Commission;
use App\Models\Gift;
use Auth; use DB; use Carbon\Carbon; use Mail;
 
class PaypalController extends BaseController
{
	private $_api_context;
 
	public function __construct(){
		// setup PayPal api context
		$paypal_conf = \Config::get('paypal');
		$this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
		$this->_api_context->setConfig($paypal_conf['settings']);
	}
 
	public function checkout(Request $request){
		$payer = new Payer();
		$payer->setPaymentMethod('paypal');
 
		$items = array();
		/*$subtotal = 0;
		$cart = Product::where('user_id', '=', Auth::user()->id)->orderBy('date', 'DESC')->get();*/
		$currency = 'USD';
        $trm = 3500;
        $monto = $request->amount;
        $tasa = $monto / $trm;
  
 		$item = new Item();
 		$item->setName('Carrito de Compra TransfórmatePro')
					->setCurrency($currency)
					->setDescription('Compra desde la Plataforma TransfórmatePro')
					->setQuantity(1)
					->setPrice($tasa);
		$items[] = $item;
 		$subtotal = $tasa;
		$total = $tasa;

		$item_list = new ItemList();
		$item_list->setItems($items);
 
		$details = new Details();
		$details->setSubtotal($subtotal);
 
		$amount = new Amount();
		$amount->setCurrency($currency)
			->setTotal($total)
			->setDetails($details);
 
		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setItemList($item_list)
			->setDescription('Pedido de prueba en mi Laravel App Store');
 	
 		if (isset($request->gift)){
 			$redirect_urls = new RedirectUrls();
			$redirect_urls->setReturnUrl(\URL::route('landing.shopping-cart.process-gift-membership'))
				->setCancelUrl(\URL::route('landing.shopping-cart.process-gift-membership'));
 		}else{
 			$redirect_urls = new RedirectUrls();
 			if (Auth::user()->role_id == 1){
 				$redirect_urls->setReturnUrl(\URL::route('students.shopping-cart.payment-status'))
					->setCancelUrl(\URL::route('students.shopping-cart.payment-status'));
 			}else{
 				$redirect_urls->setReturnUrl(\URL::route('instructors.shopping-cart.payment-status'))
					->setCancelUrl(\URL::route('instructors.shopping-cart.payment-status'));
 			}
 		}
		
		$payment = new Payment();
		$payment->setIntent('Sale')
			->setPayer($payer)
			->setRedirectUrls($redirect_urls)
			->setTransactions(array($transaction));
 
		try {
			$payment->create($this->_api_context);
		} catch (\PayPal\Exception\PPConnectionException $ex) {
			if (\Config::get('app.debug')) {
				echo "Exception: " . $ex->getMessage() . PHP_EOL;
				$err_data = json_decode($ex->getData(), true);
				exit;
			} else {
				die('Ups! Algo salió mal');
			}
		}
 
		foreach($payment->getLinks() as $link) {
			if($link->getRel() == 'approval_url') {
				$redirect_url = $link->getHref();
				break;
			}
		}
 
		// add payment ID to session
		\Session::put('paypal_payment_id', $payment->getId());
 
		if(isset($redirect_url)) {
			return \Redirect::away($redirect_url);
		}
 
		return \Redirect::route('cart-show')
			->with('message', 'Ups! Error desconocido.');
	}
 
	public function payment_status(Request $request){
		$comisiones = new CommissionController();
		$notificacion = new NotificationController();

		$payment_id = $request->get('paymentId');
		\Session::forget('paypal_payment_id');
 
		$payerId = \Input::get('PayerID');
		$token = \Input::get('token');
 
		if (empty($payerId) || empty($token)) {
			return \Redirect::route('home')
				->with('message', 'Hubo un problema al intentar pagar con Paypal');
		}
 
		$payment = Payment::get($payment_id, $this->_api_context);
 
		$execution = new PaymentExecution();
		$execution->setPayerId(\Input::get('PayerID'));
 
		$result = $payment->execute($execution, $this->_api_context);
 
 
		if ($result->getState() == 'approved') {
			$items = Product::where('user_id', '=', Auth::user()->id)
						->get();
            
            $cuponAbierto = DB::table('applied_coupons')
                                ->select('id', 'coupon_id')
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('status', '=', 1)
                                ->first();

            if (!is_null($cuponAbierto)){
            	$cuponAplicado = DB::table('coupons')
            						->select('id', 'discount')
            						->where('id', '=', $cuponAbierto->coupon_id)
            						->first();
            }else{
            	$cuponAplicado = NULL;
            }

	        $totalOriginal = 0;
			$totalCompra = 0;
            if (!is_null($cuponAplicado)){
                foreach ($items as $item){
					if (!is_null($item->course_id)){
						$totalOriginal += $item->course->price;
						if (is_null(Auth::user()->membership_id)){
							$totalCompra += ( $item->course->price - (($item->course->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->course->price * 70) / 100);
                            $totalCompra += ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
					}else if (!is_null($item->certification_id)){
						$totalOriginal += $item->certification->price;
						if (is_null(Auth::user()->membership_id)){
							$totalCompra += ( $item->certification->price - (($item->certification->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->certification->price * 70) / 100);
                            $totalCompra += ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
					}else if (!is_null($item->podcast_id)){
						$totalOriginal += $item->podcast->price;
						if (is_null(Auth::user()->membership_id)){
							$totalCompra += ( $item->podcast->price - (($item->podcast->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->podcast->price * 70) / 100);
                            $totalCompra += ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
					}else if (!is_null($item->membership_id)){
						$totalOriginal += $item->membership->price;
						$totalCompra += ( ($item->membership->price * $cuponAplicado->discount) / 100);
					}else if (!is_null($item->product_id)){
						$totalOriginal += $item->market_product->price;
						$totalCompra += ( ($item->market_product->price * $cuponAplicado->discount) / 100);
					}
				} 
            }else{
            	foreach ($items as $item){
					if (!is_null($item->course_id)){
						$totalOriginal += $item->course->price;
						if (is_null(Auth::user()->membership_id)){
							$totalCompra += $item->course->price;
                        }else{
                            $totalCompra += (($item->course->price * 70) / 100);
                        }
					}else if (!is_null($item->certification_id)){
						$totalOriginal += $item->certification->price;
						if (is_null(Auth::user()->membership_id)){
							$totalCompra += $item->certification->price;
                        }else{
                            $totalCompra += (($item->certification->price * 70) / 100);
                        }
					}else if (!is_null($item->podcast_id)){
						$totalOriginal += $item->podcast->price;
						if (is_null(Auth::user()->membership_id)){
							$totalCompra += $item->podcast->price;
                        }else{
                            $totalCompra += (($item->podcast->price * 70) / 100);
                        }
					}else if (!is_null($item->membership_id)){
						$totalOriginal += $item->membership->price;
						$totalCompra += $item->membership->price;
					}else if (!is_null($item->product_id)){
						$totalOriginal += $item->market_product->price;
						$totalCompra += $item->market_product->price;
					}
				} 
            }

            if ( ($item->instructor_code == 1) || (!is_null($item->partner_code)) ){
            	$totalCompra = (($totalCompra * 90) / 100);
            }

			$compra = new Purchase();
			$compra->user_id = Auth::user()->id;
			$compra->original_amount = $totalOriginal;
			$compra->amount = $totalCompra;
			$compra->payment_method = 'Paypal';
			$compra->payment_id = $payment_id;
			if (!is_null($cuponAplicado)){
				$compra->coupon_id = $cuponAplicado->id;
			}
			if (!is_null(Auth::user()->membership_id)){
				$compra->membership_discount = 1;
			}
			//$compra->instructor_code_discount = $descuentoCodigo;
			$compra->date = date('Y-m-d');
			$compra->save();

			foreach ($items as $item2){
				$detalle = new PurchaseDetail();
				$detalle->purchase_id = $compra->id;
				$detalle->instructor_code = $item2->instructor_code;
				$detalle->partner_code = $item2->partner_code;
				
				if (!is_null($item2->course_id)){
					$detalle->course_id = $item2->course_id;
					$detalle->original_amount = $item2->course->price;
					if (!is_null($cuponAplicado)){
						if (is_null(Auth::user()->membership_id)){
							$detalle->amount = ( $item2->course->price - (($item2->course->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item2->course->price * 70) / 100);
                            $detalle->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
					}else{
						if (is_null(Auth::user()->membership_id)){
							$detalle->amount = $item2->course->price;
                        }else{
                            $detalle->amount = (($item2->course->price * 70) / 100);
                        }
					}
				}else if (!is_null($item2->certification_id)){
					$detalle->certification_id = $item2->certification_id;
					$detalle->original_amount = $item2->certification->price;
					if (!is_null($cuponAplicado)){
						if (is_null(Auth::user()->membership_id)){
							$detalle->amount = ( $item2->certification->price - (($item2->certification->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item2->certification->price * 70) / 100);
                            $detalle->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
					}else{
						if (is_null(Auth::user()->membership_id)){
							$detalle->amount = $item2->certification->price;
                        }else{
                            $detalle->amount = (($item2->certification->price * 70) / 100);
                        }
					}
				}else if (!is_null($item2->podcast_id)){
					$detalle->podcast_id = $item2->podcast_id;
					$detalle->original_amount = $item2->podcast->price;
					if (!is_null($cuponAplicado)){
						if (is_null(Auth::user()->membership_id)){
							$detalle->amount = ( $item2->podcast->price - (($item2->podcast->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item2->podcast->price * 70) / 100);
                            $detalle->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
					}else{
						if (is_null(Auth::user()->membership_id)){
							$detalle->amount = $item2->podcast->price;
                        }else{
                            $detalle->amount = (($item2->podcast->price * 70) / 100);
                        }
					}
				}else if (!is_null($item2->membership_id)){
					$detalle->membership_id = $item2->membership_id;
					$detalle->original_amount = $item2->membership->price;
					if (!is_null($cuponAplicado)){
						$detalle->amount = (($item2->membership->price * $cuponAplicado->discount) / 100);
					}else{
						$detalle->amount = $item2->membership->price;
					}
				}else if (!is_null($item2->product_id)){
					$detalle->product_id = $item2->product_id;
					$detalle->original_amount = $item2->market_product->price;
					if (!is_null($cuponAplicado)){
						$detalle->amount = ( $item2->market_product->price - (($item2->market_product->price * $cuponAplicado->discount) / 100));
					}else{
						$detalle->amount = $item2->market_product->price;
					}
				}
				$detalle->instructor_code = $item2->instructor_code;
				if ( ($item->instructor_code == 1) || (!is_null($item->partner_code)) ){
					$detalle->amount = (($detalle->amount * 90) / 100);
				}
				$detalle->save();

				if (!is_null($item2->course_id)){
					if ($item2->gift == 0){
						Auth::user()->courses_students()->attach($item2->course_id, ['start_date' => date('Y-m-d')]);
					}else{
						$regalo = new Gift();
                        $regalo->buyer_id = Auth::user()->id;
                        $regalo->course_id = $item->course_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
					}

					if ($item2->course->price > 0){
                        $comisiones->store($item2->course_id, 'curso', $item2->instructor_code, $item2->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item2->course->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Course <b>'.$item2->course->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-courses/purchases-record/'.$item2->course->slug.'/'.$item2->course_id);
				}else if (!is_null($item2->certification_id)){
					if ($item2->gift == 0){
						Auth::user()->certifications_students()->attach($item2->certification_id, ['start_date' => date('Y-m-d')]);
					}else{
						$regalo = new Gift();
                        $regalo->buyer_id = Auth::user()->id;
                        $regalo->certification_id = $item->certification_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
					}

					if ($item2->certification->price > 0){
                        $comisiones->store($item2->certification_id, 'certificacion', $item2->instructor_code, $item2->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item2->certification->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Mentoring <b>'.$item2->ertification->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-mentorings/purchases-record/'.$item2->certification->slug.'/'.$item2->certification_id);
				}else if (!is_null($item2->podcast_id)){
					if ($item2->gift == 0){
						Auth::user()->podcasts_students()->attach($item2->podcast_id);
					}else{
						$regalo = new Gift();
                        $regalo->buyer_id = Auth::user()->id;
                        $regalo->podcast_id = $item->podcast_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
					}

					if ($item2->podcast->price > 0){
                        $comisiones->store($item2->podcast_id, 'podcast', $item2->instructor_code, $item2->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item2->podcast->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Book <b>'.$item2->podcast->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-books/purchases-record/'.$item2->podcast->slug.'/'.$item2->podcast_id);
				}else if (!is_null($item2->membership_id)){
					$fechaActual = new Carbon();
                	$fechaExpiracion = $fechaActual->addYear();

	                DB::table('users')
	                    ->where('id', '=', Auth::user()->id)
	                    ->update(['membership_id' => $item2->membership_id,
	                              'membership_expiration' => $fechaExpiracion]);
				}else if (!is_null($item2->product_id)){
					if ($item2->gift == 0){
						Auth::user()->products_users()->attach($item2->product_id, ['date' => date('Y-m-d')]);
					}else{
						$regalo = new Gift();
                        $regalo->buyer_id = Auth::user()->id;
                        $regalo->product_id = $item->product_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
					}
					
					if ($item2->market_product->price > 0){
                        $comisiones->store($item2->product_id, 'producto', $item2->instructor_code, $item2->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item2->market_product->user_id, 'Nueva Compra', 'Tiene una nueva compra de su Producto <b>'.$item2->market_product->name.'</b>', 'fa fa-shopping-cart', 'instructors/products/purchases-record/'.$item2->market_product->slug.'/'.$item2->product_id);
				}
				
				$item2->delete();
			}

			if (!is_null($cuponAplicado)){
				DB::table('applied_coupons')
                       ->where('id', '=', $cuponAbierto->id)
                        ->update(['status' => 0,
                                  'closed_at' => date('Y-m-d')]);
			}

			$ultBalance = DB::table('transactions')
	                        ->select('balance')
	                        ->orderBy('created_at', 'DESC')
	                        ->first();

            $transaccion = new Trans();
            $transaccion->type = 'Ingreso';
            $transaccion->amount = $compra->amount;
            $transaccion->operation_id = $compra->id;
            $transaccion->date = date('Y-m-d');
            if (!is_null($ultBalance)){
            	$transaccion->balance = $ultBalance->balance + $compra->amount;
            }else{
            	$transaccion->balance = $compra->amount;
            }
			$transaccion->save();

            //*** Enviar Correo con información del pago y del contenido comprado ***//
            $data['infoCompra'] = $compra;
            $data['productos'] = PurchaseDetail::where('purchase_id', '=', $compra->id)->with('gift')->get();
            $data['usuario'] = Auth::user()->names." ".Auth::user()->last_names;
            $data['email'] = Auth::user()->email;

            Mail::send('emails.newPurchase',['data' => $data], function($msg) use ($data){
                $msg->to($data['email']);
                $msg->subject('Compra de Contenido en TransfórmatePRO');
            });
			
            return redirect('shopping-cart/bill/paypal/'.$compra->id)->with('msj-exitoso', 'El pago ha sido completado con éxito.');

		}

		return redirect('shopping-cart')->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
	}

	public function process_gift_membership(Request $request){
		$payment_id = $request->get('paymentId');
		\Session::forget('paypal_payment_id');
 
		$payerId = \Input::get('PayerID');
		$token = \Input::get('token');
 
		if (empty($payerId) || empty($token)) {
			return redirect('shopping-cart/gift-membership/1')
				->with('msj-erroneo', 'Hubo un problema al intentar pagar con Paypal');
		}
 
		$payment = Payment::get($payment_id, $this->_api_context);
		
		$execution = new PaymentExecution();
		$execution->setPayerId(\Input::get('PayerID'));
 
		$result = $payment->execute($execution, $this->_api_context);
 
		if ($result->getState() == 'approved') {
			$membresia = DB::table('memberships')
							->where('id', '=', 1)
							->first();

			$compra = new Purchase();
			if (Auth::guest()){
				$compra->user_id = 0;
			}else{
				$compra->user_id = Auth::user()->id;
			}
			$compra->amount = $membresia->price;
			$compra->payment_method = 'Paypal';
			$compra->payment_id = $payment_id;
			$compra->date = date('Y-m-d');
			$compra->save();

			$detalle = new PurchaseDetail();
			$detalle->purchase_id = $compra->id;
			$detalle->membership_id = $membresia->id;
			$detalle->amount = $membresia->price;
			$detalle->gift_code = strtoupper('PT'.$compra->id.rand(6,100000));
			$detalle->save();

			$ultBalance = DB::table('transactions')
	                        ->select('balance')
	                        ->orderBy('created_at', 'DESC')
	                        ->first();

            $transaccion = new Trans();
            $transaccion->type = 'Ingreso';
            $transaccion->amount = $compra->amount;
            $transaccion->operation_id = $compra->id;
            $transaccion->date = date('Y-m-d');
            if (!is_null($ultBalance)){
            	$transaccion->balance = $ultBalance->balance + $compra->amount;
            }else{
            	$transaccion->balance = $compra->amount;
            }
           
            $transaccion->save();

            $data['correo'] = $payment->payer->payer_info->email;
            $data['membresia'] = $membresia->name;
            $data['codigo'] = $detalle->gift_code;
            $data['datosPago'] = $compra;
            $data['regalo'] = false;

            Mail::send('emails.giftMembership',['data' => $data], function($msg) use ($data){
                $msg->to($data['correo']);
                $msg->subject('Compra de Membresía de Regalo');
            });

			return redirect('shopping-cart/show-gift/'.$compra->id)->with('msj-exitoso', 'El pago ha sido completado con éxito. Le hemos enviado el soporte al correo de pago.');
		}

		return redirect('shopping-cart')->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
	}

	public function payment_event(Request $request){
		$payer = new Payer();
		$payer->setPaymentMethod('paypal');
 
		$items = array();
		$currency = 'USD';
        $trm = 3500;
        $monto = $request->amount;
        $tasa = $monto / $trm;
  
 		$item = new Item();
 		$item->setName('Pago de Evento en TransfórmatePro')
					->setCurrency($currency)
					->setDescription('Compra desde la Plataforma TransfórmatePro')
					->setQuantity(1)
					->setPrice($tasa);
		$items[] = $item;
 		$subtotal = $tasa;
		$total = $tasa;

		$item_list = new ItemList();
		$item_list->setItems($items);
 
		$details = new Details();
		$details->setSubtotal($subtotal);
 
		$amount = new Amount();
		$amount->setCurrency($currency)
			->setTotal($total)
			->setDetails($details);
 
		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setItemList($item_list)
			->setDescription('Pedido de prueba en mi Laravel App Store');
 	
 		$redirect_urls = new RedirectUrls();
		$redirect_urls->setReturnUrl(\URL::route('landing.events.process-paypal-checkout'))
				->setCancelUrl(\URL::route('landing.events.process-paypal-checkout'));
		
		$payment = new Payment();
		$payment->setIntent('Sale')
			->setPayer($payer)
			->setRedirectUrls($redirect_urls)
			->setTransactions(array($transaction));
 
		try {
			$payment->create($this->_api_context);
		} catch (\PayPal\Exception\PPConnectionException $ex) {
			if (\Config::get('app.debug')) {
				echo "Exception: " . $ex->getMessage() . PHP_EOL;
				$err_data = json_decode($ex->getData(), true);
				exit;
			} else {
				die('Ups! Algo salió mal');
			}
		}
 
		foreach($payment->getLinks() as $link) {
			if($link->getRel() == 'approval_url') {
				$redirect_url = $link->getHref();
				break;
			}
		}
 
		// add payment ID to session
		\Session::put('paypal_payment_id', $payment->getId());
		$request->session()->put('suscriptor_id', $request->suscriptor_id);
 
		if(isset($redirect_url)) {
			return \Redirect::away($redirect_url);
		}
 
		return \Redirect::route('cart-show')
			->with('message', 'Ups! Error desconocido.');
	}

	public function process_payment_event(Request $request){
		$notificacion = new NotificationController();
		$payment_id = $request->get('paymentId');
		\Session::forget('paypal_payment_id');
 
		$payerId = \Input::get('PayerID');
		$token = \Input::get('token');

		$suscriptor = $request->session()->get('suscriptor_id');
 
		if (empty($payerId) || empty($token)) {
			return redirect('t-events/payment/'.$suscriptor)
				->with('msj-erroneo', 'Hubo un problema al intentar pagar con Paypal');
		}
 
		$payment = Payment::get($payment_id, $this->_api_context);
		
		$execution = new PaymentExecution();
		$execution->setPayerId(\Input::get('PayerID'));
 
		$result = $payment->execute($execution, $this->_api_context);
 
		if ($result->getState() == 'approved') {
			$suscripcion =  EventSubscription::find($suscriptor);
			$suscripcion->payment_method = 'Paypal';
	        $suscripcion->payment_id = $payment_id;
	        $suscripcion->payment_amount = $suscripcion->event->price;
	        $suscripcion->payment_date = date('Y-m-d');
	        $suscripcion->status = 1;
	        $suscripcion->save();

	        $montoFinal = (($suscripcion->payment_amount * 94.5) / 100);
	        
	       	if ( (!is_null($suscripcion->instructor_code)) && (!is_null($suscripcion->partner_code)) ){
            		//**** Comisón al instructor por Código Aplicado ***//
            		$comision = new Commission();
        			$comision->user_id = $suscripcion->instructor_code;
                	$comision->amount = (($montoFinal * 60) / 100);
                	$comision->type = 'Código T-Mentor Aplicado en T-Event';
        			$comision->event_subscription_id = $suscripcion->id;
        			$comision->status = 0;
        			$comision->date = date('Y-m-d');
        			$comision->save();

			        $usuario = User::find($suscripcion->instructor_code);
			        $usuario->event_balance = $usuario->event_balance + $comision->amount;
			        $usuario->save();
    
        			$notificacion->store($suscripcion->instructor_code, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision->type.'</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision->id);

        			$comision2 = new Commission();
        			$comision2->user_id = $suscripcion->partner_code;
                	$comision2->amount = (($montoFinal * 10) / 100);
                	$comision2->type = 'Código T-Partner Aplicado en T-Event';
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
                	$comision->amount = (($montoFinal * 70) / 100);
                	$comision->type = 'Código T-Mentor Aplicado en T-Event';
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
			
			return redirect('t-events/payment-bill/'.$suscripcion->id)->with('msj-exitoso', 'El pago ha sido completado con éxito.');
		}

		return redirect('t-events/payment/'.$suscriptor)->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
	}
}