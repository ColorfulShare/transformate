<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str as Str;
use App\Models\Course; use App\Models\Certification; use App\Models\Podcast; use App\Models\Module; 
use App\Models\Lesson; use App\Models\Category; use App\Models\Test; use App\Models\Question;
use App\Models\PurchaseDetail; use App\Models\User; use App\Models\Rating; use App\Models\ResourceFile;
use App\Models\Comment;
use DB; use Auth; use Storage;

class CourseController extends Controller
{   
    //**** Instructor / T-Courses
    public function index(){
        //Vista de Instructor
        $cursos = Course::where('user_id', '=', Auth::user()->id)
                    ->orderBy('created_at', 'DESC')
                    ->paginate(8);

        $cursosConCorrecciones = Course::where('user_id', '=', Auth::user()->id)
                                    ->where('status', '=', 4)
                                        ->orderBy('reviewed_at', 'DESC')
                                        ->get();

            $cantCursosConCorrecciones = $cursosConCorrecciones->count();

            $nuevasDiscusiones = collect();

            $instructor = User::where('id', '=', Auth::user()->id)
                            ->with(['courses',
                                'courses.discussions' => function ($query){
                                    $query->where('status', '=', 0);
                                }
                            ])->first();

            foreach ($instructor->courses as $curso){
                foreach ($curso->discussions as $discusion){
                    $nuevasDiscusiones->push($discusion);
                }
            }

            $cantNuevasDiscusiones = $nuevasDiscusiones->count();
            $nuevasDiscusiones = $nuevasDiscusiones->sortByDesc('created_at');


            $nuevosComentarios = Comment::join('discussions', 'comments.discussion_id', '=', 'discussions.id')
                                    ->join('courses', 'discussions.course_id', '=', 'courses.id')
                                    ->join('users', 'discussions.user_id', '=', 'users.id')
                                    ->where('comments.status', '=', 0)
                                    ->where('discussions.status', '=', 1)
                                    ->where('courses.user_id', '=', Auth::user()->id)
                                    ->select('users.avatar', 'users.names', 'users.last_names', 'discussions.slug', 'discussions.title', 'comments.discussion_id', DB::raw('COUNT(*) as total_comentarios'))
                                    ->groupBy('comments.discussion_id')
                                    ->get();

            $cantNuevosComentarios = $nuevosComentarios->count();

            return view('instructors.courses.index')->with(compact('cursos', 'cursosConCorrecciones', 'cantCursosConCorrecciones', 'nuevasDiscusiones', 'cantNuevasDiscusiones',  'nuevosComentarios', 'cantNuevosComentarios'));
        
    }

    public function steps(){
        return view('instructors.courses.steps');
    }

    //**** Instructor / T-Courses / Nuevo T-Course
    public function create(){
        $categorias = DB::table('categories')
                        ->select('id', 'title', 'icon')
                        ->orderBy('id', 'ASC')
                        ->get();

        $etiquetas = DB::table('tags')
                        ->orderBy('tag', 'ASC')
                        ->get();

        return view('instructors.courses.create')->with(compact('categorias', 'etiquetas'));
    }

    //**** Instructor / T-Courses / Nuevo T-Course / Guardar
    public function store(Request $request){
        $messages = [
            'title.required' => 'El campo Título es requerido.',
            'subtitle.required' => 'El campo Subtítulo es requerido.',
            'category_id.required' => 'El campo Categoría es requerido.',
            'subcategory_id.required' => 'El campo SubCategoría es requerido.',
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'subtitle' => 'required|max:100',
            'category_id' => 'required',
            'subcategory_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect('instructors/t-courses/create')
                ->withErrors($validator)
                ->withInput();
        }

        $curso = new Course($request->all());
        $curso->user_id = Auth::user()->id;
        $curso->slug = Str::slug($curso->title);
        if (is_null($request->price)){
            $curso->price = 0;
        }else{
            $curso->price = $request->price;
        }

        $curso->save();

        if (!is_null($request->tags)){
            foreach ($request->tags as $tag){
                DB::table('courses_tags')->insert(
                    ['course_id' => $curso->id, 'tag_id' => $tag]
                );
            }
        }
        
        $modulo = new Module();
        $modulo->course_id = $curso->id;
        $modulo->priority_order = 1;
        $modulo->title = 'Módulo 1';
        $modulo->save();

        $leccion = new Lesson();
        $leccion->module_id = $modulo->id;
        $leccion->priority_order = 1;
        $leccion->title = 'Lección 1';
        $leccion->save();

        return redirect('instructors/t-courses/temary/'.$curso->slug.'/'.$curso->id)->with('msj-exitoso', 'El curso ha sido creado con éxito');
    }

    //**** Instructor / T-Courses / Cargar Temario de un Curso
    public function temary($slug, $id, Request $request){
        $curso = Course::where('id', '=', $id)
                    ->with([
                        'modules' => function($query){
                            $query->orderBy('priority_order', 'ASC');
                        }, 
                        'modules.lessons' => function ($query2){
                            $query2->orderBy('priority_order', 'ASC');
                        },
                        'modules.lessons.resource_files'])->first();

        return view('instructors.courses.temary')->with(compact('curso'));
    }

    //**** Invitado - Estudiante - Instructor /  T-Courses / Ver T-Course ***//
    //**** Admin /  T-Courses / Ver - Editar T-Course ***//
    public function show($slug, $id){
        $curso = Course::where('id', '=', $id)
                        ->with('modules', 'modules.lessons', 'tags')
                        ->withCount(['students', 'modules', 
                                'ratings' => function($query){
                                     $query->orderBy('created_at', 'DESC');
                                }, 
                                'ratings as promedio' => function ($query2){
                                    $query2->select(DB::raw('avg(points)'));
                                }
                            ])->first();

        $curso->avg = explode('.', $curso->promedio);

        $cantLecciones = 0;
        $cantRecursos = 0;
        foreach ($curso->modules as $modulo) {
            $leccionesMod = DB::table('lessons')
                                ->where('module_id', '=', $modulo->id)
                                ->select('id')
                                ->get();

            foreach ($leccionesMod as $leccion){
                $cantLecciones++;

                $recursosLeccion = DB::table('resource_files')
                                    ->where('lesson_id', '=', $leccion->id)
                                    ->count();

                $cantRecursos += $recursosLeccion;
            }
        }

        $curso->lessons_count = $cantLecciones;
        $curso->resource_files_count = $cantRecursos;

        $instructor = User::where('id', '=', $curso->user_id)
                        ->first();

        if (Auth::guest()){
            return view('landing.showCourse')->with(compact('curso', 'instructor')); 
        }else if (Auth::user()->role_id == 1){
            //Vista de Estudiantes  
            $agregado = Auth::user()->courses_students()->where('course_id', '=', $id)->count();

            if ($agregado == 0){
                return view('landing.showCourse')->with(compact('curso', 'instructor'));
            }else{
               return redirect('students/t-courses/resume/'.$slug.'/'.$id);
            } 
        }else if (Auth::user()->role_id == 2){
            return view('landing.showCourse')->with(compact('curso', 'instructor'));
        }else if (Auth::user()->role_id == 3){
            $curso = Course::find($id);

            $etiquetasActivas = [];
            foreach ($curso->tags as $etiq){
                array_push($etiquetasActivas, $etiq->id);
            }

            $etiquetas = DB::table('tags')
                            ->orderBy('tag', 'ASC')
                            ->get();

            $categorias = DB::table('categories')
                            ->select('id', 'title')
                            ->orderBy('id', 'ASC')
                            ->get();

            $subcategorias = DB::table('subcategories')
                                ->select('id', 'title')
                                ->where('category_id', '=', $curso->category_id)
                                ->orderBy('id', 'ASC')
                                ->get();

            return view('admins.courses.show')->with(compact('curso', 'categorias', 'subcategorias', 'etiquetas', 'etiquetasActivas'));
        }
    }

    //**** Estudiante / Ver Curso / Tomar Curso (Guatuito) ****//
    public function add($curso, $membresia = NULL){
        DB::table('courses_students')
            ->insert(['course_id' => $curso, 'user_id' => Auth::user()->id, 'progress' => 0, 'start_date' => date('Y-m-d')]);

        if (!is_null($membresia)){
            DB::table('users')
                ->where('id', '=', Auth::user()->id)
                ->update(['membership_courses' => Auth::user()->membership_courses+1]);
        }
        return redirect('students/my-content')->with('msj-exitoso', 'El curso ha sido añadido a su lista con éxito');
    }

    //**** Instructor /  T-Courses / Editar T-Course ***//
    public function edit(Request $request, $slug, $id){
        $curso = Course::find($id);

        $etiquetasActivas = [];
        foreach ($curso->tags as $etiq){
            array_push($etiquetasActivas, $etiq->id);
        }

        $categorias = DB::table('categories')
                        ->select('id', 'title')
                        ->orderBy('id', 'ASC')
                        ->get();

        $subcategorias = DB::table('subcategories')
                            ->select('id', 'title')
                            ->where('category_id', '=', $curso->category_id)
                            ->get();

        $etiquetas = DB::table('tags')
                        ->orderBy('tag', 'ASC')
                        ->get();

        return view('instructors.courses.edit')->with(compact('curso', 'categorias', 'subcategorias', 'etiquetas', 'etiquetasActivas'));
    }

    /**** Instructor - Admin /  T-Courses / Editar T-Course / Guardar Cambios ***/
    public function update(Request $request){
        if(!$request->ajax()){
            $messages = [
                'title.required' => 'El campo Título es requerido.',
                'subtitle.required' => 'El campo Subtítulo es requerido.',
                'category_id.required' => 'El campo Categoría es requerido.',
                'subcategory_id.required' => 'El campo SubCategoría es requerido.',
            ];

            $validator = Validator::make($request->all(), [
                'title' => 'required|max:255',
                'subtitle' => 'required|max:100',
                'category_id' => 'required',
                'subcategory_id' => 'required',
            ], $messages);
            
            $curso = Course::find($request->course_id);
            
            if ($validator->fails()) {
                if (Auth::user()->role_id == 2){
                    return redirect('instructors/t-courses/edit/'.$curso->slug.'/'.$request->course_id)
                        ->withErrors($validator)
                        ->withInput();
                }else if (Auth::user()->role_id == 3){
                     return redirect('admins/t-courses/show/'.$curso->slug.'/'.$request->course_id)
                        ->withErrors($validator)
                        ->withInput();
                }
            }
            
            $curso->fill($request->all());
            $curso->slug = Str::slug($curso->title);

            DB::table('courses_tags')
                ->where('course_id', '=', $curso->id)
                ->delete();

            if (!is_null($request->tags)){
                foreach ($request->tags as $tag){
                    DB::table('courses_tags')->insert(
                        ['course_id' => $curso->id, 'tag_id' => $tag]
                    );
                }
            }

            if ($request->hasFile('cover')){
                $file = $request->file('cover');
                $name = $curso->id.".".$file->getClientOriginalExtension();
                $file->move(public_path().'/uploads/images/courses', $name);
                $curso->cover = $name;
                $curso->cover_name = $file->getClientOriginalName();
            }
            
            $curso->save();

            if (Auth::user()->role_id == 2){
                return redirect('instructors/t-courses/edit/'.$curso->slug.'/'.$request->course_id)->with('msj-exitoso', 'Los datos del T-Course han sido actualizados con éxito');
            }else if (Auth::user()->role_id == 3){
                return redirect('admins/t-courses/show/'.$curso->slug.'/'.$request->course_id)->with('msj-exitoso', 'Los datos del T-Course han sido actualizados con éxito');
            }
        }else{
            $curso = Course::find($request->course_id);

            if (isset($request->file_type)){
                if ($request->file_type == 'cover'){
                    $imagen = public_path().'/uploads/images/courses/'.$curso->cover;
                    if (getimagesize($imagen)) {
                        unlink($imagen);
                    }

                    $curso->cover = NULL;
                    $curso->cover_name = NULL;
                }if ($request->file_type == 'cover'){
                    $imagen = public_path().'/uploads/images/courses/'.$curso->cover;
                    if (getimagesize($imagen)) {
                        unlink($imagen);
                    }

                    $curso->cover = NULL;
                    $curso->cover_name = NULL;
                }else if ($request->file_type == 'miniature_cover'){
                    $imagen = public_path().'/uploads/images/courses/thumbnails/'.$curso->miniature_cover;
                    if (getimagesize($imagen)) {
                        unlink($imagen);
                    }

                    $curso->miniature_cover = NULL;
                    $curso->miniature_cover_name = NULL;
                }else if ($request->file_type == 'preview'){
                    $nombreArchivo = explode("previews/", $curso->preview);
                    if (Storage::disk('s3')->has('courses/previews/'.$nombreArchivo[1])){
                        Storage::disk('s3')->delete('courses/previews/'.$nombreArchivo[1]);
                    }
                    $curso->preview = NULL;
                    $curso->preview_name = NULL;
                }
            }else{
                if ($request->hasFile('cover')){
                    $file = $request->file('cover');
                    $name = $request->course_id.".".$file->getClientOriginalExtension();
                    $file->move(public_path().'/uploads/images/courses', $name);
                    $curso->cover = $name;
                    $curso->cover_name = $file->getClientOriginalName();
                }else if ($request->hasFile('miniature_cover')){
                    $file = $request->file('miniature_cover');
                    $name = $request->course_id.".".$file->getClientOriginalExtension();
                    $file->move(public_path().'/uploads/images/courses/thumbnails', $name);
                    $curso->miniature_cover = $name;
                    $curso->miniature_cover_name = $file->getClientOriginalName();
                }else if ($request->hasFile('preview')){
                    $file2 = $request->file('preview');
                    $upload = Storage::disk('s3')->put('courses/previews', $file2, 'public');
                    $curso->preview = 'https://transformate-videos.s3.us-east-2.amazonaws.com/'.$upload;
                    $curso->preview_name = $file2->getClientOriginalName();
                }else if ($request->hasFile('preview_cover')){
                    $file3 = $request->file('preview_cover');
                    $name2 = $request->course_id.".".$file->getClientOriginalExtension();
                    $file3->move(public_path().'/uploads/images/courses/preview_covers', $name2);
                    $curso->preview_cover = $name2;
                    $curso->preview_cover_name = $file3->getClientOriginalName();
                }
            }

            $curso->save();

            return response()->json(
                "Operación Exitosa."
            );
        }
    }

    //**** Instructor /  T-Courses / Enivar T-Course a Revisión ***//
    public function publish($id){
        DB::table('courses')
            ->where('id', '=', $id)
            ->update(['status' => 1,
                      'sent_for_review' => date('Y-m-d')]);

        //**** Notificar al Administrador ***//
        $notificacion = new NotificationController();
        $notificacion->store(1, 'Nueva Solicitud de Publicación', 'Un instructor ha enviado su T-Course a revisión para publicación', 'fa fa-check', 'admins/t-courses/pending');

        return redirect('instructors/t-courses')->with('msj-exitoso', 'El T-Course ha sido enviado a revisión para su publicación.');
    }

    //*** Estudiante / T-Courses / Continuar T-Course ***//
    //*** Admin / T-Courses / Resumen T-Course ***//
    public function resume($slug, $id){
        $curso = Course::where('id', '=', $id)
                    ->with(['modules' => function ($query){
                            $query->orderBy('priority_order', 'ASC');
                        },
                        'modules.lessons', 
                        'students' => function ($query2){
                            $query2->where('user_id', '=', Auth::user()->id);
                        },
                        'tags'
                    ])->withCount(['students',
                        'ratings' => function ($query3){
                            $query3->orderBy('created_at', 'DESC');
                        },
                        'ratings as promedio' => function ($query4){
                            $query4->select(DB::raw('avg(points)'));
                        }
                    ])->first();

        $primerModulo = DB::table('modules')
                            ->select('id')
                            ->where('course_id', '=', $id)
                            ->where('priority_order', '=', 1)
                            ->first();

        $primeraLeccion = DB::table('lessons')
                            ->select('id')
                            ->where('module_id', '=', $primerModulo->id)
                            ->where('priority_order', '=', 1)
                            ->first();
        
        $promedio = explode('.', $curso->promedio);

        $instructor = User::where('id', '=', $curso->user_id)
                        ->withCount('courses')
                        ->first();

        $cantEstudiantes = 0;
        foreach ($instructor->courses as $curso2){
            $estudiantes = DB::table('courses_students')
                                ->where('course_id', '=', $curso2->id)
                                ->count();

            $cantEstudiantes = $cantEstudiantes + $estudiantes;
        }

        $progreso = DB::table('courses_students')
                        ->select('progress')
                        ->where('user_id', '=', Auth::user()->id)
                        ->where('course_id', '=', $id)
                        ->first();

        $miValoracion = DB::table('ratings')
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('course_id', '=', $id)
                            ->first();

        $cursosRelacionados = DB::table('courses')
                                ->where('category_id', '=', $curso->category_id)
                                ->where('id', '<>', $id)
                                ->orderBy('created_at', 'DESC')
                                ->take(2)
                                ->get();

        $cantLecciones = 0;
        foreach ($curso->modules as $modulo) {
           /*$leccionesMod = DB::table('lessons')
                                ->where('module_id', '=', $modulo->id)
                                ->select('id', 'duration')
                                ->get();*/
            foreach ($modulo->lessons as $leccion){
                $cantLecciones++;
                if ($leccion->duration > 0){
                    $tiempo = explode(".", $leccion->duration);
                    $segundos = $tiempo[0]*60 + $tiempo[1]; 
                    $leccion->hours = floor($segundos/ 3600);
                    $leccion->minutes = floor(($segundos - ($leccion->hours * 3600)) / 60);
                    $leccion->seconds = $segundos - ($leccion->hours * 3600) - ($leccion->minutes * 60);
                }
            }
        }

        $curso->lessons_count = $cantLecciones;

        if (Auth::user()->role_id == 1){
            return view('students.courses.resume')->with(compact('curso', 'primeraLeccion', 'promedio', 'instructor', 'cantEstudiantes', 'progreso', 'miValoracion', 'cursosRelacionados'));     
        }else{
            return view('admins.courses.resume')->with(compact('curso', 'promedio', 'instructor', 'cantEstudiantes', 'progreso', 'miValoracion', 'cursosRelacionados'));
        }
        
    }

    //*** Estudiante / T-Courses / Continuar T-Course / Lecciones ****//
    public function lessons($slug, $id, $lesson_id){
        $leccionEscogida = Lesson::find($lesson_id);

        $progresoLeccion = DB::table('lesson_progress')
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('lesson_id', '=', $lesson_id)
                            ->first();

        if (!is_null($progresoLeccion)){
            $leccionEscogida->finished = $progresoLeccion->finished;
            $leccionEscogida->start_time = $progresoLeccion->progress;
        }else{
            $leccionEscogida->finished = 0;
            $leccionEscogida->start_time = 0;
        }

        $curso = Course::where('id', '=', $id)
                    ->with(['modules' => function($query){
                            $query->orderBy('priority_order', 'ASC');
                        }, 
                        'modules.lessons', 'modules.tests', 'modules.lessons.resource_files'
                    ])->first();

        foreach ($curso->modules as $modulo){
            $cantLecciones = 0;
            $modulo->completed_lessons = DB::table('lesson_progress')
                                            ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
                                            ->join('modules', 'lessons.module_id', '=', 'modules.id')
                                            ->where('modules.id', '=', $modulo->id)
                                            ->where('lesson_progress.user_id', '=', Auth::user()->id)
                                            ->where('lesson_progress.finished', '=', 1)
                                            ->count();

            foreach ($modulo->lessons as $leccion){
                $cantLecciones++;
                
                if ($leccion->duration > 0){
                    $tiempo = explode(".", $leccion->duration);
                    $segundos = $tiempo[0]*60 + $tiempo[1]; 
                    $leccion->hours = floor($segundos/ 3600);
                    $leccion->minutes = floor(($segundos - ($leccion->hours * 3600)) / 60);
                    $leccion->seconds = $segundos - ($leccion->hours * 3600) - ($leccion->minutes * 60);
                }

                $leccion->resource_files_count = DB::table('resource_files')
                                                    ->where('lesson_id', '=', $leccion->id)
                                                    ->count();

                $progresoLeccion = DB::table('lesson_progress')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->where('lesson_id', '=', $leccion->id)
                                        ->first();

                if (!is_null($progresoLeccion)){
                    $leccion->finished = $progresoLeccion->finished;
                }else{
                    $leccion->finished = 0;
                }
            }

            $modulo->lessons_count = $cantLecciones;

            $modulo->tests_count = DB::table('tests')
                                    ->where('module_id', '=', $modulo->id)
                                    ->count();

            if ($modulo->tests_count > 0){
                foreach ($modulo->tests as $test){
                    $datosTest = DB::table('tests_students')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('test_id', '=', $test->id)
                                    ->orderBy('id', 'DESC')
                                    ->first();

                    if (!is_null($datosTest)){
                        $test->presented = 1;
                        $test->score = $datosTest->score;
                        $test->intent_id = $datosTest->id;
                    }else{
                        $test->presented = 0;
                        $test->score = 0;
                    }
                }
            }
        }

        return view('students.courses.lessons')->with(compact('curso', 'lesson_id', 'leccionEscogida', 'cantLecciones'));     
    }

    //*** Estudiante / T-Courses / Lecciones / Actualizar Progreso de la T-Course al finalizar una lección ***//
    //*** Función llamada desde el método "actualizar_progreso_leccion (AjaxController)" ***//
    public function update_progress($curso){
        try{
            $modulosCurso = DB::table('modules')
                                ->select('id')
                                ->where('course_id', '=', $curso)
                                ->get();

            $cantLecciones = 0;
            $leccionesCurso = collect();
            foreach ($modulosCurso as $modulo){
                $leccionesModulo = DB::table('lessons')
                                    ->select('id')
                                    ->where('module_id', '=', $modulo->id)
                                    ->get();

                foreach ($leccionesModulo as $lec){
                    $leccionesCurso->push($lec);
                }
            }
            $cantLecciones = $leccionesCurso->count();

            $cantLeccionesFinalizadas = 0; 
            foreach ($leccionesCurso as $leccionCurso){
                $progresoLeccion = DB::table('lesson_progress')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('lesson_id', '=', $leccionCurso->id)
                                    ->first();

                if (!is_null($progresoLeccion)){
                    if ($progresoLeccion->finished == 1){
                        $cantLeccionesFinalizadas++;
                    }
                }
            }
            $progreso = (($cantLeccionesFinalizadas * 100) / $cantLecciones);

            if ($progreso == 100){
                $fecha = date('Y-m-d');

                DB::table('courses_students')
                    ->where('user_id', '=', Auth::user()->id)
                    ->where('course_id', '=', $curso)
                    ->update(['progress' => number_format($progreso),
                              'ending_date' => $fecha]);

                $this->generate_certificate($curso, Auth::user()->id, $fecha);
            }else{
                DB::table('courses_students')
                    ->where('user_id', '=', Auth::user()->id)
                    ->where('course_id', '=', $curso)
                    ->update(['progress' => number_format($progreso)]);
            }

            return response()->json(
                $progreso
            );
        }catch (\Exception $e) {
            $error = explode('Stack trace', $e);
            \Log::error("Módulo: Estudiante. Usuario Autenticado: ".Auth::user()->id.". Acción: Actualizar progreso de un curso. # Curso: ".$curso." Error: ".$error[0]);
        }catch (\Throwable $ex) {
            $error = explode('Stack trace', $ex);
            \Log::error("Módulo: Estudiante. Usuario Autenticado: ".Auth::user()->id.". Acción: Actualizar progreso de un curso. # Curso: ".$curso." Error: ".$error[0]);
        }
    }

    //**** Genera el PDF del certificado y lo almacena para mostrarlo posteriormente ***//
    public function generate_certificate($curso_id, $usuario_id, $fecha){
        $curso = Course::where('id', '=', $curso_id)
                        ->first();

        $datosUsuario = DB::table('users')
                            ->select('names', 'last_names')
                            ->where('id', '=', $usuario_id)
                            ->first();
        $alumno = $datosUsuario->names." ".$datosUsuario->last_names;
        $mentor = $curso->user->names." ".$curso->user->last_names;

        $partesFecha = explode("-", $fecha);

        switch ($partesFecha[1]) {
            case '01':
                $mes = 'Enero';
            break;
            case '02':
                $mes = 'Febrero';
            break;
            case '03':
                $mes = 'Marzo';
            break;
            case '04':
                $mes = 'Abril';
            break;
            case '05':
                $mes = 'Mayo';
            break;
            case '06':
                $mes = 'Junio';
            break;
            case '07':
                $mes = 'Julio';
            break;
            case '08':
                $mes = 'Agosto';
            break;
            case '09':
                $mes = 'Septiembre';
            break;
            case '10':
                $mes = 'Octubre';
            break;
            case '11':
                $mes = 'Noviembre';
            break;
            case '12':
                $mes = 'Diciembre';
            break;
        }

        $fecha_fin = $partesFecha[2]." de ".$mes." de ".$partesFecha[0];

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdfs.certificate', compact('curso', 'alumno', 'mentor', 'fecha_fin'))->setPaper('a4', 'landscape');
        $output = $pdf->output();
        $path = "certificates/courses/".$usuario_id."-".$curso_id.".pdf"; 
        file_put_contents($path, $output);
        //return $pdf->stream();
    }

    //*** Admin / T-Courses / Cursos Creados ***//
    public function courses_record(Request $request){
        $cursos = Course::where('status', '=', 0)
                    ->orderBy('created_at', 'DESC')
                    ->get();

        return view('admins.courses.index')->with(compact('cursos'));
    }

     //*** Admin / T-Courses / Cursos Publicados ***//
    public function published_record(Request $request){
        $cursos = Course::where('status', '=', 2)
                    ->orderBy('created_at', 'DESC')
                    ->get();


        return view('admins.courses.published')->with(compact('cursos'));
    }

    //*** Admin / T-Courses / Pendientes Para Publicación ***//
    public function pending_for_publication(Request $request){
        $cursos = Course::where('status', '=', 1)
                    ->orderBy('created_at', 'DESC')
                    ->get();

        return view('admins.courses.pendingForPublication')->with(compact('cursos'));
    }

    //*** Admin / T-Courses / Deshabilitados ***//
    public function disabled_record(Request $request){
        $cursos = Course::where('status', '=', 3)
                    ->orderBy('created_at', 'DESC')
                    ->get();

        return view('admins.courses.disabledRecord')->with(compact('cursos'));
    }

    //*** Admin / T-Courses / Reportes / Ventas ***//
    public function sales(Request $request){
        $cursos = Course::where('status', '>', 1)
                    ->withCount('purchases')
                    ->orderBy('purchases_count', 'DESC')
                    ->get();

        return view('admins.courses.reports.sales')->with(compact('cursos'));
    }

    //*** Admin / T-Courses / Reportes / Ventas / Ver Reporte Detallado ***//
    public function show_sales($curso){
        $datosCurso = Course::where('id', '=', $curso)
                    ->select('title')
                    ->first();

        $compras = PurchaseDetail::where('course_id', '=', $curso)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.courses.reports.showSales')->with(compact('datosCurso', 'compras'));
    }

    //*** Admin / T-Courses / Reportes / Estudiantes ***//
    public function students(Request $request){
        $cursos = Course::where('status', '>', 1)
                    ->withCount('students')
                    ->orderBy('students_count', 'DESC')
                    ->get();

        return view('admins.courses.reports.students')->with(compact('cursos'));
    }

     //*** Admin / T-Courses / Reportes / Estudiantes / Ver Reporte Detallado ***//
    public function show_students($curso){
        $datosCurso = Course::where('id', '=', $curso)
                        ->withCount('students')->first();

        return view('admins.courses.reports.showStudents')->with(compact('datosCurso'));
    }

    //*** Admin / T-Courses / Reportes / Valoraciones ***//
    public function ratings(Request $request){
        $cursos = Course::where('status', '>', 1)
                    ->withCount(['ratings', 
                                 'ratings as promedio' => function ($query){
                                    $query->select(DB::raw('avg(points)'));
                                }])->orderBy('promedio', 'DESC')
                                ->get();

        $estudiantes = DB::table('users')
                        ->select('id', 'email')
                        ->orderBy('email', 'ASC')
                        ->get();

        return view('admins.courses.reports.ratings')->with(compact('cursos', 'estudiantes'));
    }

     //*** Admin / T-Courses / Reportes / Valoraciones / Ver Reporte Detallado ***//
    public function show_ratings($curso){
        $datosCurso = Course::where('id', '=', $curso)
                        ->withCount(['ratings', 
                                 'ratings as promedio' => function ($query){
                                    $query->select(DB::raw('avg(points)'));
                                }])->first();

        $valoraciones = Rating::where('course_id', '=', $curso)
                            ->orderBy('created_at', 'DESC')
                            ->get();

        return view('admins.courses.reports.showRatings')->with(compact('datosCurso', 'valoraciones'));
    }

     //*** Instructor / T-Courses / Historial de Compras de un Curso ***//
    public function purchases_record($slug, $id){
        $compras = PurchaseDetail::where('course_id', '=', $id)
                        ->with('commission')
                        ->orderBy('created_at', 'DESC')
                        ->paginate(25);


        $cantCompras = PurchaseDetail::where('course_id', '=', $id)
                        ->count();

        return view('instructors.purchases.index')->with(compact('compras', 'cantCompras'));
    }

    //*** Admin / T-Courses / T-Courses por Instructor ***//
    public function show_by_instructor($slug, $instructor){
        $cursos = Course::where('user_id', '=', $instructor)
                    ->orderBy('created_at', 'DESC')
                    ->paginate(20);

        $totalCursos = Course::where('user_id', '=', $instructor)
                            ->count();

        return view('admins.courses.showByInstructor')->with(compact('cursos', 'totalCursos'));
    }

    //*** Admin / Informes / Cursos Más Vendidos ***//
    public function best_selling(){
        $cursos = PurchaseDetail::with('course')
                    ->select('purchase_details.course_id', DB::raw('count(*) as total'))
                    ->where('course_id', '<>', NULL)
                    ->groupBy('course_id')
                    ->orderBy('total', 'DESC')
                    ->get();

        return view('admins.reports.bestSellingCourses')->with(compact('cursos'));
    }

    //*** Admin / Informes / Cursos Con Más Alumnos ***//
    public function most_taken(){
        $alumnosPorCurso = DB::table('courses_students')
                                ->select('course_id', DB::raw('count(*) as total'))
                                ->groupBy('course_id')
                                ->orderBy('total', 'DESC')
                                ->get();

        $cursos = collect();
        foreach ($alumnosPorCurso as $cursoAlumno){
            $curso = Course::where('id', '=', $cursoAlumno->course_id)
                        ->withCount('students')
                        ->first();

            $cursos->push($curso);
        }

        return view('admins.reports.mostTakenCourses')->with(compact('cursos'));
    }

    //*** Admin / Informes / Cursos Mejores Valorados ***//
    public function best_rating(){
        $cursos = Rating::with('course')
                    ->select('ratings.course_id', DB::raw('COUNT(*) as cant_valoraciones'), DB::raw('AVG(points) as valoracion'))
                    ->where('course_id', '<>', NULL)
                    ->groupBy('course_id')
                    ->orderBy('valoracion', 'DESC')
                    ->get();

        return view('admins.reports.bestRatingCourses')->with(compact('cursos'));
    }

    //*** Admin / Informes / Cursos Más Recientes ***//
    public function most_recent(){
        $cursos = Course::where('status', '=', 2)
                    ->orderBy('published_at', 'DESC')
                    ->take(20)
                    ->get();

        return view('admins.reports.mostRecentCourses')->with(compact('cursos'));
    }
}
