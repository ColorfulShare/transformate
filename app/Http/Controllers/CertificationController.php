<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str as Str;
use App\Models\Certification; use App\Models\Module; use App\Models\Lesson; use App\Models\Category; 
use App\Models\PurchaseDetail; use App\Models\User; use App\Models\Rating; use App\Models\ResourceFile;
use App\Models\Comment;
use Auth; use DB; use Storage;

class CertificationController extends Controller
{
    //**** Estudiante / T - Mentorings
    //**** Instructor / T - Mentorings
    public function index(){
        //Vista de Estudiantes
        if (Auth::user()->role_id == 1){
            return view('contador');

            /*$configuracion = DB::table('settings')->first();
            $certificacionesMasVendidas = NULL;
            $cantCertificacionesMasVendidas = 0;
            $certificacionesMasRecientes = NULL;
            //$certificacionesMasCursadas = NULL;
            //$cantCertificacionesMasCursadas = 0;

            $categorys = Category::with(['certifications' => function ($query2){
                                        $query2->where('status', '=', 2);
                                    }])->orderBy('id', 'ASC')
                                    ->get();

            $cantCertificacionesRestantes = 0;
            $certificacionesRestantes = collect();
            foreach ($categorys as $cat){
                $cat->certifications_count = $cat->certifications->count();

                if ($cat->certifications_count < 4){
                    foreach ($cat->certifications as $cer){
                        $certificacionesRestantes->push($cer);
                        $cantCertificacionesRestantes++; 
                    }
                }
            }

            if ($configuracion->most_sellers_slider_certification == 1){
                $certificacionesMasVendidas = PurchaseDetail::with('certification')
                                                ->select('purchase_details.certification_id', DB::raw('count(*) as total'))
                                                ->where('certification_id', '<>', NULL)
                                                ->groupBy('certification_id')
                                                ->orderBy('total', 'DESC')
                                                ->get();

                foreach ($certificacionesMasVendidas as $certificacionVendida){
                    if ($certificacionVendida->status == 2){
                        $cantCertificacionesMasVendidas++;
                    }
                }
            }
            
            if ($configuracion->most_recent_slider_certification == 1){
                $certificacionesMasRecientes = Certification::where('status', '=', 2)
                                                ->orderBy('created_at', 'DESC')
                                                ->get();
            }

            /*if ($configuracion->most_taken_slider_certification == 1){
                $certificacionesCursadas = DB::table('certifications_students')
                                                ->select('certification_id', DB::raw('count(*) as total'))
                                                ->groupBy('certification_id')
                                                ->orderBy('total', 'DESC')
                                                ->get();

                $certificacionesMasCursadas = collect();
                foreach ($certificacionesCursadas as $certificacionCursada){
                    $datosCertificacion = Certification::where('id', '=', $certificacionCursada->certification_id)
                                            ->where('status', '=', 2)
                                            ->first();

                    if (!is_null($datosCertificacion)){
                        $cantCertificacionesMasCursadas++;
                        $certificacionesMasCursadas->push($datosCertificacion);
                    }
                }
            }*/

            /*$cantCertificacionesDestacadas = Certification::where('featured', '=', 1)
                                                ->where('status', '=', 2)
                                                ->count();

            $certificacionesDestacadas = NULL;
            if ($cantCertificacionesDestacadas > 0){
                $certificacionesDestacadas = Certification::withCount(['students', 'ratings', 
                                                'ratings as promedio' => function ($query2){
                                                    $query2->select(DB::raw('avg(points)'));
                                                }])->where('featured', '=', 1)
                                                ->where('status', '=', 2)
                                                ->orderBy('created_at', 'DESC')
                                                ->get();
            }*/

            /*$portada = Certification::select('id', 'user_id', 'title', 'subtitle', 'slug', 'cover', 'review', 'price','image_cover', 'preview')
                        ->where('cover_home', '=', 1)
                        ->first();
            if ( (!is_null($portada)) && (empty($portada->image_cover)) ) {
                $portada->image_cover = '10.jpg';
            }

            if (!is_null($portada)){
                $portadaAgregada = DB::table('certifications_students')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('certification_id', '=', $portada->id)
                                    ->first();
            }else{
                $portadaAgregada = NULL;
            }
           

            $misCertificaciones = DB::table('certifications_students')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->get();

            $certificacionesAgregadas = array();
            foreach ($misCertificaciones as $miCertificacion){
                array_push($certificacionesAgregadas, $miCertificacion->certification_id);
            }

            return view('landing.certifications')->with(compact('categorys', 'portada', 'certificacionesMasVendidas', 'cantCertificacionesMasVendidas', 'certificacionesMasRecientes', 'configuracion', 'portadaAgregada', 'certificacionesAgregadas', 'cantCertificacionesRestantes', 'certificacionesRestantes'));*/
        }else if (Auth::user()->role_id == 2){
            //Vista de Instructor
            $certificaciones = Certification::where('user_id', '=', Auth::user()->id)
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(8);

            $certificacionesConCorrecciones = Certification::where('user_id', '=', Auth::user()->id)
                                                ->where('status', '=', 4)
                                                ->orderBy('reviewed_at', 'DESC')
                                                ->get();

            $cantCertificacionesConCorrecciones = $certificacionesConCorrecciones->count();

            $nuevasDiscusiones = collect();

            $instructor = User::where('id', '=', Auth::user()->id)
                            ->with(['certifications',
                                'certifications.discussions' => function ($query){
                                    $query->where('status', '=', 0);
                                }
                            ])->first();

            foreach ($instructor->certifications as $certificacion){
                foreach ($certificacion->discussions as $discusion){
                    $nuevasDiscusiones->push($discusion);
                }
            }

            $cantNuevasDiscusiones = $nuevasDiscusiones->count();
            $nuevasDiscusiones = $nuevasDiscusiones->sortByDesc('created_at');


            $nuevosComentarios = Comment::join('discussions', 'comments.discussion_id', '=', 'discussions.id')
                                    ->join('certifications', 'discussions.certification_id', '=', 'certifications.id')
                                    ->join('users', 'discussions.user_id', '=', 'users.id')
                                    ->where('comments.status', '=', 0)
                                    ->where('discussions.status', '=', 1)
                                    ->where('certifications.user_id', '=', Auth::user()->id)
                                    ->select('users.avatar', 'users.names', 'users.last_names', 'discussions.slug', 'discussions.title', 'comments.discussion_id', DB::raw('COUNT(*) as total_comentarios'))
                                    ->groupBy('comments.discussion_id')
                                    ->get();

            $cantNuevosComentarios = $nuevosComentarios->count();

            return view('instructors.certifications.index')->with(compact('certificaciones', 'certificacionesConCorrecciones', 'cantCertificacionesConCorrecciones', 'nuevasDiscusiones', 'cantNuevasDiscusiones',  'nuevosComentarios', 'cantNuevosComentarios'));
        }
    }

    //**** Instructor /  T-Mentoring / Crear T-Mentoring ***//
    public function create(){
        $categorias = DB::table('categories')
                        ->select('id', 'title', 'icon')
                        ->orderBy('id', 'ASC')
                        ->get();

         $etiquetas = DB::table('tags')
                        ->orderBy('tag', 'ASC')
                        ->get();

        return view('instructors.certifications.create')->with(compact('categorias', 'etiquetas'));
    }

    //**** Instructor /  T-Mentoring / Crear T-Mentoring / Guardar ***//
    public function store(Request $request){
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

        if ($validator->fails()) {
            return redirect('instructors/t-mentorings/create')
                ->withErrors($validator)
                ->withInput();
        }

        $certificacion = new Certification($request->all());
        $certificacion->user_id = Auth::user()->id;
        $certificacion->slug = Str::slug($certificacion->title);
        if (is_null($request->price)){
            $certificacion->price = 0;
        }else{
            $certificacion->price = $request->price;
        }

        $certificacion->save();

        if (!is_null($request->tags)){
            foreach ($request->tags as $tag){
                DB::table('certifications_tags')->insert(
                    ['certification_id' => $certificacion->id, 'tag_id' => $tag]
                );
            }
        }
        
        for ($i = 1; $i < 11; $i++){
            $modulo = new Module();
            $modulo->certification_id = $certificacion->id;
            $modulo->priority_order = $i;
            $modulo->title = 'Módulo '.$i;
            $modulo->save();

            for ($j = 1; $j < 7; $j++){
                $leccion = new Lesson();
                $leccion->module_id = $modulo->id;
                $leccion->priority_order = $j;
                $leccion->title = 'Lección '.$j;
                $leccion->save();
            }
        }

        return redirect('instructors/t-mentoring/temary/'.$certificacion->slug.'/'.$certificacion->id)->with('msj-exitoso', 'La T-Mentoring ha sido creada con éxito. Por favor, cree el temario de la misma para finalizar.');
    }

    //**** Instructor /  T-Mentoring / Cargar Temario ***//
    public function temary(Request $request, $slug, $id){
        $certificacion = Certification::where('id', '=', $id)
                            ->with([
                                'modules' => function($query){
                                    $query->orderBy('priority_order', 'ASC');
                                }, 
                                'modules.lessons' => function ($query2){
                                    $query2->orderBy('priority_order', 'ASC');
                                }, 'modules.lessons.resource_files'])->first();

        return view('instructors.certifications.temary')->with(compact('certificacion', 'www'));
    }

    //**** Instructor /  T-Mentoring / Temario / Actualizar ***//
    public function update_temary(Request $request){
        if ($request->tipo_actualizacion == 'modulo'){
            $modulo = Module::find($request->module_id);
            $modulo->fill($request->all());
            $modulo->save();

            return response()->json(
                "Operación Exitosa."
            );

        }else if ($request->tipo_actualizacion == 'titulo_leccion'){
            $leccion = Lesson::find($request->lesson);
            $leccion->fill($request->all());
            $leccion->save();

            return response()->json(
                "Operación Exitosa."
            );
        }else if ($request->tipo_actualizacion == 'video_leccion'){
            $leccion = Lesson::find($request->leccion);
            $leccion->filename = $request->nombre_archivo;
            $leccion->file_extension = $request->extension;
            $leccion->file_icon = $this->setIcon($leccion->file_extension);
            $leccion->video = 'https://transformate-videos.s3.us-east-2.amazonaws.com/'.$request->direccion; 
            $leccion->save();

            return response()->json(
                true
            );
        }else if ($request->tipo_actualizacion == 'recurso'){
            $recurso = new ResourceFile();
            $recurso->lesson_id = $request->leccion;
            $recurso->filename = $request->nombre_archivo;
            $recurso->file_extension = $request->extension;
            $recurso->file_icon = $this->setIcon($recurso->file_extension);
            $recurso->link = 'https://transformate-videos.s3.us-east-2.amazonaws.com/'.$request->direccion; 
            $recurso->save();

            return response()->json(
                true
            );
        }
    }

    public function setIcon($extension){
        switch ($extension) {
            case 'mp4':
                $icono = 'far fa-file-video';
            break;
            case 'docx':
                $icono = 'far fa-file-word';
            break;
            case 'pptx':
                $icono = 'far fa-file-powerpoint';
            break;
            case 'jpg':
                $icono = 'far fa-file-image';
            break;
            case 'jpeg':
                $icono = 'far fa-file-image';
            break;
            case 'png':
                $icono = 'far fa-file-image';
            break;
            case 'svg':
                $icono = 'far fa-file-image';
            break;
            case 'gif':
                $icono = 'far fa-file-image';
            break;
            case 'mp3':
                $icono = 'far fa-file-audio';
            break;
            case 'xlsx':
                $icono = 'far fa-file-excel';
            break;
            case 'zip':
                $icono = 'far fa-file-archive';
            break;
            case 'rar':
                $icono = 'far fa-file-archive';
            break;
            case 'txt':
                $icono = 'far fa-file-alt';
            break;
            case 'csv':
                $icono = 'far fa-file-csv';
            break;
            default:
                $icono = 'far fa-file';
            break;
        }

        return $icono;
    }

    //**** Invitado - Estudiante - Instructor /  T-Mentorings / Ver T-Mentoring ***//
    //**** Admin /  T-Mentorings / Ver - Editar T-Mentoring ***//
    public function show($slug, $id){
        if (Auth::guest()){
            $certificacion = Certification::where('id', '=', $id)
                                ->with('modules', 'modules.lessons', 'modules.tests', 'tags')
                                ->withCount(['students', 
                                    'ratings' => function($query){
                                         $query->orderBy('created_at', 'DESC');
                                    }, 
                                    'ratings as promedio' => function ($query2){
                                        $query2->select(DB::raw('avg(points)'));
                                    }
                                ])->first();

            $promedio = explode('.', $certificacion->promedio);

            $instructor = User::where('id', '=', $certificacion->user_id)
                            ->withCount('certifications')
                            ->first();

            $cantEstudiantes = 0;
            foreach ($instructor->certifications as $certificacion2){
                $estudiantes = DB::table('certifications_students')
                                ->where('certification_id', '=', $certificacion2->id)
                                ->count();

                $cantEstudiantes = $cantEstudiantes + $estudiantes;
            }

            return view('landing.showCertification')->with(compact('certificacion', 'promedio', 'instructor', 'cantEstudiantes'));
        }else{
            if (Auth::user()->role_id == 1){ 
                //Vista de Estudiantes  
                $agregado = Auth::user()->certifications_students()->where('certification_id', '=', $id)->count();

                if ($agregado == 0){
                    $certificacion = Certification::where('id', '=', $id)
                                        ->with('modules', 'modules.lessons', 'modules.tests', 'tags')
                                        ->withCount(['students', 
                                            'ratings' => function($query){
                                                 $query->orderBy('created_at', 'DESC');
                                            }, 
                                            'ratings as promedio' => function ($query2){
                                                $query2->select(DB::raw('avg(points)'));
                                            }
                                         ])->first();

                    $promedio = explode('.', $certificacion->promedio);

                    $instructor = User::where('id', '=', $certificacion->user_id)
                                    ->withCount('certifications')
                                    ->first();

                    $cantEstudiantes = 0;
                    foreach ($instructor->certifications as $certificacion2){
                        $estudiantes = DB::table('certifications_students')
                                        ->where('certification_id', '=', $certificacion2->id)
                                        ->count();

                        $cantEstudiantes = $cantEstudiantes + $estudiantes;
                    }


                    return view('students.certifications.show')->with(compact('certificacion', 'promedio', 'instructor', 'cantEstudiantes'));
                }else{
                    return redirect('students/t-mentorings/resume/'.$slug.'/'.$id);
                }
            }else if (Auth::user()->role_id == 2){
                //Vista de Instructor;
                $certificacion = Certification::where('id', '=', $id)
                                    ->with('modules', 'modules.lessons', 'tags')
                                    ->withCount(['students'])->first();

                return view('instructors.certifications.show')->with(compact('certificacion'));
            }else if (Auth::user()->role_id == 3){
                $certificacion = Certification::find($id);

                $etiquetasActivas = [];
                foreach ($certificacion->tags as $etiq){
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
                                    ->where('category_id', '=', $certificacion->category_id)
                                    ->orderBy('id', 'ASC')
                                    ->get();

                return view('admins.certifications.show')->with(compact('certificacion', 'categorias', 'subcategorias', 'etiquetasActivas', 'etiquetas'));
            }  
        }
    }

    //**** Instructor /  T-Mentorings / Editar T-Mentoring ***//
    public function edit($slug, $id){
        $certificacion = Certification::find($id);

        $etiquetasActivas = [];
        foreach ($certificacion->tags as $etiq){
            array_push($etiquetasActivas, $etiq->id);
        }

        $categorias = DB::table('categories')
                        ->select('id', 'title')
                        ->orderBy('id', 'ASC')
                        ->get();

        $subcategorias = DB::table('subcategories')
                            ->select('id', 'title')
                            ->where('category_id', '=', $certificacion->category_id)
                            ->get();

        $etiquetas = DB::table('tags')
                        ->orderBy('tag', 'ASC')
                        ->get();

        return view('instructors.certifications.edit')->with(compact('certificacion', 'categorias', 'subcategorias', 'etiquetas', 'etiquetasActivas'));
    }

    //**** Instructor - Admin /  T-Mentorings / Editar T-Mentoring / Guardar Cambios ***//
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
            
            $certificacion = Certification::find($request->certification_id);
            
            if ($validator->fails()) {
                if (Auth::user()->role_id == 2){
                    return redirect('instructors/t-mentorings/edit/'.$certificacion->slug.'/'.$request->certification_id)
                        ->withErrors($validator)
                        ->withInput();
                }else if (Auth::user()->role_id == 3){
                    return redirect('admins/t-mentorings/show/'.$certificacion->slug.'/'.$request->certification_id)
                        ->withErrors($validator)
                        ->withInput();
                }
            }
           
            $certificacion->fill($request->all());
            $certificacion->slug = Str::slug($certificacion->title);

             DB::table('certifications_tags')
                ->where('certification_id', '=', $certificacion->id)
                ->delete();

            if (!is_null($request->tags)){
                foreach ($request->tags as $tag){
                    DB::table('certifications_tags')->insert(
                        ['certification_id' => $certificacion->id, 'tag_id' => $tag]
                    );
                }
            }
            
            $certificacion->save();

            if (Auth::user()->role_id == 2){
                return redirect('instructors/t-mentorings/edit/'.$certificacion->slug.'/'.$request->certification_id)->with('msj-exitoso', 'Los datos de la T-Mentoring han sido actualizados con éxito');
            }else if (Auth::user()->role_id == 3){
                return redirect('admins/t-mentorings/show/'.$certificacion->slug.'/'.$request->certification_id)->with('msj-exitoso', 'Los datos de la T-Mentoring han sido actualizados con éxito');
            }
        }else{
            $certificacion = Certification::find($request->certification_id);

            if (isset($request->file_type)){
                if ($request->file_type == 'cover'){
                    $imagen = public_path().'/uploads/images/certifications/'.$certificacion->cover;
                    if (getimagesize($imagen)) {
                        unlink($imagen);
                    }

                    $certificacion->cover = NULL;
                    $certificacion->cover_name = NULL;
                }else if ($request->file_type == 'preview_cover'){
                    $imagen = public_path().'/uploads/images/certifications/preview_covers/'.$certificacion->preview_cover;
                    if (getimagesize($imagen)) {
                        unlink($imagen);
                    }

                    $certificacion->preview_cover = NULL;
                    $certificacion->preview_cover_name = NULL;
                }else if ($request->file_type == 'preview'){
                    $nombreArchivo = explode("previews/", $certificacion->preview);
                    if (Storage::disk('s3')->has('certifications/previews/'.$nombreArchivo[1])){
                        Storage::disk('s3')->delete('certifications/previews/'.$nombreArchivo[1]);
                    }

                    $certificacion->preview = NULL;
                    $certificacion->preview_name = NULL;
                }
            }else{
                if ($request->hasFile('cover')){
                    $file = $request->file('cover');
                    $name = time().$file->getClientOriginalName();
                    $file->move(public_path().'/uploads/images/certifications', $name);
                    $certificacion->cover = $name;
                    $certificacion->cover_name = $file->getClientOriginalName();
                }else if ($request->hasFile('preview')){
                    $file2 = $request->file('preview');
                    $upload = Storage::disk('s3')->put('certifications/previews', $file2, 'public');
                    $certificacion->preview = 'https://transformate-videos.s3.us-east-2.amazonaws.com/'.$upload;
                    $certificacion->preview_name = $file2->getClientOriginalName();
                }else if ($request->hasFile('preview_cover')){
                    $file3 = $request->file('preview_cover');
                    $name2 = time().$file3->getClientOriginalName();
                    $file3->move(public_path().'/uploads/images/certifications/preview_covers', $name2);
                    $certificacion->preview_cover = $name2;
                    $certificacion->preview_cover_name = $file3->getClientOriginalName();
                }
            }

            $certificacion->save();

            return response()->json(
                "Operación Exitosa."
            );
        }
    }

    //**** Instructor /  T-Mentorings / Enivar T-Mentoring a Revisión ***//
    public function publish($id){
        DB::table('certifications')
            ->where('id', '=', $id)
            ->update(['status' => 1,
                      'sent_for_review' => date('Y-m-d')]);

        //**** Notificar al Administrador ***//
        $notificacion = new NotificationController();
        $notificacion->store(1, 'Nueva Solicitud de Publicación', 'Un instructor ha enviado su T-Mentoring a revisión para publicación', 'fa fa-check', 'admins/t-mentorings/pending');

        return redirect('instructors/t-mentorings')->with('msj-exitoso', 'La T-Mentoring ha sido enviada a revisión para su publicación.');
    }

    //*** Estudiante / T-Mentorings / Continuar T-Mentoring ***//
    //*** Admin / T-Mentorings / Resumen T-Mentoring ***//
    public function resume($slug, $id){
        $certificacion = Certification::where('id', '=', $id)
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
                            ->where('certification_id', '=', $id)
                            ->where('priority_order', '=', 1)
                            ->first();

        $primeraLeccion = DB::table('lessons')
                            ->select('id')
                            ->where('module_id', '=', $primerModulo->id)
                            ->where('priority_order', '=', 1)
                            ->first();
        
        $promedio = explode('.', $certificacion->promedio);

        $instructor = User::where('id', '=', $certificacion->user_id)
                        ->withCount('courses')
                        ->first();

        $cantEstudiantes = 0;
        foreach ($instructor->certifications as $certificacion2){
            $estudiantes = DB::table('certifications_students')
                                ->where('certification_id', '=', $certificacion2->id)
                                ->count();

            $cantEstudiantes = $cantEstudiantes + $estudiantes;
        }

        $progreso = DB::table('certifications_students')
                        ->select('progress')
                        ->where('user_id', '=', Auth::user()->id)
                        ->where('certification_id', '=', $id)
                        ->first();

        $miValoracion = DB::table('ratings')
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('certification_id', '=', $id)
                            ->first();

        $certificacionesRelacionadas = DB::table('certifications')
                                ->where('category_id', '=', $certificacion->category_id)
                                ->where('id', '<>', $id)
                                ->orderBy('created_at', 'DESC')
                                ->take(2)
                                ->get();

        if (Auth::user()->role_id == 1){
            return view('students.certifications.resume')->with(compact('certificacion', 'primeraLeccion', 'promedio', 'instructor', 'cantEstudiantes', 'progreso', 'miValoracion', 'certificacionesRelacionadas')); 
        }else{
            return view('admins.certifications.resume')->with(compact('certificacion', 'promedio', 'instructor', 'cantEstudiantes', 'progreso', 'miValoracion', 'certificacionesRelacionadas'));
        } 
    }

    //*** Estudiante / T-Mentorings / Continuar T-Mentoring / Lecciones ***//
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

        $certificacion = Certification::where('id', '=', $id)
                            ->with(['modules' => function($query){
                                    $query->orderBy('priority_order', 'ASC');
                                }, 
                                'modules.lessons', 'modules.tests', 'modules.lessons.resource_files'
                            ])->first();

        foreach ($certificacion->modules as $modulo){
            $modulo->completed_lessons = DB::table('lesson_progress')
                                            ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
                                            ->join('modules', 'lessons.module_id', '=', 'modules.id')
                                            ->where('modules.id', '=', $modulo->id)
                                            ->where('lesson_progress.user_id', '=', Auth::user()->id)
                                            ->where('lesson_progress.finished', '=', 1)
                                            ->count();

            foreach ($modulo->lessons as $leccion){
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

        return view('students.certifications.lessons')->with(compact('certificacion', 'lesson_id', 'leccionEscogida')); ;     
    }

    //*** Estudiante / T-Mentorings / Lecciones / Actualizar Progreso de la T-Mentoring al finalizar una lección ***//
    //*** Función llamada desde el método "actualizar_progreso_leccion (AjaxController)" ***//
    public function update_progress($certificacion){
        $modulosCertificacion = DB::table('modules')
                                    ->select('id')
                                    ->where('certification_id', '=', $certificacion)
                                    ->get();

        $cantLecciones = 0;
        foreach ($modulosCertificacion as $modulo){
            $leccionesModulo = DB::table('lessons')
                                ->select('id')
                                ->where('module_id', '=', $modulo->id)
                                ->get();

            $cantLecciones += $leccionesModulo->count();
        }

        $cantLeccionesFinalizadas = 0; 
        foreach ($leccionesModulo as $leccion){
            $progresoLeccion = DB::table('lesson_progress')
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('lesson_id', '=', $leccion->id)
                                ->first();

            if (!is_null($progresoLeccion)){
                if ($progresoLeccion->finished == 1){
                    $cantLeccionesFinalizadas++;
                }
            }
        }

        $progreso = (($cantLeccionesFinalizadas * 100) / $cantLecciones);

        if ($progreso == 100){
            DB::table('certifications_students')
                ->where('user_id', '=', Auth::user()->id)
                ->where('certification_id', '=', $certificacion)
                ->update(['progress' => number_format($progreso),
                          'ending_date' => date('Y-m-d')]);
        }else{
            DB::table('certifications_students')
                ->where('user_id', '=', Auth::user()->id)
                ->where('certification_id', '=', $certificacion)
                ->update(['progress' => number_format($progreso)]);
        }
    }

    //*** Admin / T-Mentorings /  Creadas ***//
    public function certifications_record(Request $request){
        $certificaciones = Certification::where('status', '=', 0)
                                ->orderBy('created_at', 'DESC')
                                ->get();

        return view('admins.certifications.index')->with(compact('certificaciones'));
    }

     //*** Admin / T-Mentorings /  Publicadas ***//
    public function published_record(Request $request){
        $certificaciones = Certification::where('status', '=', 2)
                                ->orderBy('created_at', 'DESC')
                                ->get();

        return view('admins.certifications.published')->with(compact('certificaciones'));
    }

    //*** Admin / T-Mentorings / Pendientes Para Publicación ***//
    public function pending_for_publication(Request $request){
        $certificaciones = Certification::where('status', '=', 1)
                                ->orderBy('created_at', 'DESC')
                                ->get();

        return view('admins.certifications.pendingForPublication')->with(compact('certificaciones'));
    }

    //*** Admin / T-Mentorings / Deshabilitados ***//
    public function disabled_record(Request $request){
        $certificaciones = Certification::where('status', '=', 3)
                                ->orderBy('created_at', 'DESC')
                                ->get();

        return view('admins.certifications.disabledRecord')->with(compact('certificaciones'));
    }

    //*** Admin / T-Mentorings / Reportes / Ventas ***//
    public function sales(Request $request){
        $certificaciones = Certification::where('status', '>', 1)
                                ->withCount('purchases')
                                ->orderBy('purchases_count', 'DESC')
                                ->get();

        return view('admins.certifications.reports.sales')->with(compact('certificaciones'));
    }

    //*** Admin / T-Mentorings / Reportes / Ventas / Ver Reporte Detallado ***//
    public function show_sales($certificacion){
        $datosCertificacion = Certification::where('id', '=', $certificacion)
                                ->select('title')
                                ->first();

        $compras = PurchaseDetail::where('certification_id', '=', $certificacion)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.certifications.reports.showSales')->with(compact('datosCertificacion', 'compras'));
    }

    //*** Admin / T-Mentorings / Reportes / Estudiantes ***//
    public function students(Request $request){
        $certificaciones = Certification::where('status', '>', 1)
                                ->withCount('students')
                                ->orderBy('students_count', 'DESC')
                                ->get();

        return view('admins.certifications.reports.students')->with(compact('certificaciones'));
    }

     //*** Admin / T-Mentorings / Reportes / Estudiantes / Ver Reporte Detallado ***//
    public function show_students($curso){
        $datosCertificacion = Certification::where('id', '=', $curso)
                        ->withCount('students')->first();

        return view('admins.certifications.reports.showStudents')->with(compact('datosCertificacion'));
    }

    //*** Admin / T-Mentorings / Reportes / Valoraciones ***//
    public function ratings(Request $request){
        $certificaciones = Certification::where('status', '>', 1)
                                ->withCount(['ratings', 
                                             'ratings as promedio' => function ($query){
                                                $query->select(DB::raw('avg(points)'));
                                            }])->orderBy('promedio', 'DESC')
                                            ->get();

        return view('admins.certifications.reports.ratings')->with(compact('certificaciones'));
    }

     //*** Admin / T-Mentorings / Reportes / Valoraciones / Ver Reporte Detallado ***//
    public function show_ratings($certificacion){
        $datosCertificacion = Certification::where('id', '=', $certificacion)
                                ->withCount(['ratings', 
                                         'ratings as promedio' => function ($query){
                                            $query->select(DB::raw('avg(points)'));
                                        }])->first();

        $valoraciones = Rating::where('certification_id', '=', $certificacion)
                            ->orderBy('created_at', 'DESC')
                            ->get();

        return view('admins.certifications.reports.showRatings')->with(compact('datosCertificacion', 'valoraciones'));
    }

    //*** Instructor / T-Mentorings / Historial de Compras de una T-Mentoring ***//
    public function purchases_record($slug, $id){
        $compras = PurchaseDetail::where('certification_id', '=', $id)
                        ->with('commission')
                        ->orderBy('created_at', 'DESC')
                        ->paginate(25);

        $cantCompras = PurchaseDetail::where('certification_id', '=', $id)
                        ->count();

        return view('instructors.purchases.index')->with(compact('compras', 'cantCompras'));
    }

    //*** Admin / T-Mentorings / T-Mentorings por Instructor ***//
    public function show_by_instructor($slug, $instructor){
        $certificaciones = Certification::where('user_id', '=', $instructor)
                                ->orderBy('created_at', 'DESC')
                                ->paginate(20);

        $totalCertificaciones = Certification::where('user_id', '=', $instructor)
                                    ->count();

        return view('admins.certifications.showByInstructor')->with(compact('certificaciones', 'totalCertificaciones'));
    }
}
