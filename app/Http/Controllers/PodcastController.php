<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str as Str;
use App\Models\Podcast; use App\Models\Category; use App\Models\User; use App\Models\Module;
use App\Models\Lesson; use App\Models\PurchaseDetail; use App\Models\Rating; use App\Models\ResourceFile;
use App\Models\Comment;
use DB; use Auth; use Storage;

class PodcastController extends Controller
{
    //**** Instructor / T-Books
    public function index(){
        //Vista de Instructor
        $podcasts = Podcast::where('user_id', '=', Auth::user()->id)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(8);

        $podcastsConCorrecciones = Podcast::where('user_id', '=', Auth::user()->id)
                                        ->where('status', '=', 4)
                                        ->orderBy('reviewed_at', 'DESC')
                                        ->get();

        $cantPodcastsConCorrecciones = $podcastsConCorrecciones->count();

        $nuevasDiscusiones = collect();

        $instructor = User::where('id', '=', Auth::user()->id)
                        ->with(['podcasts',
                            'podcasts.discussions' => function ($query){
                                $query->where('status', '=', 0);
                            }
                        ])->first();

        foreach ($instructor->podcasts as $podcast){
            foreach ($podcast->discussions as $discusion){
                $nuevasDiscusiones->push($discusion);
            }
        }

        $cantNuevasDiscusiones = $nuevasDiscusiones->count();
        $nuevasDiscusiones = $nuevasDiscusiones->sortByDesc('created_at');


        $nuevosComentarios = Comment::join('discussions', 'comments.discussion_id', '=', 'discussions.id')
                                ->join('podcasts', 'discussions.podcast_id', '=', 'podcasts.id')
                                ->join('users', 'discussions.user_id', '=', 'users.id')
                                ->where('comments.status', '=', 0)
                                ->where('discussions.status', '=', 1)
                                ->where('podcasts.user_id', '=', Auth::user()->id)
                                ->select('users.avatar', 'users.names', 'users.last_names', 'discussions.slug', 'discussions.title', 'comments.discussion_id', DB::raw('COUNT(*) as total_comentarios'))
                                ->groupBy('comments.discussion_id')
                                ->get();

        $cantNuevosComentarios = $nuevosComentarios->count();

        return view('instructors.podcasts.index')->with(compact('podcasts', 'podcastsConCorrecciones', 'cantPodcastsConCorrecciones', 'nuevasDiscusiones', 'cantNuevasDiscusiones',  'nuevosComentarios', 'cantNuevosComentarios'));
    }

    //**** Instructor / T-Books / Nuevo T-Books
    public function create(){
        $categorias = DB::table('categories')
                        ->select('id', 'title', 'icon')
                        ->orderBy('id', 'ASC')
                        ->get();

        $etiquetas = DB::table('tags')
                        ->orderBy('tag', 'ASC')
                        ->get();

        return view('instructors.podcasts.create')->with(compact('categorias', 'etiquetas'));
    }

     //**** Instructor / T-Books / Nuevo T-Book / Guardar
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
            return redirect('instructors/t-books/create')
                ->withErrors($validator)
                ->withInput();
        }

        $podcast = new Podcast($request->all());
        $podcast->user_id = Auth::user()->id;
        $podcast->slug = Str::slug($podcast->title);
        if (is_null($request->price)){
            $podcast->price = 0;
        }else{
            $podcast->price = $request->price;
        }

        $podcast->save();
        
        if (!is_null($request->tags)){
            foreach ($request->tags as $tag){
                DB::table('podcasts_tags')->insert(
                    ['podcast_id' => $podcast->id, 'tag_id' => $tag]
                );
            }
        }

        $this->save_search_keys($podcast->id);

        return redirect('instructors/t-books/edit/'.$podcast->slug.'/'.$podcast->id)->with('msj-exitoso', 'El T-Book ha sido creado con éxito. Por favor proceda a cargar el contenido multimedia.');
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

    //**** Invitado - Estudiante - Instructor /  T-Books / Ver T-Book ***//
    //**** Admin /  T-Books / Ver - Editar T-Book ***//
    public function show($slug, $id){
        $podcast = Podcast::where('id', '=', $id)
                        ->with('tags')
                        ->withCount(['students', 'resources', 
                            'ratings' => function($query){
                                $query->orderBy('created_at', 'DESC');
                            }, 
                            'ratings as promedio' => function ($query2){
                                $query2->select(DB::raw('avg(points)'));
                            }
                        ])->first();

        $podcast->avg = explode('.', $podcast->promedio);

        $instructor = User::where('id', '=', $podcast->user_id)
                            ->first();

        if (Auth::guest()){
            return view('landing.showPodcast')->with(compact('podcast', 'instructor'));
        }else{
            if (Auth::user()->role_id == 1){

                //Vista de Estudiantes  
                $agregado = Auth::user()->podcasts_students()->where('podcast_id', '=', $id)->count();

                if ($agregado == 0){
                    return view('landing.showPodcast')->with(compact('podcast', 'instructor'));
                }else{
                   return redirect('students/t-books/resume/'.$slug.'/'.$id);
                } 
            }else if (Auth::user()->role_id == 2){
                return view('landing.showPodcast')->with(compact('podcast', 'instructor'));
            }else if (Auth::user()->role_id == 3){
                $podcast = Podcast::find($id);

                $etiquetasActivas = [];
                foreach ($podcast->tags as $etiq){
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
                                    ->where('category_id', '=', $podcast->category_id)
                                    ->orderBy('id', 'ASC')
                                    ->get();

                return view('admins.podcasts.show')->with(compact('podcast', 'categorias', 'subcategorias', 'etiquetasActivas', 'etiquetas'));
            }
        }
    }

    //**** Instructor /  T-Books / Editar T-Book ***//
    public function edit($slug, $id){
        $podcast = Podcast::find($id);

        $etiquetasActivas = [];
        foreach ($podcast->tags as $etiq){
            array_push($etiquetasActivas, $etiq->id);
        }

        $cantRecursos = $podcast->resources->count();

        $categorias = DB::table('categories')
                        ->select('id', 'title')
                        ->orderBy('id', 'ASC')
                        ->get();

        $subcategorias = DB::table('subcategories')
                            ->select('id', 'title')
                            ->where('category_id', '=', $podcast->category_id)
                            ->get();

        $etiquetas = DB::table('tags')
                        ->orderBy('tag', 'ASC')
                        ->get();

        return view('instructors.podcasts.edit')->with(compact('podcast', 'cantRecursos', 'categorias', 'subcategorias', 'etiquetasActivas', 'etiquetas'));
    }

    //**** Instructor /  T-Books / Editar T-Book / Guardar Cambios ***//
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

            $podcast = Podcast::find($request->podcast_id);

            if ($validator->fails()) {
                if (Auth::user()->role_id == 2){
                    return redirect('instructors/t-books/edit/'.$podcast->slug.'/'.$request->podcast_id)
                        ->withErrors($validator)
                        ->withInput();
                }else if (Auth::user()->role_id == 3){
                    return redirect('admins/t-books/show/'.$podcast->slug.'/'.$request->podcast_id)
                        ->withErrors($validator)
                        ->withInput();
                }
            }
            
            $podcast->fill($request->all());
            $podcast->slug = Str::slug($podcast->title);

            DB::table('podcasts_tags')
                ->where('podcast_id', '=', $podcast->id)
                ->delete();

            if (!is_null($request->tags)){
                foreach ($request->tags as $tag){
                    DB::table('podcasts_tags')->insert(
                        ['podcast_id' => $podcast->id, 'tag_id' => $tag]
                    );
                }
            }


            if ($request->hasFile('cover')){
                $file = $request->file('cover');
                $name = $podcast->id.".".$file->getClientOriginalExtension();
                $file->move(public_path().'/uploads/images/podcasts', $name);
                $podcast->cover = $name;
                $podcast->cover_name = $file->getClientOriginalName();
            }

            $podcast->save();

            $this->save_search_keys($podcast->id);

            if (Auth::user()->role_id == 2){
                return redirect('instructors/t-books/edit/'.$podcast->slug.'/'.$request->podcast_id)->with('msj-exitoso', 'Los datos del T-Book han sido actualizados con éxito');
            }else if (Auth::user()->role_id == 3){
                return redirect('admins/t-books/show/'.$podcast->slug.'/'.$request->podcast_id)->with('msj-exitoso', 'Los datos del T-Book han sido actualizados con éxito');
            }
        }else{
            $podcast = Podcast::find($request->podcast_id);

            if (isset($request->file_type)){
                if ($request->file_type == 'cover'){
                    $imagen = public_path().'/uploads/images/podcasts/'.$podcast->cover;
                    if (getimagesize($imagen)) {
                        unlink($imagen);
                    }

                    $podcast->cover = NULL;
                    $podcast->cover_name = NULL;
                }else if ($request->file_type == 'preview'){
                    $nombreArchivo = explode("previews/", $podcast->preview);
                    if (Storage::disk('s3')->has('podcasts/previews/'.$nombreArchivo[1])){
                        Storage::disk('s3')->delete('podcasts/previews/'.$nombreArchivo[1]);
                    }
                    $podcast->preview = NULL;
                    $podcast->preview_name = NULL;
                }
            }else{
                if ($request->hasFile('cover')){
                    if (!is_null($podcast->cover)){
                       $imagen = public_path().'/uploads/images/podcasts/'.$podcast->cover;
                        if (getimagesize($imagen)) {
                            unlink($imagen);
                        }
                    }

                    $file = $request->file('cover');
                    $name = time().$file->getClientOriginalName();
                    $file->move(public_path().'/uploads/images/podcasts', $name);
                    $podcast->cover = $name;
                    $podcast->cover_name = $file->getClientOriginalName();
                }else if ($request->hasFile('preview')){
                    if (!is_null($podcast->preview)){
                        $nombreArchivo = explode("podcasts/".$podcast->id."/preview/", $podcast->preview);
                        if (Storage::disk('s3')->has('podcasts/'.$podcast->id."/preview/".$nombreArchivo[1])){
                            Storage::disk('s3')->delete('podcasts/'.$podcast->id."/preview/".$nombreArchivo[1]);
                        }
                    }

                    $file = $request->file('preview');
                    $upload = Storage::disk('s3')->put('podcasts/'.$podcast->id.'/preview', $file, 'public');
                    $podcast->preview = 'https://transformate-videos.s3.us-east-2.amazonaws.com/'.$upload;
                    $podcast->preview_name = $file->getClientOriginalName();
                }else if ($request->hasFile('audio_file')){
                    if (!is_null($podcast->audio_file)){
                        $nombreArchivo = explode("podcasts/".$podcast->id."/", $podcast->audio_file);
                        if (Storage::disk('s3')->has('podcasts/'.$podcast->id."/".$nombreArchivo[1])){
                            Storage::disk('s3')->delete('podcasts/'.$podcast->id."/".$nombreArchivo[1]);
                        }
                    }
                    
                    $file = $request->file('audio_file');
                    $upload = Storage::disk('s3')->put('podcasts/'.$podcast->id, $file, 'public');
                    $podcast->audio_file = 'https://transformate-videos.s3.us-east-2.amazonaws.com/'.$upload;
                    $podcast->audio_filename = $file->getClientOriginalName();
                }
            }

            $podcast->save();

            return response()->json(
                "Operación Exitosa."
            );
        }
    }

    public function load_resource(Request $request){
        $recurso = new ResourceFile();
        $recurso->podcast_id = $request->podcast;
        $recurso->filename = $request->nombre_archivo;
        $recurso->file_extension = $request->extension;
        $recurso->file_icon = $this->setIcon($recurso->file_extension);
        $recurso->link = 'https://transformate-videos.s3.us-east-2.amazonaws.com/'.$request->direccion; 
        $recurso->save();

        return response()->json(
            true
        );
    }

    //** Instructor / Listado de recursos de un T-Book 
    //Método Ajax que actualiza después de cargar un nuevo recurso)
    public function resources($podcast){
        $recursos = ResourceFile::where('podcast_id', '=', $podcast)
                        ->get();

        $cantRecursos = $recursos->count();

        return view('instructors.podcasts.downloadResources')->with(compact('recursos', 'cantRecursos'));
    }

    //** Instructor / Editar T-Book / Eliminar Recurso Descargable 
    public function delete_resource(Request $request){
        $recurso = ResourceFile::find($request->resource_id);

        $nombreArchivo = explode("resources/", $recurso->link);
        if (Storage::disk('s3')->has('podcasts/'.$recurso->podcast_id.'/resources/'.$nombreArchivo[1])){
            Storage::disk('s3')->delete('podcasts/'.$recurso->podcast_id.'/resources/'.$nombreArchivo[1]);
        }
        
        $recurso->delete();

        return response()->json(
            true
        );
    }

    //**** Instructor /  T-Books / Enivar T-Book a Revisión ***//
    public function publish($id){
        DB::table('podcasts')
            ->where('id', '=', $id)
            ->update(['status' => 1,
                      'sent_for_review' => date('Y-m-d')]);

        //**** Notificar al Administrador ***//
        $notificacion = new NotificationController();
        $notificacion->store(1, 'Nueva Solicitud de Publicación', 'Un instructor ha enviado su T-Book a revisión para publicación', 'fa fa-check', 'admins/t-books/pending');

        return redirect('instructors/t-books')->with('msj-exitoso', 'El T-Book ha sido enviado a revisión para su publicación.');
    }

    //*** Estudiante / T-Books / Continuar T-Book ***//
    //*** Admin / T-Books / Resumen T-Book ***//
    public function resume($slug, $id){
       $podcast = Podcast::where('id', '=', $id)
                    ->with(['students' => function ($query2){
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
        
        $promedio = explode('.', $podcast->promedio);

        $instructor = User::where('id', '=', $podcast->user_id)
                        ->withCount('podcasts')
                        ->first(); 

        $podcastsRelacionados = DB::table('podcasts')
                                ->where('category_id', '=', $podcast->category_id)
                                ->where('id', '<>', $id)
                                ->orderBy('created_at', 'DESC')
                                ->take(2)
                                ->get();


        if (Auth::user()->role_id == 1){
            $miValoracion = DB::table('ratings')
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('podcast_id', '=', $id)
                                ->first(); 

            return view('students.podcasts.resume')->with(compact('podcast', 'promedio', 'instructor', 'miValoracion', 'podcastsRelacionados'));
        }else{
            return view('admins.podcasts.resume')->with(compact('podcast', 'promedio', 'instructor', 'podcastsRelacionados'));
        }
    }

    //*** Estudiante / T-Books / Lecciones / Actualizar Progreso del T-Book al finalizar una lección ***//
    //*** Función llamada desde el método "actualizar_progreso_leccion (AjaxController)" ***//
    public function update_progress($podcast){
        $modulosPodcast = DB::table('modules')
                            ->select('id')
                            ->where('podcast_id', '=', $podcast)
                            ->get();

        $cantLecciones = 0;
        foreach ($modulosPodcast as $modulo){
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
            DB::table('podcasts_students')
                ->where('user_id', '=', Auth::user()->id)
                ->where('podcast_id', '=', $podcast)
                ->update(['progress' => number_format($progreso),
                          'ending_date' => date('Y-m-d')]);
        }else{
            DB::table('podcasts_students')
                ->where('user_id', '=', Auth::user()->id)
                ->where('podcast_id', '=', $podcast)
                ->update(['progress' => number_format($progreso)]);
        }
    }

    //*** Admin / T-Books / Cursos Creados ***//
    public function podcasts_record(Request $request){
        $podcasts = Podcast::where('status', '=', 0)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.podcasts.index')->with(compact('podcasts'));
    }

    //*** Admin / T-Books / Podcasts Publicados ***//
    public function published_record(Request $request){
        $podcasts = Podcast::where('status', '=', 2)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.podcasts.published')->with(compact('podcasts'));
    }

    //*** Admin / T-Books / Pendientes Para Publicación ***//
    public function pending_for_publication(Request $request){
        $podcasts = Podcast::where('status', '=', 1)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.podcasts.pendingForPublication')->with(compact('podcasts'));
    }

    //*** Admin / T-Books / Deshabilitados ***//
    public function disabled_record(Request $request){
        $podcasts = Podcast::where('status', '=', 3)
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.podcasts.disabledRecord')->with(compact('podcasts'));
    }

    //*** Admin / T-Books / Reportes / Ventas ***//
    public function sales(Request $request){
        $podcasts = Podcast::where('status', '>', 1)
                        ->withCount('purchases')
                        ->orderBy('purchases_count', 'DESC')
                        ->get();

        return view('admins.podcasts.reports.sales')->with(compact('podcasts'));
    }

    //*** Admin / T-Books / Reportes / Ventas / Ver Reporte Detallado ***//
    public function show_sales($podcast){
        $datosPodcast = Podcast::where('id', '=', $podcast)
                            ->select('title')
                            ->first();

        $compras = PurchaseDetail::where('podcast_id', '=', $podcast)
                        ->orderBy('created_at', 'DESC')
                        ->get();
        return view('admins.podcasts.reports.showSales')->with(compact('datosPodcast', 'compras'));
    }

    //*** Admin / T-Books / Reportes / Estudiantes ***//
    public function students(Request $request){
        $podcasts = Podcast::where('status', '>', 1)
                    ->withCount('students')
                    ->orderBy('students_count', 'DESC')
                    ->get();

        return view('admins.podcasts.reports.students')->with(compact('podcasts'));
    }

     //*** Admin / T-Books / Reportes / Estudiantes / Ver Reporte Detallado ***//
    public function show_students($podcast){
        $datosPodcast = Podcast::where('id', '=', $podcast)
                        ->withCount('students')->first();

        return view('admins.podcasts.reports.showStudents')->with(compact('datosPodcast'));
    }

    //*** Admin / T-Books / Reportes / Valoraciones ***//
    public function ratings(Request $request){
        $podcasts = Podcast::where('status', '>', 1)
                    ->withCount(['ratings', 
                                 'ratings as promedio' => function ($query){
                                    $query->select(DB::raw('avg(points)'));
                                }])->orderBy('promedio', 'DESC')
                                ->get();

        return view('admins.podcasts.reports.ratings')->with(compact('podcasts'));
    }

     //*** Admin / T-Books / Reportes / Valoraciones / Ver Reporte Detallado ***//
    public function show_ratings($podcast){
        $datosPodcast = Podcast::where('id', '=', $podcast)
                            ->withCount(['ratings', 
                                     'ratings as promedio' => function ($query){
                                        $query->select(DB::raw('avg(points)'));
                                    }])->first();

        $valoraciones = Rating::where('podcast_id', '=', $podcast)
                            ->orderBy('created_at', 'DESC')
                            ->get();

        return view('admins.podcasts.reports.showRatings')->with(compact('datosPodcast', 'valoraciones'));
    }

    //*** Instructor / T-Books / Historial de Compras de un Podcas ***//
    public function purchases_record($slug, $id){
        $compras = PurchaseDetail::where('podcast_id', '=', $id)
                        ->with('commission')
                        ->orderBy('created_at', 'DESC')
                        ->paginate(25);

        $cantCompras = PurchaseDetail::where('podcast_id', '=', $id)
                        ->count();

        return view('instructors.purchases.index')->with(compact('compras', 'cantCompras'));
    }

    //*** Admin / T-Books / T-Books por Instructor ***//
    public function show_by_instructor($slug, $instructor){
        $podcasts = Podcast::where('user_id', '=', $instructor)
                                ->orderBy('created_at', 'DESC')
                                ->paginate(20);

        $totalPodcasts = Podcast::where('user_id', '=', $instructor)
                                    ->count();

        return view('admins.podcasts.showByInstructor')->with(compact('podcasts', 'totalPodcasts'));
    }

    public function save_search_keys($podcast){
        $podcast = Podcast::find($podcast);

        $etiquetas = "";
        foreach ($podcast->tags as $tag){
            $etiquetas = $etiquetas." ".$tag->tag;
        }
        $podcast->search_keys = $podcast->title." ".$podcast->subtitle." ".$podcast->user->names." ".$podcast->user->last_names." ".$podcast->category->title." ".$podcast->subcategory->title." ".$etiquetas;
        $podcast->save();
    }
}
