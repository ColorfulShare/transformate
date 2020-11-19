<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gift; 
use App\Models\PurchaseDetail; 
use App\Models\EventSubscription;
use DB; use Auth;

class GiftController extends Controller
{
    public function index(){
		if (Auth::user()->role_id == 1){
			$regalosComprados = Gift::where('buyer_id', '=', Auth::user()->id)
									->orderBy('id', 'DESC')
									->get();
			
			$regalosObtenidos = Gift::where('user_id', '=', Auth::user()->id)
									->orderBy('id', 'DESC')
									->get();

			return view('students.gifts')->with(compact('regalosComprados', 'regalosObtenidos'));
		}else if (Auth::user()->role_id == 3){
			$regalos = Gift::where('admin_id', '<>', NULL)
    					->orderBy('id', 'DESC')
    					->get();

			$cursos = DB::table('courses')
						->select('id', 'title')
						->where('status', '=', 2)
						->orderBy('title', 'ASC')
						->get();

			$podcasts = DB::table('podcasts')
							->select('id', 'title')
							->where('status', '=', 2)
							->orderBy('title', 'ASC')
							->get();
			
			$eventos = DB::table('events')
						->select('id', 'title')
						->where('status', '=', 1)
						->orderBy('title', 'ASC')
						->get();

			return view('admins.gifts.index')->with(compact('regalos', 'cursos', 'podcasts', 'eventos'));
		}
	}
	
	public function redeem_gift_code(Request $request){
		$regalo = Gift::where('code', '=', strtoupper($request->code))
					->first();
		
		if (is_null($regalo)){
			return redirect('students/my-content')->with('msj-erroneo', 'El código de regalo ingresado es incorrecto.');
		}else{
			if ($regalo->status == 1){
				return redirect('students/my-content')->with('msj-erroneo', 'El código de regalo ingresado ya fue aplicado.');
			}else{
				if (!is_null($regalo->course_id)){
					DB::table('courses_students')->insert(
						['user_id' => Auth::user()->id, 'course_id' => $regalo->course_id, 'progress' => 0, 'start_date' => date('Y-m-d')]
					);

					$regalo->user_id = Auth::user()->id;
					$regalo->status = 1;
					$regalo->applied_at = date('Y-m-d H:i:s');
					$regalo->save();

					return redirect('students/t-courses/resume/'.$regalo->course->slug.'/'.$regalo->course_id)->with('msj-exitoso', 'Listo');
				}else if (!is_null($regalo->podcast_id)){
					DB::table('podcasts_students')->insert(
						['user_id' => Auth::user()->id, 'podcast_id' => $regalo->podcast_id]
					);

					$regalo->user_id = Auth::user()->id;
					$regalo->status = 1;
					$regalo->applied_at = date('Y-m-d H:i:s');
					$regalo->save();

					return redirect('students/t-books/resume/'.$regalo->podcast->slug.'/'.$regalo->podcast_id)->with('msj-exitoso', 'Listo');
				}
			}
		}
	}

    public function store(Request $request){
    	$usuario = DB::table('users')
    				->select('id')
    				->where('email', '=', $request->email)
    				->where('role_id', '=', 1)
    				->first();

    	if (!is_null($usuario)){
    		if ($request->type == 'Curso'){
    			$check = DB::table('courses_students')
    						->where('user_id', '=', $usuario->id)
    						->where('course_id', '=', $request->course_id)
    						->first();
    		}else if ($request->type== 'Podcast'){
    			$check = DB::table('podcasts_students')
    						->where('user_id', '=', $usuario->id)
    						->where('podcast_id', '=', $request->podcast_id)
    						->first();
    		}else if ($request->type == 'Evento'){
    			$check = DB::table('event_subscriptions')
    						->where('email', '=', $request->email)
    						->where('event_id', '=', $request->event_id)
    						->first();
    		}
    		
    		if (is_null($check)){
    			$regalo = new Gift($request->all());
		    	$regalo->user_id = $usuario->id;
				$regalo->admin_id = Auth::user()->id;
			    $regalo->status = 1;
			    $regalo->checked = 0;
			    $regalo->save();

			    if (!is_null($regalo->course_id)){
				    DB::table('courses_students')->insert(
	                    ['user_id' => $usuario->id, 'course_id' => $regalo->course_id, 'progress' => 0, 'start_date' => date('Y-m-d')]
	                );
	            }else if (!is_null($regalo->podcast_id)){
	            	DB::table('podcasts_students')->insert(
	                    ['user_id' => $usuario->id, 'podcast_id' => $regalo->podcast_id,]
	                );
	            }else if (!is_null($regalo->event_id)){
					$suscripcion = new EventSubscription();
					$suscripcion->event_id = $request->event_id;
					$suscripcion->email = $request->email;
					$suscripcion->gift = 1;
					$suscripcion->gift_status = 1;
					$suscripcion->gift_admin = 1;
					$suscripcion->status = 1;
					$suscripcion->save();
	            }

			    return redirect('admins/gifts')->with('msj-exitoso', 'El regalo ha sido asignado al usuario con éxito.');
    		}else{
    			return redirect('admins/gifts')->with('msj-erroneo', 'El estudiante ya posee este contenido.');
    		}
    	}else{
    		return redirect('admins/gifts')->with('msj-erroneo', 'El correo ingresado no pertenece a ningún estudiante en la plataforma.');
    	}
    }

    public function delete($id){
    	$regalo = Gift::find($id);

    	if (!is_null($regalo->course_id)){
    		DB::table('courses_students')
    			->where('user_id', '=', $regalo->user_id)
    			->where('course_id', '=', $regalo->course_id)
    			->delete();
    	}else if (!is_null($regalo->podcast_id)){
    		DB::table('podcasts_students')
    			->where('user_id', '=', $regalo->user_id)
    			->where('podcast_id', '=', $regalo->podcast_id)
    			->delete();
    	}else if (!is_null($regalo->event_id)){
    		DB::table('event_subscriptions')
    			->where('email', '=', $regalo->user->email)
				->where('event_id', '=', $regalo->event_id)
				->where('gift_admin', '=', 1)
    			->delete();
    	}

    	$regalo->delete();

    	return redirect('admins/gifts')->with('msj-exitoso', 'El regalo ha sido eliminado con éxito.');
	}
	
	public function user_purchases(){
		$regalos = Gift::where('buyer_id', '<>', NULL)
						->orderBy('id', 'DESC')
						->get();

		return view('admins.gifts.userPurchases')->with(compact('regalos'));
	}

	public function events_inscriptions(){
		$regalos = EventSubscription::where('gift', '=', 1)
						->where('gift_admin', '=', 0)
						->orderBy('id', 'DESC')
						->get();

		return view('admins.gifts.eventsInscriptions')->with(compact('regalos'));
	}
}
