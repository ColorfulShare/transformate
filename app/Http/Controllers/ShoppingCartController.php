<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; use App\Models\Course; use App\Models\Certification; use App\Models\Podcast;
use App\Models\Purchase; use App\Models\PurchaseDetail; use App\Models\Transaction; use App\Models\Membership;
use App\Models\MarketProduct; use App\Models\BankAccount; use App\Models\PendingProduct; use App\Models\Gift;
use Auth; use DB; use Mail; use Carbon\Carbon;

class ShoppingCartController extends Controller
{
    //**** Invitado - Estudiante - Instructor / Carrito de Compra ****//
    public function index(Request $request){
        if (Auth::guest()){
            $cantItems = 0;
            $totalItems = 0;

            $itemsA = array();

            if ($request->session()->has('cart')) {
                $itemsA = $request->session()->get('cart');
            }

            $items = collect();
            foreach ($itemsA as $item) {
                $tipoItem = explode("-", $item);
                if ($tipoItem[0] == 'curso'){
                    $datosItem = Course::where('id', '=', $tipoItem[1])->first();
                }else if ($tipoItem[0] == 'certificacion'){
                    $datosItem = Certification::where('id', '=', $tipoItem[1])->first();
                }else if ($tipoItem[0] == 'podcast'){
                    $datosItem = Podcast::where('id', '=', $tipoItem[1])->first();
                }else if ($tipoItem[0] == 'membresia'){
                    $datosItem = Membership::where('id', '=', $tipoItem[1])->first();
                }else{
                    $datosItem = MarketProduct::where('id', '=', $tipoItem[1])->first();
                }
                $datosItem->type = $tipoItem[0];
                if (isset($tipoItem[2])){
                    $datosItem->instructor_code = $tipoItem[2];
                }else{
                    $datosItem->instructor_code = 0;
                }
                $totalItems += $datosItem->price;
                $cantItems++;

                $items->push($datosItem);
            }

            return view('landing.shoppingCart')->with(compact('items', 'cantItems', 'totalItems'));
        }else{
            $items = Product::where('user_id', '=', Auth::user()->id)->orderBy('date', 'DESC')->get();
            
            $cuponAbierto = DB::table('applied_coupons')
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('status', '=', 1)
                                ->first();

            if (!is_null($cuponAbierto)){
                $cuponAplicado = DB::table('coupons')
                                    ->where('id', '=', $cuponAbierto->coupon_id)
                                    ->first();
            }else{
                $cuponAplicado = null;
            }

            $cantItems = 0;
            $cantItemsInstructorCode = 0;
            $cantItemsPartnerCode = 0;
            $cantGifts = 0;
            $totalAnterior = 0;
            $totalItems = 0;
            $categoriasCursos = [];
            $cursos = [];
            $categoriasCertificaciones = [];
            $certificaciones = [];
            $categoriasPodcasts = [];
            $podcasts = [];
            $sugerencias = collect();

            foreach ($items as $key => $item){
                $cantItems++;
                if ($item->instructor_code == 1){
                    $cantItemsInstructorCode++;
                }
                if (!is_null($item->partner_code)){
                    $cantItemsPartnerCode++;
                }
                if ($item->gift == 1){
                    $cantGifts++;
                }

                if (!is_null($item->course_id)){
                    $totalAnterior += $item->course->price;
                    if (!is_null($cuponAplicado)){
                        if (is_null(Auth::user()->membership_id)){
                            $item->new_price = ( $item->course->price - (($item->course->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->course->price * 70) / 100);
                            $item->new_price = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                        
                        $totalItems += $item->new_price;
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $totalItems += $item->course->price;
                        }else{
                            $item->new_price = (($item->course->price * 70) / 100);
                            $totalItems += $item->new_price;
                        }
                    }
                    $categoriasCursos[$key] = $item->course->category_id;
                    $cursos[$key] = $item->course_id;
                }else if (!is_null($item->certification_id)){
                    $totalAnterior += $item->certification->price;
                    if (!is_null($cuponAplicado)){
                        if (is_null(Auth::user()->membership_id)){
                            $item->new_price = ( $item->certification->price - (($item->certification->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->certification->price * 70) / 100);
                            $item->new_price = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                        $totalItems += $item->new_price;
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $totalItems += $item->certification->price;
                        }else{
                            $item->new_price = (($item->certification->price * 70) / 100);
                            $totalItems += $item->new_price;
                        }
                    }
                    $categoriasCertificaciones[$key] = $item->certification->category_id;
                    $certificaciones[$key] = $item->certification_id;
                }else if (!is_null($item->podcast_id)){
                    $totalAnterior += $item->podcast->price;
                    if (!is_null($cuponAplicado)){
                        if (is_null(Auth::user()->membership_id)){
                            $item->new_price = ( $item->podcast->price - (($item->podcast->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->podcast->price * 70) / 100);
                            $item->new_price = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                        $totalItems += $item->new_price;
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $totalItems += $item->podcast->price;
                        }else{
                            $item->new_price = (($item->podcast->price * 70) / 100);
                            $totalItems += $item->new_price;
                        }
                    }
                    $categoriasPodcasts[$key] = $item->podcast->category_id;
                    $podcasts[$key] = $item->podcast_id;
                }else if (!is_null($item->membership_id)){
                    $totalAnterior += $item->membership->price;
                    if (!is_null($cuponAplicado)){
                        $item->new_price = ($item->membership->price - (($item->membership->price * $cuponAplicado->discount) / 100));
                        $totalItems += $item->new_price;
                    }else{
                        $totalItems += $item->membership->price;
                    }
                }else if (!is_null($item->product_id)){
                    if (!is_null($cuponAplicado)){
                        $totalAnterior += $item->market_product->price;
                        $item->new_price = ($item->market_product->price - (($item->market_product->price * $cuponAplicado->discount) / 100));
                        $totalItems += $item->new_price;
                    }else{
                        $totalItems += $item->market_product->price;
                    }
                }         
            }

            if ( ($cantItemsInstructorCode > 0) || ($cantItemsPartnerCode > 0) ){
                $totalItems = (($totalItems * 90) / 100);
            }
            
            return view('students.shoppingCart.index')->with(compact('items', 'cantItems', 'totalItems', 'totalAnterior', 'cuponAplicado', 'cantItemsInstructorCode', 'cantItemsPartnerCode', 'cantGifts'));
        }
    }

    //**** Invitado - Estudiante - Instructor / Agregar item al carrito de compra ****//
    public function store(Request $request, $id, $tipo){
        if (Auth::guest()){
            $contenido = $tipo."-".$id."-0"; 
            $cont = 0;

            if ($request->session()->has('cart')) {
                $items = $request->session()->pull('cart');
                
                foreach ($items as $item) {
                    $datosItem = explode("-", $item);
                    if ( ($datosItem[0] == $tipo) && ($datosItem[1] == $id) ){
                        $cont++;
                    }else{
                        $request->session()->push('cart', $item);
                    }        
                }

                $request->session()->push('cart', $contenido);

                if ($cont > 0){
                    return redirect('shopping-cart')->with('msj-informativo', 'El item ya se encuentra en su carrito de compras.');
                }
            }else{
                $request->session()->push('cart', $contenido);
            }
            
            return redirect('shopping-cart')->with('msj-exitoso', 'El item ha sido agregado a su carrito de compras con éxito.');
        }else{
            if ($tipo == 'curso'){
                $itemAgregado = DB::table('shopping_cart')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('course_id', '=', $id)
                                    ->count();
            }else if ($tipo == 'certificacion'){
                $itemAgregado = DB::table('shopping_cart')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('certification_id', '=', $id)
                                    ->count();
            }else if ($tipo == 'podcast'){
                $itemAgregado = DB::table('shopping_cart')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('podcast_id', '=', $id)
                                    ->count();
            }else if ($tipo == 'membresia'){
                $itemAgregado = DB::table('shopping_cart')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('membership_id', '=', $id)
                                    ->count();
            }else if ($tipo == 'producto'){
                $itemAgregado = DB::table('shopping_cart')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('product_id', '=', $id)
                                    ->count();
            }

            if ($itemAgregado == 0){
                $item = new Product();
                $item->user_id = Auth::user()->id;
                $item->date = date('Y-m-d');
                if ($tipo == 'curso'){
                    $item->course_id = $id;
                }else if ($tipo == 'certificacion'){
                    $item->certification_id = $id;
                }else if ($tipo == 'podcast'){
                    $item->podcast_id = $id;
                }else if ($tipo == 'membresia'){
                    $item->membership_id = $id;
                }else if ($tipo == 'producto'){
                    $item->product_id = $id;
                }
                $item->save();

                return redirect('shopping-cart')->with('msj-exitoso', 'El item ha sido agregado a su carrito de compras con éxito.');
            }else{
                return redirect('shopping-cart')->with('msj-informativo', 'El item ya se encuentra en su carrito de compras.');
            }
        }
    }

    //**** Invitado - Estudiante - Instructor / Eliminar item del carrito de compra ****//
    public function delete(Request $request, $id){
        if (Auth::guest()){
            $items = $request->session()->pull('cart');

            foreach ($items as $item) {
                if ($item != $id){
                    $request->session()->push('cart', $item);
                }               
            }

            return redirect('shopping-cart')->with('msj-exitoso', 'El item ha sido eliminado de su carrito de compras con éxito.');
        }else{
            Product::destroy($id);

            return redirect('shopping-cart')->with('msj-exitoso', 'El item ha sido eliminado de su carrito de compras con éxito.');
        }
    }

    public function gift_membership($membresia){
        $membresia = DB::table('memberships')
                        ->where('id', '=', $membresia)
                        ->first();

        return view('landing.giftMembership')->with(compact('membresia'));
    }

    public function show_gift($compra){
        $datosPago = Purchase::where('id', '=', $compra)
                        ->withCount('details')
                        ->first();

        $cantItems = $datosPago->details_count;
        $items = $datosPago->details;

        return view('landing.showGift')->with(compact('datosPago', 'cantItems', 'items'));
    }

    public function send_gift_membership(Request $request){
        $datosPago = Purchase::where('id', '=', $request->purchase_id)
                        ->withCount('details')
                        ->first();

        foreach ($datosPago->details as $detalle){
            $data['membresia'] = $detalle->membership->name;
            $data['codigo'] = $detalle->gift_code;
        }

        $data['correo'] = $request->email;
        $data['regalo'] = true;

        Mail::send('emails.giftMembership',['data' => $data], function($msg) use ($data){
            $msg->to($data['correo']);
            $msg->subject('Compra de Membresía de Regalo');
        });

        return redirect('shopping-cart/show-gift/'.$request->purchase_id)->with('msj-exitoso', 'El correo ha sido enviado con éxito.');
    }

    //**** Agregar Código de Instructor a un Item
    public function add_code(Request $request){
        if (Auth::guest()){
            $datosItemRequest = explode("-", $request->item_id);
            if ($datosItemRequest[0] == 'curso'){
                $seleccion = Course::find($datosItemRequest[1]);
            }else if ($datosItemRequest[0] == 'certificacion'){
                $seleccion = Certification::find($datosItemRequest[1]);
            }else if ($datosItemRequest[0] == 'podcast'){
                $seleccion = Podcast::find($datosItemRequest[1]);
            }else if ($datosItemRequest[0] == 'producto'){
                $seleccion = MarketProduct::find($datosItemRequest[1]);
            }

            if ($seleccion->user->afiliate_code == strtolower($request->codigo)){
                $items = $request->session()->pull('cart');

                foreach ($items as $item) {
                    $datosItem = explode("-", $item);
                    if ( ($datosItem[0] == $datosItemRequest[0]) && ($datosItem[1] == $datosItemRequest[1]) ){
                        $contenido = $datosItem[0]."-".$datosItem[1]."-1";
                        $request->session()->push('cart', $contenido);  
                    }else{
                        $request->session()->push('cart', $item);
                    }       
                }
            }else{
                return redirect('shopping-cart')->with('msj-erroneo', 'El código de instructor que ingresó es incorrecto.');
            }

            return redirect('shopping-cart')->with('msj-exitoso', 'El código de instructor ha sido añadido a su item con éxito.');
        }else{
            $item = Product::find($request->item_id);

            if ($request->type_code == 'mentor'){
                if (!is_null($item->course_id)){
                    $codigoInstructor = $item->course->user->afiliate_code;
                }else if (!is_null($item->podcast_id)){
                    $codigoInstructor = $item->podcast->user->afiliate_code;
                }else if(!is_null($item->product_id)){
                    $codigoInstructor = $item->market_product->user->afiliate_code;
                }

                if ($codigoInstructor == strtolower($request->codigo)){
                    $item->instructor_code = 1;
                    $item->save();

                    return redirect('shopping-cart')->with('msj-exitoso', 'El código T-Mentor ha sido añadido a su item con éxito.');
                }else{
                    return redirect('shopping-cart')->with('msj-erroneo', 'El código T-Mentor que ingresó es incorrecto.');
                }
            }else{
                $partner = DB::table('users')
                            ->select('id')
                            ->where('partner_code', '=', $request->codigo)
                            ->first();

                if (!is_null($partner)){
                    $item->partner_code = $partner->id;
                    $item->save();

                    return redirect('shopping-cart')->with('msj-exitoso', 'El código T-Partner ha sido añadido a su item con éxito.');
                }else{
                    return redirect('shopping-cart')->with('msj-erroneo', 'El código T-Partner que ingresó es incorrecto.');
                }
            }
        }
    }
    
    // Marcar Contenido para Regalar
    public function add_gift(Request $request){
        $item = Product::find($request->item_id);
        $item->gift = 1;
        $item->save();

        return redirect('shopping-cart')->with('msj-exitoso', 'El T-Gift ha sido marcado con éxito.');
    }

    public function checkout(Request $request){
        if (Auth::guest()){
            $cantItems = 0;
            $totalItems = 0;
            
            $items = array();

            if ($request->session()->has('cart')) {
                $items = $request->session()->get('cart');
            }

            foreach ($items as $item) {
                $cantItems++;
                $tipoItem = explode("-", $item);
                if ($tipoItem[0] == 'curso'){
                    $precioItem = DB::table('courses')
                                    ->select('price')
                                    ->where('id', '=', $tipoItem[1])
                                    ->first();
                }else if ($tipoItem[0] == 'podcast'){
                    $precioItem = DB::table('podcasts')
                                    ->select('price')
                                    ->where('id', '=', $tipoItem[1])
                                    ->first();
                }else if ($tipoItem[0] == 'certificacion'){
                    $precioItem = DB::table('certifications')
                                    ->select('price')
                                    ->where('id', '=', $tipoItem[1])
                                    ->first();
                }else if ($tipoItem[0] == 'membresia'){
                    $precioItem = DB::table('memberships')
                                    ->select('price')
                                    ->where('id', '=', $tipoItem[1])
                                    ->first();
                }else{
                    $precioItem = DB::table('products')
                                    ->select('price')
                                    ->where('id', '=', $tipoItem[1])
                                    ->first();
                }

                $totalItems += $precioItem->price;
            }

            return view('landing.checkout')->with(compact('cantItems', 'totalItems'));
        }else{
            $items = Product::where('user_id', '=', Auth::user()->id)->orderBy('date', 'DESC')->get();

            $cuponAbierto = DB::table('applied_coupons')
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('status', '=', 1)
                                ->first();

            if (!is_null($cuponAbierto)){
                $cuponAplicado = DB::table('coupons')
                                    ->where('id', '=', $cuponAbierto->coupon_id)
                                    ->first();
            }else{
                $cuponAplicado = null;
            }

            $cantItems = 0;
            $cantItemsInstructorCode = 0;
            $cantItemsPartnerCode = 0;
            $totalItems = 0;
            $totalAnterior = 0;
            foreach ($items as $item){
                $cantItems++;
                if ($item->instructor_code == 1){
                    $cantItemsInstructorCode++;
                }
                if (!is_null($item->partner_code)){
                    $cantItemsPartnerCode++;
                }
                if (!is_null($item->course_id)){ 
                    $totalAnterior += $item->course->price;
                    if (!is_null($cuponAplicado)){
                        if (is_null(Auth::user()->membership_id)){
                            $item->new_price = ( $item->course->price - (($item->course->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->course->price * 70) / 100);
                            $item->new_price = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                        
                        $totalItems += $item->new_price;
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $totalItems += $item->course->price;
                        }else{
                            $precioConMembresia = (($item->course->price * 70) / 100);
                            $totalItems += $precioConMembresia;
                        }
                    }
                }else if (!is_null($item->podcast_id)){
                    $totalAnterior += $item->podcast->price;
                    if (!is_null($cuponAplicado)){
                        if (is_null(Auth::user()->membership_id)){
                            $item->new_price = ( $item->podcast->price - (($item->podcast->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->podcast->price * 70) / 100);
                            $item->new_price = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                        
                        $totalItems += $item->new_price;
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $totalItems += $item->podcast->price;
                        }else{
                            $precioConMembresia = (($item->podcast->price * 70) / 100);
                            $totalItems += $precioConMembresia;
                        }
                    }
                }else if (!is_null($item->certification_id)){
                    $totalAnterior += $item->certification->price;
                    if (!is_null($cuponAplicado)){
                        if (is_null(Auth::user()->membership_id)){
                            $item->new_price = ( $item->certification->price - (($item->certification->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->certification->price * 70) / 100);
                            $item->new_price = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                        
                        $totalItems += $item->new_price;
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $totalItems += $item->certification->price;
                        }else{
                            $precioConMembresia = (($item->certification->price * 70) / 100);
                            $totalItems += $precioConMembresia;
                        }
                    }
                }else if (!is_null($item->membership_id)){
                    $totalAnterior += $item->membership->price;
                    if (!is_null($cuponAplicado)){
                        $item->new_price = ($item->membership->price - (($item->membership->price * $cuponAplicado->discount) / 100));
                        $totalItems += $item->new_price;
                    }else{
                        $totalItems += $item->membership->price;
                    }
                }else if (!is_null($item->product_id)){
                    $totalAnterior += $item->market_product->price;
                    if (!is_null($cuponAplicado)){
                        $item->new_price = ($item->market_product->price - (($item->market_product->price * $cuponAplicado->discount) / 100));
                        $totalItems += $item->new_price;
                    }else{
                        $totalItems += $item->market_product->price;
                    }
                }
            }

            if ( ($cantItemsInstructorCode > 0) || ($cantItemsPartnerCode > 0) ){
                $totalItems = (($totalItems * 90) / 100);
            }

            /*\MercadoPago\SDK::setAccessToken("APP_USR-1902410051285318-043020-57263f6c7aa781de7dba01fd37458c14-515837620");
            $payment_methods = \MercadoPago\SDK::get("/v1/payment_methods");
            $bancosDisponibles = $payment_methods['body'][9]['financial_institutions'];*/

            return view('students.shoppingCart.checkout')->with(compact('cantItems', 'totalItems', 'totalAnterior'));
        }
    }

    //*** Procesa los items en el carrito una vez verificada la compra
    public function process_cart($amount, $original_amount, $payment_method, $payment_id, $status){
        $comisiones = new CommissionController();
        $notificacion = new NotificationController();

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
        }

        $compra = new Purchase();
        $compra->user_id = Auth::user()->id;
        $compra->original_amount = $original_amount;
        $compra->amount = $amount;
        $compra->payment_method = $payment_method;
        $compra->payment_id = $payment_id;
        if (!is_null($cuponAbierto)){
            $compra->coupon_id = $cuponAplicado->id;
        }
        if (!is_null(Auth::user()->membership_id)){
            $compra->membership_discount = 1;
        }
        //$compra->instructor_code_discount = $descuentoCodigo;
        $compra->date = date('Y-m-d');
        $compra->status = $status;
        $compra->save();

        if ($status == 1){
            //Pago Aprobado
            foreach ($items as $item){
                $detalle = new PurchaseDetail();
                $detalle->purchase_id = $compra->id;
                $detalle->instructor_code = $item->instructor_code;
                $detalle->partner_code = $item->partner_code;
                    
                if (!is_null($item->course_id)){
                    $detalle->course_id = $item->course_id;
                    $detalle->original_amount = $item->course->price;
                    if (!is_null($cuponAbierto)){
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = ( $item->course->price - (($item->course->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->course->price * 70) / 100);
                            $detalle->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = $item->course->price;
                        }else{
                            $detalle->amount = (($item->course->price * 70) / 100);
                        }
                    }

                    if ( ($item->instructor_code == 1) || (!is_null($item->partner_code)) ){
                        $detalle->amount = (($detalle->amount * 90) / 100);
                    }

                    $detalle->save();

                    if ($item->gift == 0){
                        Auth::user()->courses_students()->attach($item->course_id, ['start_date' => date('Y-m-d')]);
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = Auth::user()->id;
                        $regalo->course_id = $item->course_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
                    }
                    
                    if ($item->course->price > 0){
                        $comisiones->store($item->course_id, 'curso', $item->instructor_code, $item->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->course->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Course <b>'.$item->course->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-courses/purchases-record/'.$item->course->slug.'/'.$item->course_id);
                }else if (!is_null($item->certification_id)){
                    $detalle->certification_id = $item->certification_id;
                    $detalle->original_amount = $item->certification->price;
                    if (!is_null($cuponAbierto)){
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = ( $item->certification->price - (($item->certification->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->certification->price * 70) / 100);
                            $detalle->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = $item->certification->price;
                        }else{
                            $detalle->amount = (($item->certification->price * 70) / 100);
                        }
                    }

                    if ( ($item->instructor_code == 1) || (!is_null($item->partner_code)) ){
                        $detalle->amount = (($detalle->amount * 90) / 100);
                    }

                    $detalle->save();

                    if ($item->gift == 0){
                        Auth::user()->certifications_students()->attach($item->course_id, ['start_date' => date('Y-m-d')]);
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = Auth::user()->id;
                        $regalo->certification_id = $item->certification_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
                    }
                    
                    if ($item->certification->price > 0){
                        $comisiones->store($item->certification_id, 'certificacion', $item->instructor_code, $item->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->certification->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Mentoring <b>'.$item->certification->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-mentorings/purchases-record/'.$item->certification->slug.'/'.$item->certification_id);
                }else if (!is_null($item->podcast_id)){
                    $detalle->podcast_id = $item->podcast_id;
                    $detalle->original_amount = $item->podcast->price;
                    if (!is_null($cuponAbierto)){
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = ( $item->podcast->price - (($item->podcast->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->podcast->price * 70) / 100);
                            $detalle->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = $item->podcast->price;
                        }else{
                            $detalle->amount = (($item->podcast->price * 70) / 100);
                        }
                    }

                    if ( ($item->instructor_code == 1) || (!is_null($item->partner_code)) ){
                        $detalle->amount = (($detalle->amount * 90) / 100);
                    }
                    
                    $detalle->save();

                    if ($item->gift == 0){
                        Auth::user()->podcasts_students()->attach($item->podcast_id);
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = Auth::user()->id;
                        $regalo->podcast_id = $item->podcast_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
                    }

                    if ($item->podcast->price > 0){
                        $comisiones->store($item->podcast_id, 'podcast', $item->instructor_code, $item->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->podcast->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Book <b>'.$item->podcast->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-books/purchases-record/'.$item->podcast->slug.'/'.$item->podcast_id);
                }else if (!is_null($item->membership_id)){
                    $detalle->membership_id = $item->membership_id;
                    $detalle->original_amount = $item->membership->price;
                    if (!is_null($cuponAbierto)){
                        $detalle->amount = ( $item->membership->price - (($item->membership->price * $cuponAplicado->discount) / 100));
                    }else{
                        $detalle->amount = $item->membership->price;
                    }
                    $detalle->save();

                    $fechaActual = new Carbon();
                    $fechaExpiracion = $fechaActual->addYear();

                    DB::table('users')
                        ->where('id', '=', Auth::user()->id)
                        ->update(['membership_id', '=', $item->membership_id,
                                  'membership_expiration' => $fechaExpiracion]);
                }else if (!is_null($item->product_id)){
                    $detalle->product_id = $item->podcast_id;
                    $detalle->original_amount = $item->market_product->price;
                    if (!is_null($cuponAbierto)){
                        $detalle->amount = ( $item->market_product->price - (($item->market_product->price * $cuponAplicado->discount) / 100));
                    }else{
                        $detalle->amount = $item->market_product->price;
                    }
                    $detalle->save();

                    if ($item->gift == 0){
                        Auth::user()->products_users()->attach($item->product_id, ['date' => date('Y-m-d')]);
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = Auth::user()->id;
                        $regalo->product_id = $item->product_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
                    }

                    if ($item->market_product->price > 0){
                        $comisiones->store($item->product_id, 'producto', $item->instructor_code, $item->partner_code, $detalle->id);
                    }

                    //*** Notificar al Propietario ***//
                    $notificacion->store($item->market_product->user_id, 'Nueva Compra', 'Tiene una nueva compra de su Producto <b>'.$item->market_product->name.'</b>', 'fa fa-shopping-cart', 'instructors/products/purchases-record/'.$item->market_product->slug.'/'.$item->product_id);
                }

                $item->delete();
            }
        }else{
            //Pago Pendiente
            foreach ($items as $item){
                $detalle = new PendingProduct();
                $detalle->purchase_id = $compra->id;
                $detalle->instructor_code = $item->instructor_code;
                $detalle->partner_code = $item->partner_code;
                $detalle->gift = $item->gift;
                    
                if (!is_null($item->course_id)){
                    $detalle->course_id = $item->course_id;
                    $detalle->original_amount = $item->course->price;
                    if (!is_null($cuponAbierto)){
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = ( $item->course->price - (($item->course->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->course->price * 70) / 100);
                            $detalle->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = $item->course->price;
                        }else{
                            $detalle->amount = (($item->course->price * 70) / 100);
                        }
                    }

                    if ( ($item->instructor_code == 1) || (!is_null($item->partner_code)) ){
                        $detalle->amount = (($detalle->amount * 90) / 100);
                    }

                    $detalle->save();
                }else if (!is_null($item->podcast_id)){
                    $detalle->podcast_id = $item->podcast_id;
                    $detalle->original_amount = $item->podcast->price;
                    if (!is_null($cuponAbierto)){
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = ( $item->podcast->price - (($item->podcast->price * $cuponAplicado->discount) / 100));
                        }else{
                            $precioConMembresia = (($item->podcast->price * 70) / 100);
                            $detalle->amount = ( $precioConMembresia - (($precioConMembresia * $cuponAplicado->discount) / 100));
                        }
                    }else{
                        if (is_null(Auth::user()->membership_id)){
                            $detalle->amount = $item->podcast->price;
                        }else{
                            $detalle->amount = (($item->podcast->price * 70) / 100);
                        }
                    }

                    if ( ($item->instructor_code == 1) || (!is_null($item->partner_code)) ){
                        $detalle->amount = (($detalle->amount * 90) / 100);
                    }
                    $detalle->save();
                }else if (!is_null($item->membership_id)){
                    $detalle->membership_id = $item->membership_id;
                    $detalle->original_amount = $item->membership->price;
                    if (!is_null($cuponAbierto)){
                        $detalle->amount = ( $item->membership->price - (($item->membership->price * $cuponAplicado->discount) / 100));
                    }else{
                        $detalle->amount = $item->membership->price;
                    }
                    $detalle->save();
                }else if (!is_null($item->product_id)){
                    $detalle->product_id = $item->podcast_id;
                    $detalle->original_amount = $item->market_product->price;
                    if (!is_null($cuponAbierto)){
                        $detalle->amount = ( $item->market_product->price - (($item->market_product->price * $cuponAplicado->discount) / 100));
                    }else{
                        $detalle->amount = $item->market_product->price;
                    }
                    $detalle->save();
                }
                $item->delete();
            }
        }

        if (!is_null($cuponAbierto)){
            DB::table('applied_coupons')
                ->where('id', '=', $cuponAbierto->id)
                ->update(['status' => 0,
                          'closed_at' => date('Y-m-d')]);
        }

        if ($status == 1){
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

            //*** Enviar Correo con información del pago y de los items comprado ***//
            $data['infoCompra'] = $compra;
            $data['productos'] = PurchaseDetail::where('purchase_id', '=', $compra->id)->with('gift')->get();
            $data['usuario'] = Auth::user()->names." ".Auth::user()->last_names;
            $data['email'] = Auth::user()->email;

            Mail::send('emails.newPurchase',['data' => $data], function($msg) use ($data){
                $msg->to($data['email']);
                $msg->subject('Compra de Contenido en TransfórmatePRO');
            });
        }
        
        return $compra->id;
    }

    public function update_pending_payment($id, $status){
        $comisiones = new CommissionController();
        $notificacion = new NotificationController();
        
        $pago = Purchase::find($id);
        $pago->status = $status;
        $pago->save();

        $items = PendingProduct::where('purchase_id', '=', $id)->get();

        if ($status == 1){
            foreach ($items as $item){
                $detalle = new PurchaseDetail();
                $detalle->purchase_id = $pago->id;
                $detalle->instructor_code = $item->instructor_code;
                $detalle->partner_code = $item->partner_code;
                        
                if (!is_null($item->course_id)){
                    $detalle->course_id = $item->course_id;
                    $detalle->original_amount = $item->original_amount;
                    $detalle->amount = $item->amount;
                    $detalle->save();

                    if ($item->gift == 0){
                        DB::table('courses_students')->insert(
                            ['user_id' => $pago->user_id, 'course_id' => $item->course_id, 'progress' => 0, 'start_date' => date('Y-m-d')]
                        );
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = $pago->user_id;
                        $regalo->course_id = $item->course_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
                    }
                    
                    if ($item->course->price > 0){
                        $comisiones->store($item->course_id, 'curso', $item->instructor_code, $item->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->course->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Course <b>'.$item->course->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-courses/purchases-record/'.$item->course->slug.'/'.$item->course_id);
                }else if (!is_null($item->certification_id)){
                    $detalle->certification_id = $item->certification_id;
                    $detalle->original_amount = $item->original_amount;
                    $detalle->amount = $item->amount;
                    $detalle->save();

                    if ($item->gift == 0){
                        DB::table('certifications_students')->insert(
                            ['user_id' => $pago->user_id, 'certification_id' => $item->certification_id, 'progress' => 0, 'start_date' => date('Y-m-d')]
                        );
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = $pago->user_id;
                        $regalo->certification_id = $item->certification_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
                    }
                    
                    if ($item->certification->price > 0){
                        $comisiones->store($item->certification_id, 'certificacion', $item->instructor_code, $item->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->certification->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Mentoring <b>'.$item->certification->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-mentorings/purchases-record/'.$item->certification->slug.'/'.$item->certification_id);
                }else if (!is_null($item->podcast_id)){
                    $detalle->podcast_id = $item->podcast_id;
                    $detalle->original_amount = $item->original_amount;
                    $detalle->amount = $item->amount;
                    $detalle->save();

                    if ($item->gift == 0){
                        DB::table('podcasts_students')->insert(
                            ['user_id' => $pago->user_id, 'podcast_id' => $item->podcast_id]
                        );
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = $pago->user_id;
                        $regalo->podcast_id = $item->podcast_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
                    }

                    if ($item->podcast->price > 0){
                        $comisiones->store($item->podcast_id, 'podcast', $item->instructor_code, $item->partner_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->podcast->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Book <b>'.$item->podcast->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-books/purchases-record/'.$item->podcast->slug.'/'.$item->podcast_id);
                }else if (!is_null($item->membership_id)){
                    $detalle->membership_id = $item->membership_id;
                    $detalle->original_amount = $item->original_amount;
                    $detalle->amount = $item->amount;
                    $detalle->save();

                    $fechaActual = new Carbon();
                    $fechaExpiracion = $fechaActual->addYear();

                    DB::table('users')
                        ->where('id', '=', $pago->user_id)
                        ->update(['membership_id', '=', $item->membership_id,
                                  'membership_expiration' => $fechaExpiracion]);
                }else if (!is_null($item->product_id)){
                    $detalle->product_id = $item->product_id;
                    $detalle->original_amount = $item->original_amount;
                    $detalle->amount = $item->amount;
                    $detalle->save();

                    if ($item->gift == 0){
                        DB::table('products_users')->insert(
                            ['user_id' => $pago->user_id, 'product_id' => $item->product_id, 'date' => date('Y-m-d')]
                        );
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = $pago->user_id;
                        $regalo->product_id = $item->product_id;
                        $regalo->code = 'T-Gift-'.$detalle->id;
                        $regalo->purchase_detail_id = $detalle->id;
                        $regalo->status = 0; 
                        $regalo->save();
                    }

                    if ($item->market_product->price > 0){
                        $comisiones->store($item->product_id, 'producto', $item->instructor_code, $item->partner_code, $detalle->id);
                    }

                    //*** Notificar al Propietario ***//
                    $notificacion->store($item->market_product->user_id, 'Nueva Compra', 'Tiene una nueva compra de su Producto <b>'.$item->market_product->name.'</b>', 'fa fa-shopping-cart', 'instructors/products/purchases-record/'.$item->market_product->slug.'/'.$item->product_id);
                }

                $item->delete();
            }  

            $ultBalance = DB::table('transactions')
                            ->select('balance')
                            ->orderBy('created_at', 'DESC')
                            ->first();

            $transaccion = new Transaction();
            $transaccion->type = 'Ingreso';
            $transaccion->amount = $pago->amount;
            $transaccion->operation_id = $pago->id;
            $transaccion->date = date('Y-m-d');
            if (!is_null($ultBalance)){
                $transaccion->balance = $ultBalance->balance + $pago->amount;
            }else{
                $transaccion->balance = $pago->amount;
            }
            $transaccion->save(); 

            $datosUsuario = DB::table('users')
                                ->select('names', 'last_names', 'email')
                                ->where('id', '=', $pago->user_id)
                                ->first();

            //*** Enviar Correo con información del pago y del contenido comprado ***//
            $data['infoCompra'] = $pago;
            $data['productos'] = PurchaseDetail::where('purchase_id', '=', $pago->id)->with('gift')->get();
            $data['usuario'] = $datosUsuario->names." ".$datosUsuario->last_names;
            $data['email'] = $datosUsuario->email;

            Mail::send('emails.newPurchase',['data' => $data], function($msg) use ($data){
                $msg->to($data['email']);
                $msg->subject('Compra de Contenido en TransfórmatePRO');
            });
        }
    }
}
