<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount; use App\Models\BankTransfer; use App\Models\Purchase; use App\Models\Gift;
use App\Models\PendingProduct; use App\Models\PurchaseDetail; use App\Models\Transaction; use App\Models\EfectyPayment;
use Auth; use DB; use Mail;

class BankController extends Controller
{
    //*** Admin / Banco / Cuentas Bancarias ***//
    public function index(Request $request){
        $cuentasBancarias = BankAccount::bank($request->get('busqueda'))
                                ->orderBy('bank', 'ASC')
                                ->get();

        return view('admins.bank.accounts')->with(compact('cuentasBancarias'));
    }

    //*** Admin / Banco / Cuentas Bancarias / Guardar Nueva Cuenta ***//
    public function store(Request $request){
        $cuenta = new BankAccount($request->all());
        $cuenta->save();

        return redirect('admins/bank/accounts')->with('msj-exitoso', 'La cuenta bancaria ha sido creada con éxito.');
    }

    //*** Admin / Banco / Cuentas Bancarias / Ver -Editar ***//
    public function show($id){
        $cuenta = BankAccount::find($id);

        return view('admins.bank.show')->with(compact('cuenta'));
    }

    //*** Admin / Banco / Cuentas Bancarias / Actualizar Cuenta ***//
    public function update(Request $request){
        $cuenta = BankAccount::find($request->id);
        $cuenta->fill($request->all());
        $cuenta->save();

        return redirect('admins/bank/show/'.$request->id)->with('msj-exitoso', 'Los datos de la cuenta bancaria han sido actualizados con éxito.');
    }

    //*** Admin / Banco / Cuentas Bancarias / Cambias Status ***//
    public function change_status($id, $status){
        $cuenta = BankAccount::find($id);
        $cuenta->status = $status;
        $cuenta->save();

        return redirect('admins/bank/accounts')->with('msj-exitoso', 'El estado de la cuenta ha sido cambiado con éxito.');
    }

    //*** Admin / Banco / Transferencias Pendientes ***//
    public function pending_transfers(){
        $transferencias = BankTransfer::where('status', '=', 0)
                                ->orderBy('id', 'ASC')
                                ->get();

        return view('admins.bank.pendingTransfers')->with(compact('transferencias'));
    }

    //*** Admin / Banco / Transferencias Pendientes / Ver Detalles ***//
    public function show_details($id){
        $datosPago = BankTransfer::where('id', '=', $id)
                        ->withCount('pending_products')
                        ->first();

        $cantItems = $datosPago->pending_products_count;
        $items = $datosPago->pending_products;

        if (!is_null($datosPago->coupon_id)){
            $porcentajePagado = 100 - $datosPago->coupon->discount;
            $montoDescontado = ( ($datosPago->amount * $datosPago->coupon->discount) / $porcentajePagado);
            $datosPago->original_amount = $datosPago->amount + $montoDescontado;
            $datosPago->discounted_amount = $montoDescontado;
        }

        return view('admins.bank.showDetails')->with(compact('datosPago', 'cantItems', 'items'));
    }

    //*** Admin / Banco / Transferencias Pendientes / Aprobar - Denegar ***//
    public function update_transfer($id, $status){
        $comisiones = new CommissionController();
        $notificacion = new NotificationController();

        $transferencia = BankTransfer::find($id);
        $transferencia->status = $status;
        $transferencia->save();

        if ($status == 1){
            $compra = new Purchase();
            $compra->user_id = $transferencia->user_id;
            $compra->original_amount = $transferencia->original_amount;
            $compra->amount = $transferencia->amount;
            $compra->payment_method = 'Transferencia Bancaria';
            $compra->payment_id = 'TRANSF'.$transferencia->id;
            $compra->coupon_id = $transferencia->coupon_id;
            $compra->membership_discount = $transferencia->membership_discount;
            $compra->instructor_code_discount = $transferencia->instructor_code_discount;
            $compra->date = date('Y-m-d');
            $compra->save();

            $items = PendingProduct::where('bank_transfer_id', '=', $id)->get();

            foreach ($items as $item){
                $detalle = new PurchaseDetail();
                $detalle->purchase_id = $compra->id;
                $detalle->instructor_code = $item->instructor_code;
                $detalle->original_amount = $item->original_amount;
                $detalle->amount = $item->amount;
                    
                if (!is_null($item->course_id)){
                    $detalle->course_id = $item->course_id;
                    $detalle->save();

                    DB::table('courses_students')->insert([
                        ['user_id' => $compra->user_id, 'course_id' => $item->course_id, 'start_date' => date('Y-m-d')]
                    ]);

                    if ($item->course->price > 0){
                        $comisiones->store($item->course_id, 'curso', $item->instructor_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->course->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Course <b>'.$item->course->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-courses/purchases-record/'.$item->course->slug.'/'.$item->course_id);
                }else if (!is_null($item->certification_id)){
                    $detalle->certification_id = $item->certification_id;
                    $detalle->save();

                    DB::table('certifications_students')->insert([
                        ['user_id' => $compra->user_id, 'certification_id' => $item->certification_id, 'start_date' => date('Y-m-d')]
                    ]);

                    if ($item->certification->price > 0){
                        $comisiones->store($item->certification_id, 'certificacion', $item->instructor_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->certification->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Mentoring <b>'.$item->certification->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-mentorings/purchases-record/'.$item->certification->slug.'/'.$item->certification_id);
                }else if (!is_null($item->podcast_id)){
                    $detalle->podcast_id = $item->podcast_id;
                    $detalle->save();

                    DB::table('podcasts_students')->insert([
                        ['user_id' => $compra->user_id, 'podcast_id' => $item->podcast_id]
                    ]);

                    if ($item->podcast->price > 0){
                        $comisiones->store($item->podcast_id, 'podcast', $item->instructor_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->podcast->user_id, 'Nueva Compra', 'Tiene una nueva compra de su T-Book <b>'.$item->podcast->title.'</b>', 'fa fa-shopping-cart', 'instructors/t-books/purchases-record/'.$item->podcast->slug.'/'.$item->podcast_id);
                }else if (!is_null($item->membership_id)){
                    $detalle->membership_id = $item->membership_id;
                    $detalle->save();

                    DB::table('users')
                        ->where('id', '=', $compra->user_id)
                        ->update(['membership_id', '=', $item->membership_id]);
                }else if (!is_null($item->product_id)){
                    $detalle->product_id = $item->product_id;
                    $detalle->save();

                    DB::table('products_users')->insert([
                        ['user_id' => $compra->user_id, 'product_id' => $item->product_id, 'date' => date('Y-m-d')]
                    ]);

                    if ($item->market_product->price > 0){
                        $comisiones->store($item->product_id, 'producto', $item->instructor_code, $detalle->id);
                    }

                    //*** Notificar al Instructor ***//
                    $notificacion->store($item->market_product->user_id, 'Nueva Compra', 'Tiene una nueva compra de su producto <b>'.$item->market_product->name.'</b>', 'fa fa-shopping-cart', 'instructors/products/purchases-record/'.$item->market_product->slug.'/'.$item->product_id);
                }

                $item->delete();
            }

            $ultBalance = DB::table('transactions')
                            ->select('balance')
                            ->orderBy('id', 'DESC')
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

            //*** Notificar al Cliente (Transferencia Aprobada) ***//
            $notificacion->store($transferencia->user_id, 'Pago Aprobado', 'Su pago por Transferencia Bancaria ha sido aprobado.', 'fa fa-money', 'students/my-content');

            $datosUsuario = DB::table('users')
                                ->select('names', 'last_names', 'email')
                                ->where('id', '=', $compra->user_id)
                                ->first();

            //*** Enviar Correo con información del pago y del contenido comprado ***//
            $data['infoCompra'] = $compra;
            $data['productos'] = $items;
            $data['usuario'] = $datosUsuario->names." ".$datosUsuario->last_names;
            $data['email'] = $datosUsuario->email;

            Mail::send('emails.newPurchase',['data' => $data], function($msg) use ($data){
                $msg->to($data['email']);
                $msg->subject('Compra de Contenido en TransfórmatePRO');
            });
            
            return redirect('admins/bank/pending-transfers')->with('msj-exitoso', 'La transferencia bancaria ha sido aprobada con éxito.');
        }else{
            //*** Notificar al Cliente (Transferencia Denegada) ***//
            $notificacion->store($transferencia->user_id, 'Pago Rechazado', 'Su pago por Transferencia Bancaria ha sido rechazado.', 'fa fa-times', 'students'); 

            return redirect('admins/bank/pending-transfers')->with('msj-exitoso', 'La transferencia bancaria ha sido rechazada con éxito.');
        }  
    }

    public function update_payment($tipo, $id, $status){
        $comisiones = new CommissionController();
        $notificacion = new NotificationController();

        if ($tipo == 'Transferencia'){
            $pago = BankTransfer::find($id);
            $pago->status = $status;
            $pago->save();
        }else if ($tipo == 'Efectivo'){
            $pago = EfectyPayment::find($id);
            $pago->status = $status;
            $pago->save();
        }

        if ($status == 1){
            $compra = new Purchase();
            $compra->user_id = $pago->user_id;
            $compra->original_amount = $pago->original_amount;
            $compra->amount = $pago->amount;
            if ($tipo == 'Transferencia'){
                $compra->payment_method = 'Transferencia Bancaria';
                $compra->payment_id = 'TRANSF'.$id;
            }else if ($tipo == 'Efectivo'){
                $compra->payment_method = 'Efectivo';
                $compra->payment_id = 'EFECTY'.$id;
            }
            $compra->coupon_id = $pago->coupon_id;
            $compra->membership_discount = $pago->membership_discount;
            $compra->instructor_code_discount = $pago->instructor_code_discount;
            $compra->date = date('Y-m-d');
            $compra->save();

            if ($tipo == 'Transferencia'){
                $items = PendingProduct::where('bank_transfer_id', '=', $id)->get();
            }else if ($tipo == 'Efectivo'){
                $items = PendingProduct::where('efecty_payment_id', '=', $id)->get();
            }

            foreach ($items as $item){
                $detalle = new PurchaseDetail();
                $detalle->purchase_id = $compra->id;
                $detalle->instructor_code = $item->instructor_code;
                $detalle->partner_code = $item->partner_code;
                $detalle->original_amount = $item->original_amount;
                $detalle->amount = $item->amount;
                    
                if (!is_null($item->course_id)){
                    $detalle->course_id = $item->course_id;
                    $detalle->save();

                    if ($item->gift == 0){
                         DB::table('courses_students')->insert([
                            ['user_id' => $compra->user_id, 'course_id' => $item->course_id, 'start_date' => date('Y-m-d')]
                        ]);
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = $compra->user_id;
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
                }else if (!is_null($item->podcast_id)){
                    $detalle->podcast_id = $item->podcast_id;
                    $detalle->save();

                    if ($item->gift == 0){
                        DB::table('podcasts_students')->insert([
                            ['user_id' => $compra->user_id, 'podcast_id' => $item->podcast_id]
                        ]);
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = $compra->user_id;
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
                    $detalle->save();

                    DB::table('users')
                        ->where('id', '=', $compra->user_id)
                        ->update(['membership_id', '=', $item->membership_id]);
                }else if (!is_null($item->product_id)){
                    $detalle->product_id = $item->product_id;
                    $detalle->save();

                    if ($item->gift == 0){
                        DB::table('products_users')->insert([
                            ['user_id' => $compra->user_id, 'product_id' => $item->product_id, 'date' => date('Y-m-d')]
                        ]);
                    }else{
                        $regalo = new Gift();
                        $regalo->buyer_id = $compra->user_id;
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
                    $notificacion->store($item->market_product->user_id, 'Nueva Compra', 'Tiene una nueva compra de su producto <b>'.$item->market_product->name.'</b>', 'fa fa-shopping-cart', 'instructors/products/purchases-record/'.$item->market_product->slug.'/'.$item->product_id);
                }

                $item->delete();
            }

            $ultBalance = DB::table('transactions')
                            ->select('balance')
                            ->orderBy('id', 'DESC')
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

            if ($tipo == 'Transferencia'){
                //*** Notificar al Cliente (Transferencia Aprobada) ***//
                $notificacion->store($pago->user_id, 'Pago Aprobado', 'Su pago por Transferencia Bancaria ha sido aprobado.', 'fa fa-money', 'students/my-content');
            }else if ($tipo == 'Transferencia'){
                //*** Notificar al Cliente (Pago Efectivo Aprobado) ***//
                $notificacion->store($pago->user_id, 'Pago Aprobado', 'Su pago en Efectivo ha sido aprobado.', 'fa fa-money', 'students/my-content');
            }

            $datosUsuario = DB::table('users')
                                ->select('names', 'last_names', 'email')
                                ->where('id', '=', $compra->user_id)
                                ->first();

            //*** Enviar Correo con información del pago y del contenido comprado ***//
            $data['infoCompra'] = $compra;
            $data['productos'] = PurchaseDetail::where('purchase_id', '=', $compra->id)->with('gift')->get();
            $data['usuario'] = $datosUsuario->names." ".$datosUsuario->last_names;
            $data['email'] = $datosUsuario->email;

            Mail::send('emails.newPurchase',['data' => $data], function($msg) use ($data){
                $msg->to($data['email']);
                $msg->subject('Compra de Contenido en TransfórmatePRO');
            });
        }else{
            if ($tipo == 'Transferencia'){
                //*** Notificar al Cliente (Transferencia Denegada) ***//
                $notificacion->store($pago->user_id, 'Pago Denegado', 'Su pago por Transferencia Bancaria ha sido rechazada.', 'fa fa-money', 'students/my-content');
            }else if ($tipo == 'Transferencia'){
                //*** Notificar al Cliente (Pago Efectivo Denegado) ***//
                $notificacion->store($pago->user_id, 'Pago Denegado', 'Su pago en Efectivo ha sido rechazado.', 'fa fa-money', 'students/my-content');
            }
        }  
    }

    //*** Admin / Banco / Historial de Transferencias ***//
    public function transfers_record(Request $request){
        $transferencias = BankTransfer::where('status', '<>', 0)
                                ->orderBy('id', 'DESC')
                                ->get();

        return view('admins.bank.transfersRecord')->with(compact('transferencias'));
    }
}
