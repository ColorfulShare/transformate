<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission; use App\Models\User; use App\Models\PurchaseDetail;
use Auth; use DB; use Carbon\Carbon;

class CommissionController extends Controller
{
    //*** Instructor - Partners / Mis Ganancias ***//
    public function index($type = null){
        if ($type == null){
            $ganancias = Commission::where('user_id', '=', Auth::user()->id)
                        ->orderBy('id', 'DESC')
                        ->paginate(10);
        }else if ($type == 'events'){
            $ganancias = Commission::where('user_id', '=', Auth::user()->id)
                            ->where('event_subscription_id', '<>', NULL)
                            ->orderBy('id', 'DESC')
                            ->paginate(10);
        }else if ($type == 'courses'){
            $ganancias = Commission::where('user_id', '=', Auth::user()->id)
                            ->where('purchase_detail_id', '<>', NULL)
                            ->orderBy('id', 'DESC')
                            ->paginate(10);
        }

        if (Auth::user()->role_id == 2){
            return view('instructors.commissions.index')->with(compact('ganancias', 'type'));
        }else{
            return view('partners.commissions.index')->with(compact('ganancias'));
        }
       
    }

    //*** Crear comisión al momento de procesar una compra ***//
    public function store($idProducto, $tipoProducto, $codigoMentor, $codigoPartner, $idDetalleCompra){
        $notificacion = new NotificationController();

        $detallesCompra = PurchaseDetail::where('id', '=', $idDetalleCompra)
                            ->first();

        $montoFinal = (($detallesCompra->amount * 94.5) / 100);

        if ($tipoProducto == 'curso'){
            $datos = DB::table('courses')
                        ->select('user_id')
                        ->where('id', '=', $idProducto)
                        ->first();
        }else if ($tipoProducto == 'certificacion'){
            $datos = DB::table('certifications')
                        ->select('user_id')
                        ->where('id', '=', $idProducto)
                        ->first();
        }else if ($tipoProducto == 'podcast'){
            $datos = DB::table('podcasts')
                        ->select('user_id')
                        ->where('id', '=', $idProducto)
                        ->first();
        }else if ($tipoProducto == 'producto'){
            $datos = DB::table('products')
                        ->select('user_id')
                        ->where('id', '=', $idProducto)
                        ->first();
        }
        
        //****** Comisión del Mentor *****//
        $comision = new Commission();
        $comision->user_id = $datos->user_id;
        if ($tipoProducto != 'producto'){
            if ( ($codigoMentor == 1) && (!is_null($codigoPartner)) ){
                $comision->amount = (($montoFinal * 60) / 100);
                $comision->type = 'Compra Directa con T-Partner';
            }else if ($codigoMentor == 1){
                $comision->amount = (($montoFinal * 70) / 100);
                $comision->type = 'Compra Directa';
            }else{
                $comision->amount = (($montoFinal * 40) / 100);
                $comision->type = 'Compra Orgánica';
            }
            $comision->wallet = 'T-Courses';
        }else{
            $comision->amount = (($montoFinal * 70) / 100);
            $comision->type = 'Producto Marketplace';
            $comision->wallet = 'T-Marketplace';
        }
        $comision->referred_id = $detallesCompra->purchase->user_id;
        $comision->purchase_detail_id = $idDetalleCompra;
        $comision->status = 0;
        $comision->date = date('Y-m-d');
        $comision->save();

        $mentor = User::find($datos->user_id);
        $mentor->balance = $mentor->balance + $comision->amount;
        $mentor->save();

        $notificacion->store($datos->user_id, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision->type.'</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision->id);
        //********************************************//
        
        //****** Comisión del T-Partner (Si Aplica) *****//
        if (!is_null($codigoPartner)){
            $comision2 = new Commission();
            $comision2->user_id = $codigoPartner;
            $comision2->amount = (($montoFinal * 10) / 100);
            $comision2->type = 'Código T-Partner';
            $comision2->referred_id = $detallesCompra->purchase->user_id;
            $comision2->purchase_detail_id = $idDetalleCompra;
            $comision2->status = 0;
            $comision2->date = date('Y-m-d');
            $comision2->save();

            $partner = User::find($codigoPartner);
            $partner->balance = $partner->balance + $comision2->amount;
            $partner->save();

            $notificacion->store($codigoPartner, 'Nueva Comisión', 'Tiene una nueva comisión por <b>'.$comision->type.'</b>', 'fas fa-comment-dollar', 't-partners/commissions/show/'.$comision2->id);

            if (!is_null($partner->sponsor_id)){
                if ($partner->sponsor->role_id == 2){
                    $comision4 = new Commission();
                    $comision4->user_id = $partner->sponsor_id;
                    $comision4->amount = (($comision2->amount * 10) / 100);
                    $comision4->type = 'Plan de Afiliado';
                    $comision4->referred_id = $partner->id;
                    $comision4->purchase_detail_id = $idDetalleCompra;
                    $comision4->status = 0;
                    $comision4->date = date('Y-m-d');
                    $comision4->save();

                    $partnerSponsor = User::find($partner->sponsor_id);
                    $partnerSponsor->balance = $partnerSponsor->balance + $comision4->amount;
                    $partnerSponsor->save();

                    //**** Notificar al Patrocinador ***//s
                    $notificacion->store($partner->sponsor_id, 'Nueva Comisión', 'Tiene una nueva comisión por <b>Plan de Afiliados</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision4->id);
                }
            }
        }
        //********************************************//

        if ($tipoProducto != 'producto'){
            if (!is_null($mentor->sponsor_id)){
                $datosReferido = DB::table('commissions')
                                        ->select('date')
                                        ->where('user_id', '=', $mentor->id)
                                        ->orderBy('id', 'ASC')
                                        ->first();

                $fechaPrimeraCompra = new Carbon($datosReferido->date);
                $fechaActual = Carbon::now();
                $fechaFinal = $fechaPrimeraCompra->addYear();

                if ($fechaActual <= $fechaFinal){
                    $comision3 = new Commission();
                    $comision3->user_id = $mentor->sponsor_id;
                    $comision3->amount = (($comision->amount * 10) / 100);
                    $comision3->type = 'Plan de Afiliado';
                    $comision3->referred_id = $mentor->id;
                    $comision3->purchase_detail_id = $idDetalleCompra;
                    $comision3->status = 0;
                    $comision3->date = date('Y-m-d');
                    $comision3->save();

                    $mentorSponsor = User::find($mentor->sponsor_id);
                    $mentorSponsor->balance = $mentorSponsor->balance + $comision3->amount;
                    $mentorSponsor->save();

                    //**** Notificar al Patrocinador ***//s
                    $notificacion->store($mentor->sponsor_id, 'Nueva Comisión', 'Tiene una nueva comisión por <b>Plan de Afiliados</b>', 'fas fa-comment-dollar', 'instructors/commissions/show/'.$comision3->id);
                }
            }
        }
    }

    //*** Instructor / Mis Ganancias / Ver Comisión ***//
    //*** Admin / Ganancias / Ver Comisión ***//
    public function show($id){
        $comision = Commission::find($id);

        if (Auth::user()->role_id == 2){
            if ($comision->user_id == Auth::user()->id){
                return view('instructors.commissions.show')->with(compact('comision'));
            }else{
                return redirect('instructors/commissions');
            }
        }else if (Auth::user()->role_id == 4){
            if ($comision->user_id == Auth::user()->id){
                return view('partners.commissions.show')->with(compact('comision'));
            }else{
                return redirect('t-partners/commissions');
            }
        }else{
            return view('admins.commissions.show')->with(compact('comision'));
        }
    }

    //*** Admin / Reportes / Ganancias por Referencia ***//
    public function commissions_by_reference(){
        $comisiones = Commission::where('type', '=', 'Plan de Afiliado')
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.reports.commissionsByReference')->with(compact('comisiones'));
    }
}
