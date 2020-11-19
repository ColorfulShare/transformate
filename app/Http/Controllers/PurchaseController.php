<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase; use App\Models\PendingProduct;
use DB;

class PurchaseController extends Controller
{
    //*** Admin / Pedidos ***//
    public function index(Request $request){
        if ($request->get('contenido') != ""){
            $content_type = explode("-", $request->get('contenido'));

            if ($content_type[0] == 'Curso'){
               $compras = Purchase::PaymentMethod($request->get('forma_pago'))
                            ->join('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
                            ->select('purchases.*')
                            ->where('purchase_details.course_id', '=', $content_type[1])
                            ->orderBy('purchases.id', 'DESC')
                            ->groupBy('purchase_details.purchase_id')
                            ->get(); 
            }else if ($content_type[0] == 'Certificacion'){
                $compras = Purchase::PaymentMethod($request->get('forma_pago'))
                            ->join('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
                            ->select('purchases.*')
                            ->where('purchase_details.certification_id', '=', $content_type[1])
                            ->orderBy('purchases.id', 'DESC')
                            ->groupBy('purchase_details.purchase_id')
                            ->get(); 
            }else{
                $compras = Purchase::PaymentMethod($request->get('forma_pago'))
                            ->join('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
                            ->select('purchases.*')
                            ->where('purchase_details.podcast_id', '=', $content_type[1])
                            ->orderBy('purchases.id', 'DESC')
                            ->groupBy('purchase_details.purchase_id')
                            ->get();
            }
        }else{
            if ($request->get('instructor') != ""){
                $comprasCursos = Purchase::PaymentMethod($request->get('forma_pago'))
                                    ->join('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
                                    ->join('courses', 'purchase_details.course_id', '=', 'courses.id')
                                    ->select('purchases.*')
                                    ->where('courses.user_id', '=', $request->get('instructor'))
                                    ->orderBy('purchases.id', 'DESC')
                                    ->groupBy('purchase_details.purchase_id')
                                    ->get(); 

                $comprasCertificaciones = Purchase::PaymentMethod($request->get('forma_pago'))
                                            ->join('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
                                            ->join('certifications', 'purchase_details.certification_id', '=', 'certifications.id')
                                            ->select('purchases.*')
                                            ->where('certifications.user_id', '=', $request->get('instructor'))
                                            ->orderBy('purchases.id', 'DESC')
                                            ->groupBy('purchase_details.purchase_id')
                                            ->get(); 

                $comprasPodcasts = Purchase::PaymentMethod($request->get('forma_pago'))
                                        ->join('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
                                        ->join('podcasts', 'purchase_details.podcast_id', '=', 'podcasts.id')
                                        ->select('purchases.*')
                                        ->where('podcasts.user_id', '=', $request->get('instructor'))
                                        ->orderBy('purchases.id', 'DESC')
                                        ->groupBy('purchase_details.purchase_id')
                                        ->get(); 
                $comprasArray = [];
                $compras = collect();
                foreach ($comprasCursos as $compraCurso) {
                    if (!in_array($compraCurso->id, $comprasArray)){
                        $compras->push($compraCurso);
                        array_push($comprasArray, $compraCurso->id);
                    }
                }
                foreach ($comprasCertificaciones as $compraCertificacion) {
                    if (!in_array($compraCertificacion->id, $comprasArray)){
                        $compras->push($compraCertificacion);
                        array_push($comprasArray, $compraCertificacion->id);
                    }
                }
                foreach ($comprasPodcasts as $compraPodcast) {
                    if (!in_array($compraPodcast->id, $comprasArray)){
                        $compras->push($compraPodcast);
                        array_push($comprasArray, $compraPodcast->id);
                    }
                }

                $compras = $compras->sortByDesc('id');
            }else{
                $compras = Purchase::PaymentMethod($request->get('forma_pago'))
                            ->orderBy('purchases.id', 'DESC')
                            ->get();
            }
        }
       

        $instructores = DB::table('users')
                            ->select('id', 'names', 'last_names', 'email')
                            ->where('role_id', '=', 2)
                            ->get();

        $cursos = DB::table('courses')
                    ->select('id', 'title')
                    ->where('status', '=', 2)
                    ->get();

        $certificaciones = DB::table('certifications')
                            ->select('id', 'title')
                            ->where('status', '=', 2)
                            ->get();

        $podcasts = DB::table('podcasts')
                        ->select('id', 'title')
                        ->where('status', '=', 2)
                        ->get();

        $contenido = collect();
        foreach ($cursos as $curso) {
            $curso->identificador = 'Curso-'.$curso->id;
            $curso->content_type = 'T-Course';
            $contenido->push($curso);
        }
        foreach ($certificaciones as $certificacion) {
            $certificacion->identificador = 'Certificacion-'.$certificacion->id;
            $certificacion->content_type = 'T-Mentoring';
            $contenido->push($certificacion);
        }
        foreach ($podcasts as $podcast) {
            $podcast->identificador = 'Podcast-'.$podcast->id;
            $podcast->content_type = 'T-Book';
            $contenido->push($podcast);
        }

        $contenido = $contenido->sortBy('title');

        $forma_pago = $request->get('forma_pago');
        $content = $request->get('contenido');
        $instru = $request->get('instructor');

        return view('admins.orders.index')->with(compact('compras', 'instructores', 'contenido', 'forma_pago', 'content', 'instru'));
    }

    //*** Admin / Pedidos / Ver Factura ***//
    public function show_bill($order_id){
        $datosPago = Purchase::where('id', '=', $order_id)
                        ->withCount('details')
                        ->first();

        $cantItems = $datosPago->details_count;
        $items = $datosPago->details;

        if (!is_null($datosPago->coupon_id)){
            $porcentajePagado = 100 - $datosPago->coupon->discount;
            $montoDescontado = ( ($datosPago->amount * $datosPago->coupon->discount) / $porcentajePagado);
            $datosPago->original_amount = $datosPago->amount + $montoDescontado;
            $datosPago->discounted_amount = $montoDescontado;
        }

        return view('admins.orders.showBill')->with(compact('datosPago', 'cantItems', 'items'));
    }

  	//*** Admin / Pedidos / Generar Órden ***//
  	public function create(){
  		$estudiantes = DB::table('users')
  						->select('id', 'email', 'names', 'last_names')
  						->where('role_id', '=', 1)
  						->orderBy('email', 'ASC')
  						->get();

  		$cursos = DB::table('courses')
  					->select('id', 'title')
  					->where('status', '=', 2)
  					->orderBy('title', 'ASC')
  					->get();

  		return view('admins.orders.create')->with(compact('estudiantes', 'cursos'));
  	}

  	//*** Admin / Pedidos / Generar Orden / Guardar ***//
  	public function store(Request $request){
  		$orden = new Purchase($request->all());
  		$orden->original_amount = $request->amount;
  		$orden->payment_method = 'MercadoPago';
  		$orden->status = 0;
        $orden->manual = 1;
  		$orden->save();

  		if (!is_null($request->cursos)){
            foreach ($request->cursos as $curso){
            	$infoCurso = DB::table('courses')
            					->select('price')
            					->where('id', '=', $curso)
            					->first();

                $producto = new PendingProduct();
                $producto->purchase_id = $orden->id;
                $producto->course_id = $curso;
                $producto->original_amount = $infoCurso->price;
                $producto->amount = $infoCurso->price;
                $producto->instructor_code = $request->instructor_code_discount;
                $producto->save();
            }
        }else{
        	return redirect('admins/orders/create')->with('msj-erroneo', 'No se ha seleccionado ningún curso.');
        }

        return redirect('admins/orders')->with('msj-exitoso', 'La orden ha sido generada con éxito.');
  	}
}
