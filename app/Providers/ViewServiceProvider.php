<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class ViewServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot(){
    	View::composer(['students.*', 'instructors.*'], function ($view) {
	        $contenido = \App\Models\User::where('id', '=', \Auth::user()->id)
	        				->withCount(['courses_students', 'certifications_students', 'podcasts_students',
	        							 'courses_students as completed_courses_count' => function ($query){
				                				$query->where('progress', '=', 100);
				            			  },
				            			  'certifications_students as completed_certifications_count' => function ($query){
								                $query->where('progress', '=', 100);
								          }
				            		])->first(); 

	        foreach ($contenido->courses_students as $curso){
	            $instructor = \App\Models\User::where('id', '=', $curso->user_id)
	                        	->select('names', 'last_names')
	                        	->first();

	            $curso->instructor = $instructor->names." ".$instructor->last_names;  
	        }

	        foreach ($contenido->certifications_students as $certificacion){
	            $instructor = \App\Models\User::where('id', '=', $certificacion->user_id)
	                        	->select('names', 'last_names')
	                        	->first();

	            $certificacion->instructor = $instructor->names." ".$instructor->last_names;  
	        }

	        foreach ($contenido->podcasts_students as $podcast){
	            $instructor = \App\Models\User::where('id', '=', $podcast->user_id)
	                        	->select('names', 'last_names')
	                        	->first();

	            $podcast->instructor = $instructor->names." ".$instructor->last_names;  
	        }

	        $total = $contenido->courses_students_count + $contenido->certifications_students_count + $contenido->podcasts_students_count;
	        $totalCompletados = $contenido->completed_courses_count + $contenido->completed_certifications_count;

			$itemsCarrito = DB::table('shopping_cart')->where('user_id', '=', Auth::user()->id)->count();			
			$membership = DB::table('purchases')->where('user_id', '=', Auth::user()->id)->count();
	       
	        $view->with(compact('contenido', 'total', 'totalCompletados', 'itemsCarrito','membership'));
	    });

	    View::composer(['students.*', 'instructors.*', 'partners.*', 'admins.*'], function ($view) {
			//$notificationDate = [];
			$difference = [];
			$count = 0;

			$notificaciones = \App\Models\Notification::where('user_id', '=', Auth::user()->id)
	    							->orderBy('created_at', 'DESC')
	    							->take(20)
	    							->get();

	    	$cantNotificaciones = \App\Models\Notification::where('user_id', '=', Auth::user()->id)
	    								->where('status', '=', 0)
										->count();

			foreach ($notificaciones as $key => $notification) { 
				$notificationDate = Carbon::parse($notification['date']);
				$currentDate = Carbon::parse(date('Y-m-d'));
				$difference[$key] = $currentDate->diffInDays($notificationDate);				
			}							

			$categorias = \App\Models\Category::with('subcategories')->orderBy('id', 'ASC')->get();
	    	$view->with(compact('notificaciones', 'cantNotificaciones','difference', 'categorias'));
	    });

	    View::composer('landing.*', function ($view) {
			$categorias = \App\Models\Category::with('subcategories')->orderBy('id', 'ASC')->get();

			if (!Auth::guest()){
				$contenido = \App\Models\User::where('id', '=', \Auth::user()->id)
	        				->withCount(['courses_students', 'certifications_students', 'podcasts_students',
	        							 'courses_students as completed_courses_count' => function ($query){
				                				$query->where('progress', '=', 100);
				            			  },
				            			  'certifications_students as completed_certifications_count' => function ($query){
								                $query->where('progress', '=', 100);
								          }
				            		])->first(); 

		        foreach ($contenido->courses_students as $curso){
		            $instructor = \App\Models\User::where('id', '=', $curso->user_id)
		                        	->select('names', 'last_names')
		                        	->first();

		            $curso->instructor = $instructor->names." ".$instructor->last_names;  
		        }

		        foreach ($contenido->certifications_students as $certificacion){
		            $instructor = \App\Models\User::where('id', '=', $certificacion->user_id)
		                        	->select('names', 'last_names')
		                        	->first();

		            $certificacion->instructor = $instructor->names." ".$instructor->last_names;  
		        }

		        foreach ($contenido->podcasts_students as $podcast){
		            $instructor = \App\Models\User::where('id', '=', $podcast->user_id)
		                        	->select('names', 'last_names')
		                        	->first();

		            $podcast->instructor = $instructor->names." ".$instructor->last_names;  
		        }

		        $total = $contenido->courses_students_count + $contenido->certifications_students_count + $contenido->podcasts_students_count;
		        $totalCompletados = $contenido->completed_courses_count + $contenido->completed_certifications_count;

				$itemsCarrito = DB::table('shopping_cart')->where('user_id', '=', Auth::user()->id)->count();			
				$membership = DB::table('purchases')->where('user_id', '=', Auth::user()->id)->count();

				$difference = [];
				$count = 0;

				$notificaciones = \App\Models\Notification::where('user_id', '=', Auth::user()->id)
		    							->orderBy('created_at', 'DESC')
		    							->get();

		    	$cantNotificaciones = \App\Models\Notification::where('user_id', '=', Auth::user()->id)
		    								->where('status', '=', 0)
											->count();

				foreach ($notificaciones as $key => $notification) { 
					$notificationDate = Carbon::parse($notification['date']);
					$currentDate = Carbon::parse(date('Y-m-d'));
					$difference[$key] = $currentDate->diffInDays($notificationDate);				
				}

				$view->with(compact('categorias', 'contenido', 'total', 'totalCompletados', 'itemsCarrito', 'membership', 'notificaciones', 'cantNotificaciones', 'difference'));
			}else{
				$view->with(compact('categorias'));
			}
	    });
	    View::composer('*', function ($view) {
	    	$url = explode("www", \Request::url());
	        if (count($url) > 1){
	            $www = 1;
	        }else{
	            $www = 0;
	        }

	        $view->with(compact('www'));
	    });
    }
}