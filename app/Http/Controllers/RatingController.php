<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating; use App\Models\Course; use App\Models\Certification; use App\Models\Podcast;
use Auth; use DB;

class RatingController extends Controller
{
    //**** Instructor / Valoraciones ****//
    public function index($content_type, $slug, $id){
        if ($content_type == 'course'){
            $content = Course::where('id', '=', $id)
                        ->withCount(['ratings', 'ratings as promedio' => function ($query){
                                        $query->select(DB::raw('avg(points)'));
                        }])->first();
        }else if ($content_type == 'certification'){
            $content = Certification::where('id', '=', $id)
                        ->withCount(['ratings', 'ratings as promedio' => function ($query){
                                        $query->select(DB::raw('avg(points)'));
                        }])->first();
        }else{
            $content = Podcast::where('id', '=', $id)
                        ->withCount(['ratings', 'ratings as promedio' => function ($query){
                                        $query->select(DB::raw('avg(points)'));
                        }])->first();
        }
        
        return view('instructors.ratings.index')->with(compact('content', 'content_type'));
    }

    public function store(Request $request){
        $valoracion = new Rating($request->all());
        if (Auth::user()->role_id == 1){
            $valoracion->user_id = Auth::user()->id;
        }
        $valoracion->date = date('Y-m-d');
        $valoracion->save();

        if (Auth::user()->role_id == 1){
            $notificacion = new NotificationController();
            if (!is_null($request->course_id)){
                //**** Notificar al Instructor ***//
                $datosCurso = DB::table('courses')
                                ->select('user_id', 'title', 'slug')
                                ->where('id', '=', $request->course_id)
                                ->first();

                $notificacion->store($datosCurso->user_id, 'Nueva Valoración', 'Ha recibido una nueva valoración en su T-Course <b>'.$datosCurso->title.'</b>', 'fas fa-star', 'instructors/ratings/show/course/'.$datosCurso->slug.'/'.$request->course_id);

                return redirect('students/t-courses/resume/'.$datosCurso->slug.'/'.$request->course_id)->with('msj-exitoso-rating', 'Su valoración ha sido publicada con éxito.');
            }else if (!is_null($request->podcast_id)){
                //**** Notificar al Instructor ***//
                $datosPodcast = DB::table('podcasts')
                                    ->select('user_id', 'title', 'slug')
                                    ->where('id', '=', $request->podcast_id)
                                    ->first();

                $notificacion->store($datosPodcast->user_id, 'Nueva Valoración', 'Ha recibido una nueva valoración en su T-Book <b>'.$datosPodcast->title.'</b>', 'fas fa-star', 'instructors/ratings/show/podcast/'.$request->podcast_id);

                return redirect('students/t-books/resume/'.$datosPodcast->slug.'/'.$request->podcast_id)->with('msj-exitoso-rating', 'Su valoración ha sido publicada con éxito.');
            }
        }else{
            return redirect('admins/t-courses/reports/ratings')->with('msj-exitoso', 'La valoración del curso se ha cargado con éxito');
        }
        
    }

    public function update(Request $request){
        $valoracion = Rating::find($request->id);
        $valoracion->fill($request->all());
        $valoracion->save();

        if (!is_null($valoracion->course_id)){
            return redirect('students/t-courses/resume/'.$valoracion->course_id)->with('msj-exitoso-rating', 'Su valoración ha sido modificada con éxito.');
        }else if (!is_null($valoracion->certification_id)){
            return redirect('students/t-mentorings/resume/'.$valoracion->certification_id)->with('msj-exitoso-rating', 'Su valoración ha sido modificada con éxito.');
        }else{
            return redirect('students/t-books/resume/'.$valoracion->podcast_id)->with('msj-exitoso-rating', 'Su valoración ha sido modificada con éxito.');
        }
    }
}
