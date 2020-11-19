<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership; use App\Models\PurchaseDetail;
use DB; use Auth; use Carbon\Carbon; 

class MembershipController extends Controller
{
    public function index(){
        $membresia = Membership::first();

        $datosPago = DB::table('purchases')
                        ->join('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
                        ->where('purchases.user_id', '=', Auth::user()->id)
                        ->where('purchase_details.membership_id', '=', Auth::user()->membership_id)
                        ->orderBy('purchases.created_at', 'DESC')
                        ->first();

        if (is_null($datosPago)){
            $datosRegalo = DB::table('purchase_details')
                                ->where('membership_id', '=', Auth::user()->membership_id)
                                ->where('user_id', '=', Auth::user()->id)
                                ->first();
        }else{
            $datosRegalo = NULL;
        }

        $fechaActual = Carbon::now();
        $fechaVencimiento = new Carbon(Auth::user()->membership_expiration);
        $dias = $fechaVencimiento->diffInDays($fechaActual); 

        if ($fechaVencimiento < $fechaActual){
            $membresiaVencida = true;
            $renovacion = true;
        }else{
            $membresiaVencida = false;
            if ($dias <= 15){
                $renovacion = true;
            }else{
                $renovacion = false;
            }
        }
        
        if (Auth::user()->role_id == 1){
            return view('students.membership')->with(compact('membresia', 'datosPago', 'datosRegalo', 'renovacion', 'membresiaVencida'));
        }else{
            return view('instructors.membership')->with(compact('membresia', 'datosPago', 'datosRegalo', 'renovacion', 'membresiaVencida'));
        }
    }

    public function redeem_gift(Request $request){
        $detalles = PurchaseDetail::where('gift_code', '=', $request->code)
                        ->first();

        if (!is_null($detalles)){
            if ($detalles->gift_status == 0){
                $fechaActual = new Carbon();
                $fechaExpiracion = $fechaActual->addYear();

                DB::table('users')
                    ->where('id', '=', Auth::user()->id)
                    ->update(['membership_id' => $detalles->membership_id,
                              'membership_expiration' => $fechaExpiracion]);

                $detalles->gift_status = 1;
                $detalles->user_id = Auth::user()->id;
                $detalles->save();

                if(Auth::user()->role_id == 1){
                    return redirect('students/membership')->with('msj-exitoso', 'La membresía ha sido canjeada con éxito. ¡¡Disfrútela!!');
                }else{
                    return redirect('instructors/membership')->with('msj-exitoso', 'La membresía ha sido canjeada con éxito. ¡¡Disfrútela!!');
                }
            }else{
                if(Auth::user()->role_id == 1){
                    return redirect('students')->with('msj-erroneo', 'El código aplicado ya fue usado.');
                }else{
                    return redirect('instructors')->with('msj-erroneo', 'El código aplicado ya fue usado.');
                }
            }
        }else{
            if(Auth::user()->role_id == 1){
                return redirect('students')->with('msj-erroneo', 'El código aplicado es inválido.');
            }else{
                return redirect('instructors')->with('msj-erroneo', 'El código aplicado es inválido.');
            }
        }
    }
}
