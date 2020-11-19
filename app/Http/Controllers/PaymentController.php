<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase; use App\Models\User; use App\Models\Product; use App\Models\PurchaseDetail;
use App\Models\Membership; use App\Models\Transaction; use App\Models\BankAccount; 
use App\Models\BankTransfer; use App\Models\PendingProduct; use App\Models\EfectyPayment;
use Auth; use DB; use Carbon\Carbon; use Mail; use MercadoPago;

class PaymentController extends Controller
{
    public function efecty_checkout(Request $request){
        \MercadoPago\SDK::setAccessToken("APP_USR-1902410051285318-043020-57263f6c7aa781de7dba01fd37458c14-515837620");

        try{
            $payment = new MercadoPago\Payment();
            $payment->transaction_amount = $request->amount;
            $payment->description = "Compra en TransfórmatePro";
            $payment->payment_method_id = "efecty";
            $payment->payer = array(
                "email" => Auth::user()->email
            );
            $payment->save();

            if ($payment->status == 'pending'){
                $efectivo = new EfectyPayment($request->all());
                $efectivo->user_id = Auth::user()->id;
                if (!is_null(Auth::user()->membership_id)){
                    $efectivo->membership_discount = 1;
                }
                $efectivo->payment_id = $payment->id;
                $efectivo->payment_url = $payment->transaction_details->external_resource_url;
                $efectivo->save();

                $items = Product::where('user_id', '=', Auth::user()->id)->get();

                $cuponAbierto = DB::table('applied_coupons')
                                    ->select('id', 'coupon_id')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('status', '=', 1)
                                    ->first();

                if (!is_null($cuponAbierto)){
                    $cuponAplicado = DB::table('coupons')
                                        ->where('id', '=', $cuponAbierto->coupon_id)
                                        ->first();
                }else{
                    $cuponAplicado = NULL;
                }

                foreach ($items as $item){
                    $productoPendiente = new PendingProduct();
                    $productoPendiente->efecty_payment_id = $efectivo->id;
                    $productoPendiente->instructor_code = $item->instructor_code;
                    $productoPendiente->partner_code = $item->partner_code;
                    $productoPendiente->gift = $item->gift;
                    
                    if (!is_null($item->course_id)){
                        $productoPendiente->course_id = $item->course_id;
                        $productoPendiente->original_amount = $item->course->price;
                        if (!is_null($cuponAplicado)){
                            if (is_null(Auth::user()->membership_id)){
                                $productoPendiente->amount = ( $item->course->price - (($item->course->price * $cuponAplicado->discount) / 100));
                            }else{
                                $precioConMembresia = (($item->course->price * 20) / 100);
                                $productoPendiente->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                            }
                        }else{
                            if (is_null(Auth::user()->membership_id)){
                                $productoPendiente->amount = $item->course->price;
                            }else{
                                $productoPendiente->amount = (($item->course->price * 20) / 100);
                            }
                        }
                    }else if (!is_null($item->podcast_id)){
                        $productoPendiente->podcast_id = $item->podcast_id;
                        $productoPendiente->original_amount = $item->podcast->price;
                        if (!is_null($cuponAplicado)){
                            if (is_null(Auth::user()->membership_id)){
                                $productoPendiente->amount = ( $item->podcast->price - (($item->podcast->price * $cuponAplicado->discount) / 100));
                            }else{
                                $precioConMembresia = (($item->podcast->price * 50) / 100);
                                $productoPendiente->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                            }
                        }else{
                            if (is_null(Auth::user()->membership_id)){
                                $productoPendiente->amount = $item->podcast->price;
                            }else{
                                $productoPendiente->amount = (($item->podcast->price * 50) / 100);
                            }
                        }   
                    }else if (!is_null($item->membership_id)){
                        $productoPendiente->membership_id = $item->membership_id;
                        $productoPendiente->original_amount = $item->membership->price;
                        if (!is_null($cuponAplicado)){
                            $productoPendiente->amount = ( $item->membership->price - (($item->membership->price * $cuponAplicado->discount) / 100));
                        }else{
                            $productoPendiente->amount = $item->membership->price;
                        }
                    }else if (!is_null($item->product_id)){
                        $productoPendiente->product_id = $item->product_id;
                        $productoPendiente->original_amount = $item->market_product->price;
                        if (!is_null($cuponAplicado)){
                            $productoPendiente->amount = ( $item->market_product->price - (($item->market_product->price * $cuponAplicado->discount) / 100));
                        }else{
                            $productoPendiente->amount = $item->market_product->price;
                        }
                    }

                    if ($request->instructor_code_discount == 1){
                        $productoPendiente->amount = (($productoPendiente->amount * 90) / 100);
                    }
                    $productoPendiente->save();
                
                    $item->delete();
                }
                                
                if (!is_null($cuponAbierto)){
                    DB::table('applied_coupons')
                        ->where('id', '=', $cuponAbierto->id)
                        ->update(['status' => 0,
                                  'closed_at' => date('Y-m-d')]);
                }

                return redirect('shopping-cart/bill/efecty/'.$efectivo->id)->with('msj-exitoso', 'La transacción ha sido creada con éxito. Debe completar el pago para disfrutar de su compra. A continuación le dejamos el link con más información.');
            }else{
                return redirect('shopping-cart/checkout')->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
            }
        }catch (\Exception $e) {
            return redirect('shopping-cart/checkout')->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
        }
    }

    public function mercado_pago_checkout(Request $request){
        \MercadoPago\SDK::setAccessToken("APP_USR-1902410051285318-043020-57263f6c7aa781de7dba01fd37458c14-515837620");

        try{
            $payment = new \MercadoPago\Payment();
            $payment->transaction_amount = $request->amount;
            $payment->token = $request->token;
            $payment->description = "Compra en TransfórmatePro";
            $payment->installments = 1;
            $payment->payment_method_id = $request->paymentMethodId;
            if (isset($request->gift)){
                $payment->payer = $request->email;
            }else{
                $payment->payer = array("email" => Auth::user()->email);
            }
            $payment->save();

            if ($payment->status == 'approved'){
                if (isset($request->gift)){
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
                    $compra->payment_method = 'MercadoPago';
                    $compra->payment_id = $payment->id;
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

                    $transaccion = new Transaction();
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

                    $data['correo'] = $request->email;
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

                $carrito = new ShoppingCartController();
                $id_compra = $carrito->process_cart($request->amount, $request->original_amount, 'MercadoPago', $payment->id, 1);

                return redirect('shopping-cart/bill/mp/'.$id_compra)->with('msj-exitoso', 'El pago ha sido completado con éxito.');
            }else if ($payment->status == 'in_process'){
                $carrito = new ShoppingCartController();
                $id_compra = $carrito->process_cart($request->amount, $request->original_amount, 'MercadoPago', $payment->id, 0);

                return redirect('shopping-cart/checkout')->with('msj-exitoso', 'Su compra está siendo procesada. En unas horas le avisaremos a su correo el resultado de la misma.');
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
                return redirect('shopping-cart/checkout')->with('msj-erroneo', 'Su compra no ha podido ser procesada. '.$msj);
            }
        }catch (\Exception $e) {
            return redirect('shopping-cart')->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
        }
    }

    public function free_checkout(){
        $carrito = new ShoppingCartController();
        $carrito->process_cart(0, 0, 'Free', 'N/A', 1);
        
        if (Auth::user()->role_id == 1){
            return redirect('students/my-content')->with('msj-exitoso', 'El contenido ha sido agregado a su cuenta con éxito.');
        }else{
            return redirect('instructors')->with('msj-exitoso', 'El pago ha sido completado con éxito.');
        }
    }

    public function bank_transfer_checkout(Request $request){
        \MercadoPago\SDK::setAccessToken("APP_USR-1902410051285318-043020-57263f6c7aa781de7dba01fd37458c14-515837620");

        $items = Product::where('user_id', '=', Auth::user()->id)->get();
        if ($items->count() > 0){
            $transferencia = new BankTransfer($request->all());
            $transferencia->user_id = Auth::user()->id;
            if (!is_null(Auth::user()->membership_id)){
                $transferencia->membership_discount = 1;
            }
            $transferencia->save();

            $payment = new MercadoPago\Payment();
            $payment->transaction_amount = $request->amount;
            $payment->description = "Compra en TransfórmatePro";
            $payment->payer = array (
                    "email" => Auth::user()->email,
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
            $payment->callback_url = "https://www.transformatepro.com/students/shopping-cart/bill/transfer/".$transferencia->id;
            $payment->payment_method_id = "pse";
            $payment->save();

            if ($payment->status == 'pending'){
                $transferencia->payment_url = $payment->transaction_details->external_resource_url;
                $transferencia->payment_id = $payment->id;
                $transferencia->save();

                $items = Product::where('user_id', '=', Auth::user()->id)->get();

                $cuponAbierto = DB::table('applied_coupons')
                                    ->select('id', 'coupon_id')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('status', '=', 1)
                                    ->first();

                if (!is_null($cuponAbierto)){
                    $cuponAplicado = DB::table('coupons')
                                        ->where('id', '=', $cuponAbierto->coupon_id)
                                        ->first();               
                }else{
                    $cuponAplicado = NULL;
                }

                foreach ($items as $item){
                    $productoPendiente = new PendingProduct();
                    $productoPendiente->bank_transfer_id = $transferencia->id;
                    $productoPendiente->instructor_code = $item->instructor_code;
                    $productoPendiente->partner_code = $item->partner_code;
                    $productoPendiente->gift = $item->gift;
                    
                    if (!is_null($item->course_id)){
                        $productoPendiente->course_id = $item->course_id;
                        $productoPendiente->original_amount = $item->course->price;
                        if (!is_null($cuponAplicado)){
                            if (is_null(Auth::user()->membership_id)){
                                $productoPendiente->amount = ( $item->course->price - (($item->course->price * $cuponAplicado->discount) / 100));
                            }else{
                                $precioConMembresia = (($item->course->price * 20) / 100);
                                $productoPendiente->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                            }
                        }else{
                            if (is_null(Auth::user()->membership_id)){
                                $productoPendiente->amount = $item->course->price;
                            }else{
                                $productoPendiente->amount = (($item->course->price * 20) / 100);
                            }
                        }
                    }else if (!is_null($item->podcast_id)){
                        $productoPendiente->podcast_id = $item->podcast_id;
                        $productoPendiente->original_amount = $item->podcast->price;
                        if (!is_null($cuponAplicado)){
                            if (is_null(Auth::user()->membership_id)){
                                $productoPendiente->amount = ( $item->podcast->price - (($item->podcast->price * $cuponAplicado->discount) / 100));
                            }else{
                                $precioConMembresia = (($item->podcast->price * 50) / 100);
                                $productoPendiente->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                            }
                        }else{
                            if (is_null(Auth::user()->membership_id)){
                                $productoPendiente->amount = $item->podcast->price;
                            }else{
                                $productoPendiente->amount = (($item->podcast->price * 50) / 100);
                            }
                        }   
                    }else if (!is_null($item->membership_id)){
                        $productoPendiente->membership_id = $item->membership_id;
                        $productoPendiente->original_amount = $item->membership->price;
                        if (!is_null($cuponAplicado)){
                            $productoPendiente->amount = ( $item->membership->price - (($item->membership->price * $cuponAplicado->discount) / 100));
                        }else{
                            $productoPendiente->amount = $item->membership->price;
                        }
                    }else if (!is_null($item->product_id)){
                        $productoPendiente->product_id = $item->product_id;
                        $productoPendiente->original_amount = $item->market_product->price;
                        if (!is_null($cuponAplicado)){
                            $productoPendiente->amount = ( $item->market_product->price - (($item->market_product->price * $cuponAplicado->discount) / 100));
                        }else{
                            $productoPendiente->amount = $item->market_product->price;
                        }
                    }

                    if ($request->instructor_code_discount == 1){
                        $productoPendiente->amount = (($productoPendiente->amount * 90) / 100);
                    }
                    $productoPendiente->save();
                
                    $item->delete();
                }
                                    
                if (!is_null($cuponAbierto)){
                    DB::table('applied_coupons')
                        ->where('id', '=', $cuponAbierto->id)
                        ->update(['status' => 0,
                                'closed_at' => date('Y-m-d')]);
                }

                return redirect('shopping-cart/bill/transfer/'.$transferencia->id)->with('msj-exitoso', 'La transacción ha sido creada con éxito. Debe completar el pago en el link que aparece a continuación para disfrutar de su compra.');
            }else{
                BankTransfer::destroy($transferencia->id);

                return redirect('shopping-cart/checkout')->with('msj-erroneo', 'Su compra no ha podido ser procesada. Por favor, intente más tarde.');
            }
        }else{
            return redirect('shopping-cart')->with('msj-erroneo', 'Usted no posee ningún contenido al carrito de compras. Por favor, seleccione algún contenido para continuar o verifique si tiene un proceso de pago pendiente.');
        }
    }

    //**** Admin / Finanzas / Ingresos ****/
    public function index(){
        $ingresos = Purchase::orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.finances.earnings')->with(compact('ingresos'));
    }

     //**** Admin / Finanzas / Ingresos / Ver Detalles ****/
    public function show_earning($id){
        $ingreso = Purchase::find($id);

        $productos = NULL;
        if (is_null($ingreso->membership_id)){
            $productos = PurchaseDetail::where('purchase_id', '=', $id)
                            ->get();
        }
        return view('admins.finances.showEarning')->with(compact('ingreso', 'productos'));
    }

    //*** Factura que se muestra inmediatamente después de realizado un pago en el checkout***//
    public function bill($payment_type, $payment_id){
        $cuentasDisponibles = BankAccount::where('status', '=', 1)
                                    ->orderBy('bank', 'ASC')
                                    ->get();

        if ($payment_type == 'transfer'){
            $datosPago = BankTransfer::where('id', '=', $payment_id)
                            ->withCount('pending_products')
                            ->first();

            $datosPago->payment_method = 'Transferencia Bancaria';
            $datosPago->payment_id = 'TRANSF#'.$datosPago->id;
            $cantItems = $datosPago->pending_products_count;
            $items = $datosPago->pending_products;
        }else if ($payment_type == 'efecty'){
            $datosPago = EfectyPayment::where('id', '=', $payment_id)
                            ->withCount('pending_products')
                            ->first();

            $datosPago->payment_method = 'Efectivo';
            $datosPago->payment_id = 'EFECTY#'.$datosPago->id;
            $cantItems = $datosPago->pending_products_count;
            $items = $datosPago->pending_products;
        }else{
            $datosPago = Purchase::where('id', '=', $payment_id)
                            ->withCount('details')
                            ->first();

            $cantItems = $datosPago->details_count;
            $items = $datosPago->details;
        }

        return view('students.shoppingCart.bill')->with(compact('datosPago', 'cantItems', 'items', 'cuentasDisponibles', 'payment_type'));
    }

    //*** Factura que se muestra a través del historial ***//
    public function show_bill($payment_id){
        $datosPago = Purchase::where('id', '=', $payment_id)
                        ->withCount('details')
                        ->first();

        $cantItems = $datosPago->details_count;
        $items = $datosPago->details;

        $payment_type = 0;
        
        return view('students.shoppingCart.bill')->with(compact('datosPago', 'cantItems', 'items', 'payment_type' ));
    }

    //**** Estudiantes / Historial de Compras ****/
    public function purchases_record(){
        $compras = Purchase::where('user_id', '=', Auth::user()->id)
                        ->withCount('details')
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('students.purchases.index')->with(compact('compras'));
    }
}
