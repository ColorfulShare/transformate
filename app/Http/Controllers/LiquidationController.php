<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Liquidation; use App\Models\Commission; use App\Models\Transaction; use App\Models\User;
use Auth; use DB;

class LiquidationController extends Controller
{
    //**** Instructor / Mis Retiros ****//
    //**** Admin / Retiros / Historial ****//
    public function index(){
        if (Auth::user()->role_id == 2){
            $retiros = Liquidation::where('user_id', '=', Auth::user()->id)
                        ->orderBy('date', 'DESC')
                        ->paginate(10);

            return view('instructors.liquidations.index')->with(compact('retiros'));
        }else if (Auth::user()->role_id == 3){
            $retiros = Liquidation::where('status', '=', 1)
                        ->orderBy('created_at', 'DESC')
                        ->get();

            return view('admins.liquidations.index')->with(compact('retiros'));
        }
    }

    //**** Admin / Finanzas / Egresos ****//
    public function expenses(){
        $egresos = Liquidation::where('status', '=', 1)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.finances.expenses')->with(compact('egresos'));
    }

    //**** Admin / Finanzas / Engresos / Ver Detalles ****/
    public function show_expense($id){
        $egreso = Liquidation::find($id);

        $comisiones = Commission::where('liquidation_id', '=', $id)
                        ->get();
        
        return view('admins.finances.showExpense')->with(compact('egreso', 'comisiones'));
    }

    //**** Admin / Retiros / Retiros Pendientes ****//
    public function pending(){
        $retiros = Liquidation::where('status', '=', 0)
                        ->orderBy('created_at', 'ASC')
                        ->get();

        return view('admins.liquidations.pending')->with(compact('retiros'));
    }

    //**** Instructor / Ganancias / Solicitar Retiro
    public function store(Request $request){
        if ($request->wallet == 'T-Courses'){
            if ($request->amount > Auth::user()->balance){
                return redirect('instructors/liquidations')->with('msj-erroneo', 'El monto solicitado excede su monto disponible en la billetera T-Courses.');
            }
            $balance = Auth::user()->balance;
        }else{
            if ($request->amount > Auth::user()->event_balance){
                return redirect('instructors/liquidations')->with('msj-erroneo', 'El monto solicitado excede su monto disponible en la billetera T-Events.');
            }
            $balance = Auth::user()->event_balance;
        }

        $liquidacion = new Liquidation();
        $liquidacion->user_id = Auth::user()->id;
        $liquidacion->amount = $request->amount;
        $liquidacion->status = 0;
        $liquidacion->date = date('Y-m-d');
        $liquidacion->save();

        if ($request->wallet == 'T-Courses'){
            $comisiones = Commission::where('user_id', '=', Auth::user()->id)
                            ->where('status', '=', 0)
                            ->where('purchase_detail_id', '<>', NULL)
                            ->orderBy('id', 'ASC')
                            ->get();
        }else{
            $comisiones = Commission::where('user_id', '=', Auth::user()->id)
                            ->where('status', '=', 0)
                            ->where('event_subscription_id', '<>', NULL)
                            ->orderBy('id', 'ASC')
                            ->get();
        }

        $usuario = User::find(Auth::user()->id);

        if ($request->amount == $balance){
            if ($request->wallet == 'T-Courses'){
                DB::table('commissions')
                    ->where('user_id', '=', Auth::user()->id)
                    ->where('status', '=', 0)
                    ->where('purchase_detail_id', '<>', NULL)
                    ->update(['status' => 1,
                              'liquidation_id' => $liquidacion->id,
                              'updated_at' => date('Y-m-d H:i:s')]);

                $usuario->balance = 0;
            }else{
                DB::table('commissions')
                    ->where('user_id', '=', Auth::user()->id)
                    ->where('status', '=', 0)
                    ->where('event_subscription_id', '<>', NULL)
                    ->update(['status' => 1,
                              'liquidation_id' => $liquidacion->id,
                              'updated_at' => date('Y-m-d H:i:s')]);

                $usuario->event_balance = 0;
            }

            $usuario->save();
        }else{
            $monto = $request->amount;
            foreach ($comisiones as $comision){
                if ($monto > 0){
                    if ($comision->amount <= $monto){
                        $comision->liquidation_id = $liquidacion->id;
                        $comision->status = 1;
                        $comision->save();

                        $monto = $monto - $comision->amount;
                    }else{
                        $nuevoMontoComision = $comision->amount - $monto;

                        $comision->amount = $monto;
                        $comision->liquidation_id = $liquidacion->id;
                        $comision->status = 1;
                        $comision->save();

                        $nuevaComision = new Commission();
                        $nuevaComision->user_id = Auth::user()->id;
                        $nuevaComision->amount = $nuevoMontoComision;
                        $nuevaComision->referred_id = $comision->referred_id;
                        $nuevaComision->type = 'Comisión Restante por Liquidación';
                        $nuevaComision->purchase_detail_id = $comision->purchase_detail_id;
                        $nuevaComision->event_subscription_id = $comision->event_subscription_id;
                        $nuevaComision->status = 0;
                        $nuevaComision->save();

                        $monto = 0;
                    }
                }
            }

            if ($request->wallet == 'T-Courses'){
                $usuario->balance = $usuario->balance - $request->amount;
            }else{
                $usuario->event_balance = $usuario->event_balance - $request->amount;
            }

            $usuario->save();
        }

        //**** Notificar al Administrador ***//
        $notificacion = new NotificationController();
        $notificacion->store(1, 'Nueva Solicitud de Retiro', 'El instructor <b>'.Auth::user()->names.' '.Auth::user()->last_names.'</b> ha solicitado retirar sus fondos', 'fas fa-arrow-alt-circle-right', 'admins/liquidations/pending');

        return redirect('instructors/liquidations')->with('msj-exitoso', 'La solicitud de retiro ha sido generada con éxito.');
    }

    public function show($id){
        $liq = DB::table('liquidations')
                ->select('user_id')
                ->where('id', '=', $id)
                ->first();

        if ($liq->user_id == Auth::user()->id){
            $comisiones = Commission::where('liquidation_id', '=', $id)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(25);

            $liquidacion = $id;

            return view('instructors.liquidations.show')->with(compact('comisiones', 'liquidacion'));
        }else{
            return redirect('instructors/liquidations');
        }
    }

    public function update(Request $request){                 
        $liquidacion = Liquidation::find($request->liquidation_id);
        $liquidacion->fill($request->all());

        $datosUsuario = DB::table('users')
                            ->select('charging_method')
                            ->where('id', '=', $liquidacion->user_id)
                            ->first();

        $liquidacion->payment_method = $datosUsuario->charging_method;
        $liquidacion->status = 1;
        $liquidacion->processed_at = date('Y-m-d');
        $liquidacion->save();

        DB::table('commissions')
            ->where('liquidation_id', '=', $liquidacion->id)
            ->update(['status' => 2,
                      'updated_at' => date('Y-m-d H:i:s')]);

        $ultBalance = DB::table('transactions')
                        ->select('balance')
                        ->orderBy('created_at', 'DESC')
                        ->first();

        $transaccion = new Transaction();
        $transaccion->type = 'Egreso';
        $transaccion->amount = $liquidacion->amount;
        $transaccion->operation_id = $liquidacion->id;
        $transaccion->date = date('Y-m-d');
        if (!is_null($ultBalance)){
            $transaccion->balance = $ultBalance->balance - $liquidacion->amount;
        }else{
            $transaccion->banace = -$liquidacion->amount;
        }
        $transaccion->save();

        //**** Notificar al Instructor ***//
        $notificacion = new NotificationController();
        $notificacion->store($liquidacion->user_id, 'Nuevo Retiro Procesado', 'El administrador ha procesado su solicitud de retiro por un monto de <b>'.$liquidacion->amount.'$</b>', 'fas fa-arrow-alt-circle-right', 'instructors/liquidations');

        return redirect('admins/liquidations/pending')->with('msj-exitoso', 'El retiro ha sido procesado con éxito');
    }

    //**** Admin / Retiros / Generar Liquidaciones
    public function generate_all(){
        $mentores = DB::table('users')
                        ->select('id', 'balance')
                        ->where('role_id', '=', 2)
                        ->where('balance', '>', 0)
                        ->get();

        foreach ($mentores as $mentor){
            $liquidacion = new Liquidation();
            $liquidacion->user_id = $mentor->id;
            $liquidacion->amount = $mentor->balance;
            $liquidacion->status = 0;
            $liquidacion->date = date('Y-m-d');
            $liquidacion->admin = Auth::user()->id;
            $liquidacion->save();

            DB::table('commissions')
                ->where('user_id', '=', $mentor->id)
                ->where('status', '=', 0)
                ->update(['status' => 1,
                          'liquidation_id' => $liquidacion->id]);

            DB::table('users')
                ->where('id', '=', $mentor->id)
                ->update(['balance' => 0]);

            //**** Notificar al Mentor ***//
            $notificacion = new NotificationController();
            $notificacion->store($mentor->id, 'Nueva Solicitud de Retiro', 'El administrador ha generado un retiro de sus fondos', 'fas fa-arrow-alt-circle-right', 'instructors/liquidations/show/'.$liquidacion->id);
        }

        return redirect('admins/liquidations/pending')->with('msj-exitoso', 'Los retiros se han generado con éxito.');
    }
}
