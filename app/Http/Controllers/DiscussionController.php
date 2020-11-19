<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str as Str;
use App\Models\Discussion; use App\Models\Comment; use App\Models\Course; use App\Models\Certification; use App\Models\Podcast;
use Auth; use DB;

class DiscussionController extends Controller{

    //Instructor - Foros
    public function index(){
        $cursos = Course::withCount('discussions')
                    ->where('user_id', '=', Auth::user()->id)
                    ->get();

        $cantCursos = $cursos->count();

        $certificaciones = Certification::withCount('discussions')
                            ->where('user_id', '=', Auth::user()->id)
                            ->get();

        $cantCertificaciones = $certificaciones->count();

        $podcasts = Podcast::withCount('discussions')
                    ->where('user_id', '=', Auth::user()->id)
                    ->get();

        $cantPodcasts = $podcasts->count();

        return view('instructors.discussions.index')->with(compact('cursos', 'cantCursos', 'certificaciones', 'cantCertificaciones', 'podcasts', 'cantPodcasts'));
    }

    //Estudiante / Crear nueva discusión
    public function store(Request $request){
        $messages = [
            'comment.required' => 'Es necesario que especifique una descripción para la discusión.'
        ];

        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ], $messages);

        if ($validator->fails()) {
            if ($request->tipo == 'course'){
                $slug = DB::table('courses')
                            ->select('slug')
                            ->where('id', '=', $request->id)
                            ->first();
            }else if ($request->tipo == 'certification'){
                $slug = DB::table('certifications')
                            ->select('slug')
                            ->where('id', '=', $request->id)
                            ->first();
            }else if ($request->tipo == 'podcast'){
                $slug = DB::table('podcasts')
                            ->select('slug')
                            ->where('id', '=', $request->id)
                            ->first();
            }

            return redirect('students/discussions/group/'.$request->tipo.'/'.$slug->slug.'/'.$request->id)
                ->withErrors($validator)
                ->withInput();
        }

        $discusion = new Discussion($request->all());
        $discusion->user_id = Auth::user()->id;
        $discusion->slug = Str::slug($discusion->title);
        $discusion->date = date('Y-m-d');
        $discusion->save();
        
        if (!is_null($discusion->course_id)){
            $contenido = 'Curso';
            $datosContenido = DB::table('courses')
                                ->select('user_id', 'title', 'slug')
                                ->where('id', '=', $discusion->course_id)
                                ->first();
        }else if (!is_null($discusion->certification_id)){
            $contenido = 'Certificación';
            $datosContenido = DB::table('certifications')
                                ->select('user_id', 'title', 'slug')
                                ->where('id', '=', $discusion->certification_id)
                                ->first();
        }else if (!is_null($discusion->podcast_id)){
            $podcast = 'Podcast';
            $datosContenido = DB::table('podcasts')
                                ->select('user_id', 'title', 'slug')
                                ->where('id', '=', $discusion->podcast_id)
                                ->first();
        }

        $notificacion = new NotificationController();
        $notificacion->store($datosContenido->user_id, 'Nueva Pregunta', 'Tiene una nueva discusión en su '.$contenido.' <b>'.$datosContenido->title.'</b>', 'fa fa-comment', 'instructors/discussions/show/'.$datosContenido->slug.'/'.$discusion->id);
        
        return redirect('students/discussions/group/'.$request->tipo.'/'.$datosContenido->slug.'/'.$request->id)->with('msj-exitoso', 'Su discusión ha sido publicada con éxito.');
    }
    
    //Estudiante - Instructor / Discusiones de un curso, certificacion o podcast determinado
    public function discussions_group($tipo, $slug, $id, Request $request){
        if ($tipo == 'course'){
            $discusiones = Discussion::where('course_id', '=', $id)
                                ->withCount('comments')
                                ->orderBy('created_at', 'DESC')
                                ->paginate(20);
        }else if ($tipo == 'certification'){
            $discusiones = Discussion::where('certification_id', '=', $id)
                                ->withCount('comments')
                                ->orderBy('created_at', 'DESC')
                                ->paginate(20);
        }else{
            $discusiones = Discussion::where('podcast_id', '=', $id)
                                ->withCount('comments')
                                ->orderBy('created_at', 'DESC')
                                ->paginate(20);
        }
        
        $cantDiscusiones = 0;
        foreach ($discusiones as $discusion){
            $cantDiscusiones++;
        }

        if (Auth::user()->role_id == 1){
            return view('students.discussions.index')->with(compact('discusiones', 'tipo', 'id', 'cantDiscusiones'));
        }else if(Auth::user()->role_id == 2){
            return view('instructors.discussions.group')->with(compact('discusiones', 'cantDiscusiones', 'tipo'));
        }
    }

    //Estudiante - Instructor / Mostrar Discusión Con Sus Comentarios
    public function show($slug, $id){
    	$discusion = Discussion::where('id', '=', $id)
                        ->withCount('comments')
                        ->with(['comments' => function ($query){
                            $query->orderBy('id', 'ASC');
                        }])->first();

        $discusion->status = 1;
        $discusion->save();

        foreach ($discusion->comments as $comentario){
            DB::table('comments')
                ->where('id', '=', $comentario->id)
                ->update(['status' => 1]);
        }

        if (!is_null($discusion->course_id)){
            $otrasDiscusiones = Discussion::where('course_id', '=', $discusion->course_id)
                                    ->withCount('comments')
                                    ->orderBy('id', 'DESC')
                                    ->take(5)
                                    ->get();
        }else if (!is_null($discusion->certification_id)){
            $otrasDiscusiones = Discussion::where('certification_id', '=', $discusion->certification_id)
                                    ->withCount('comments')
                                    ->orderBy('id', 'DESC')
                                    ->take(5)
                                    ->get();
        }else if (!is_null($discusion->podcast_id)){
            $otrasDiscusiones = Discussion::where('podcast_id', '=', $discusion->podcast_id)
                                    ->withCount('comments')
                                    ->orderBy('id', 'DESC')
                                    ->take(5)
                                    ->get();
        }

        if (Auth::user()->role_id == 1){
            return view('students.discussions.show')->with(compact('discusion', 'otrasDiscusiones'));
        }else if (Auth::user()->role_id == 2){
            return view('instructors.discussions.show')->with(compact('discusion', 'otrasDiscusiones'));
        } 
    }

    //Estudiante - Instructor / Guardar Comentario sobre una discusíón
    public function store_comment(Request $request){
        $comentario = new Comment($request->all());
        $comentario->user_id = Auth::user()->id;
        $comentario->date = date('Y-m-d');
        $comentario->save();
        
        $datosDiscusion = Discussion::find($comentario->discussion_id);
        if (!is_null($datosDiscusion->course_id)){
            $instructor = $datosDiscusion->course->user_id;
            $slug = $datosDiscusion->course->slug;
        }else if (!is_null($datosDiscusion->certification_id)){
            $instructor = $datosDiscusion->certification->user_id;
            $slug = $datosDiscusion->certification->slug;
        }else if (!is_null($datosDiscusion->podcast_id)){
            $instructor = $datosDiscusion->podcast->user_id;
            $slug = $datosDiscusion->podcast->slug;
        }

        $notificacion = new NotificationController();

        if (Auth::user()->role_id == 1){
            //*** Notificar al Instructor ***//
            $notificacion->store($instructor, 'Nuevo Comentario', 'Tiene un nuevo comentario en su discusión <b>'.$datosDiscusion->title.'</b>', 'fa fa-comment', 'instructors/discussions/show/'.$slug.'/'.$datosDiscusion->id);
        }
        
        if (Auth::user()->id != $datosDiscusion->user_id){
            //*** Notificar al estudiante que creó la discusión ***//
            $notificacion->store($datosDiscusion->user_id, 'Nuevo Comentario', 'Hay un nuevo comentario en la discusión que creaste <b>('.$datosDiscusion->title.')</b>', 'fa fa-comment', 'students/discussions/show/'.$slug.'/'.$datosDiscusion->id);
        }
       
        //*** Notificar a los estudiantes que hayan comentado la discusión ***//
        $estudiantes = DB::table('comments')
                        ->select('user_id')
                        ->where('discussion_id', '=', $datosDiscusion->id)
                        ->where('user_id', '<>', $datosDiscusion->user_id)
                        ->where('user_id', '<>', Auth::user()->id)
                        ->where('user_id', '<>', $instructor)
                        ->get();

        foreach ($estudiantes as $estudiante){
             $notificacion->store($estudiante->user_id, 'Nuevo Comentario', 'Hay un nuevo comentario en una discusión en la que participas <b>('.$datosDiscusion->title.')</b>', 'fa fa-comment', 'students/discussions/show/'.$slug.'/'.$datosDiscusion->id);
        }

        if (Auth::user()->role_id == 1){
            return redirect('students/discussions/show/'.$slug.'/'.$request->discussion_id)->with('msj-exitoso', 'Su comentario ha sido publicado con éxito.');
        }else if (Auth::user()->role_id == 2){
             return redirect('instructors/discussions/show/'.$slug.'/'.$request->discussion_id)->with('msj-exitoso', 'Su comentario ha sido publicado con éxito.');
        }
    }
}
