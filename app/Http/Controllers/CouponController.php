<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Auth; use DB;

class CouponController extends Controller
{
    //**** Admin / Cupones / Listado ****//
    public function index(){
        $cupones = Coupon::orderBy('id', 'DESC')
                        ->get();

        return view('admins.coupons.index')->with(compact('cupones'));
    }

    //**** Admin / Cupones / Almacenar Nuevo Cupón
    public function store(Request $request){
        $categorias = array();
        $cont = 0;
        foreach ($request->categories as $cat){
            $categorias[$cont] = $cat;
            $cont++;
        }

        $cupon = new Coupon($request->all());
        $cupon->code = strtoupper(str_random(15));
        $cupon->categories_availables = implode(",", $categorias);
        $cupon->save();
        
        return redirect('admins/coupons')->with('msj-exitoso', 'El cupón de descuento ha sido generado con éxito.');
    }

    //**** Admin / Cupones / Ver - Editar Cupón ***//
    public function show($id){
        $cupon = Coupon::find($id);

        $categorias = DB::table('categories')
                        ->orderBy('id', 'ASC')
                        ->get();

        $categoriasDisponibles = explode(",", $cupon->categories_availables);

        return view('admins.coupons.show')->with(compact('cupon', 'categorias', 'categoriasDisponibles'));
    }

    //**** Admin / Cupones / Editar Cupón / Guardar Cambios ***//
    public function update(Request $request){
        $categorias = array();
        $cont = 0;
        foreach ($request->categories as $cat){
            $categorias[$cont] = $cat;
            $cont++;
        }

        $cupon = Coupon::find($request->coupon_id);
        $cupon->fill($request->all());
        $cupon->categories_availables = implode(",", $categorias);
        $cupon->save();

         return redirect('admins/coupons/show/'.$request->coupon_id)->with('msj-exitoso', 'Los datos del cupón de descuento han sido actualizados con éxito.');
    }

    //**** Estudiante / Carrito de Compras / Aplicar Cupón de Descuento ***//
    public function apply(Request $request){
        $coupon = DB::table('coupons')
                    ->where('code', $request->coupon)
                    ->first();

        if ($coupon) {
            if ($coupon->status == 1){
                if ($coupon->applications < $coupon->limit){       
                    $cupon = Coupon::find($coupon->id);
                    $cupon->applications = $coupon->applications + 1;
                    $cupon->save();

                    DB::table('applied_coupons')->insert(
                        ['user_id' => Auth::user()->id, 'coupon_id' => $coupon->id, 'status' => 1, 'opened_at' => date('Y-m-d')]
                    );

                    if ($request->route == 'checkout'){
                        if (Auth::user()->role_id == 1){
                            return redirect('students/shopping-cart/checkout')->with('msj-exitoso', 'El cupón de descuento ha sido aplicado con éxito.');
                        }else{
                            return redirect('instructors/shopping-cart/checkout')->with('msj-exitoso', 'El cupón de descuento ha sido aplicado con éxito.');
                        }
                    }else{
                        if (Auth::user()->role_id == 1){
                            return redirect('students/shopping-cart')->with('msj-exitoso', 'El cupón de descuento ha sido aplicado con éxito.');
                        }else{
                            return redirect('instructors/shopping-cart')->with('msj-exitoso', 'El cupón de descuento ha sido aplicado con éxito.');
                        }
                    }
                }else{
                    if ($request->route == 'checkout'){
                        if (Auth::user()->role_id == 1){
                            return redirect('students/shopping-cart/checkout')->with('msj-erroneo', 'El cupón que ingesó ya alcanzó el límite de aplicaciones permitidas.');
                        }else{
                            return redirect('instructors/shopping-cart/checkout')->with('msj-erroneo', 'El cupón que ingesó ya alcanzó el límite de aplicaciones permitidas.');
                        }
                    }else{
                        if (Auth::user()->role_id == 1){
                            return redirect('students/shopping-cart')->with('msj-erroneo', 'El cupón que ingesó ya alcanzó el límite de aplicaciones permitidas.');
                        }else{
                            return redirect('instructors/shopping-cart')->with('msj-erroneo', 'El cupón que ingesó ya alcanzó el límite de aplicaciones permitidas.');
                        }
                    }
                }
            }else{
                if ($request->route == 'checkout'){
                    if (Auth::user()->role_id == 1){
                        return redirect('students/shopping-cart/checkout')->with('msj-erroneo', 'El cupón que ingesó no se encuentra disponible actualmente.');
                    }else{
                        return redirect('instructors/shopping-cart/checkout')->with('msj-erroneo', 'El cupón que ingesó no se encuentra disponible actualmente.');
                    }
                }else{
                    if (Auth::user()->role_id == 1){
                        return redirect('students/shopping-cart')->with('msj-erroneo', 'El cupón que ingesó no se encuentra disponible actualmente.');
                    }else{
                        return redirect('instructors/shopping-cart')->with('msj-erroneo', 'El cupón que ingesó no se encuentra disponible actualmente.');
                    }
                }
            }
        }else{       
            if ($request->route == 'checkout'){   
                if (Auth::user()->role_id == 1){
                    return redirect('students/shopping-cart/checkout')->with('msj-erroneo', 'El código que ingesó es inválido.');
                }else{
                    return redirect('instructors/shopping-cart/checkout')->with('msj-erroneo', 'El código que ingesó es inválido.');
                }
            }else{
                if (Auth::user()->role_id == 1){
                    return redirect('students/shopping-cart')->with('msj-erroneo', 'El código que ingesó es inválido.');
                }else{
                    return redirect('instructors/shopping-cart')->with('msj-erroneo', 'El código que ingesó es inválido.');
                }
            }
        }
    }

    //**** Admin / Cupones / Cupones Aplicados ****//
    public function applied_coupons(){
        $aplicaciones = DB::table('applied_coupons')
                            ->orderBy('closed_at', 'DESC')
                            ->get();

        $cupones = collect();
        foreach ($aplicaciones as $aplicacion){
            $cupon = new \stdClass();

            $datosCompra = DB::table('purchases')
                            ->select('id', 'amount')
                            ->where('user_id', '=', $aplicacion->user_id)
                            ->where('coupon_id', '=', $aplicacion->coupon_id)
                            ->first();

            $cupon->purchase_id = $datosCompra->id;
            $cupon->amount = $datosCompra->amount;
            
            $datosUsuario = DB::table('users')
                                ->select('names', 'last_names')
                                ->where('id', '=', $aplicacion->user_id)
                                ->first();

            $cupon->user = $datosUsuario->names." ".$datosUsuario->last_names;

            $datosCupon = DB::table('coupons')
                            ->select('name', 'discount')
                            ->where('id', '=', $aplicacion->coupon_id)
                             ->first();

            $cupon->name = $datosCupon->name;
            $cupon->discount = $datosCupon->discount;
            $cupon->apply_date = $aplicacion->closed_at;

            $porcentajePagado = 100 - $datosCupon->discount;
            $montoDescontado = ( ($datosCompra->amount * $datosCupon->discount) / $porcentajePagado);
            $cupon->original_amount = $datosCompra->amount + $montoDescontado;
            $cupones->push($cupon);
        }   

        $cuponesAplicados = $cupones->sortByDesc("apply_date");

        return view('admins.coupons.appliedCoupons')->with(compact('cuponesAplicados'));
    }
}
