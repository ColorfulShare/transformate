<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category; 
use App\Models\Subcategory; 
use App\Models\Course; 
use App\Models\User; 
use App\Models\PurchaseDetail;
use App\Models\Certification; 
use App\Models\Podcast; 
use App\Models\Tag; 
use App\Models\MasterClass;
use Auth; use DB;

class LandingController extends Controller
{
    public function contact_us(Request $request){
        $messages = [
            'recaptcha' => 'Debe validar su captcha haciendo clic en "No soy un robot"',
        ];

        $validator = Validator::make($request->all(), [
            recaptchaFieldName() => recaptchaRuleName()
        ], $messages);

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

        $data['email'] = $request->email;
        $data['name'] = $request->name;
        $data['subject'] = $request->subject;
        $data['message'] = $request->message;

        Mail::send('emails.contactUs',['data' => $data], function($msg) use ($data){
            $msg->to('mentorestransformate@gmail.com');
            $msg->subject($data['subject']);
        });

        return redirect('/')->with('msj-exitoso', 'Tu mensaje ha sido enviado con éxito');
    }
    
    public function index2(){
        $url = explode("www", \Request::url());
        if (count($url) > 1){
            $www = 1;
        }else{
            $www = 0;
        }

        $categoriasHome = Category::withCount('courses')
                            ->orderBy('id', 'ASC')
                            ->get();
        
        $cursosID = [];
        $cursosDestacados = Course::where('status', '=', 2)
                                ->where('featured', '=', 1)
                                ->orderBy('created_at', 'DESC')
                                ->take(4)
                                ->get();

        foreach ($cursosDestacados as $cursoDestacado){
            array_push($cursosID, $cursoDestacado->id);
        }

        $cursosVendidos = PurchaseDetail::with('course', 'course.user')
                                ->select('purchase_details.course_id', DB::raw('count(*) as total'))
                                ->where('course_id', '<>', NULL)
                                ->groupBy('course_id')
                                ->orderBy('total', 'DESC')
                                ->take(4)
                                ->get();

        foreach ($cursosVendidos as $cursoVendido){
            array_push($cursosID, $cursoVendido->course_id);
        }
        
        $cursosRecomendados = Course::where('status', '=', 2)
                                ->whereNotIn('id', $cursosID)
                                ->orderByRaw('rand()')
                                ->take(4)
                                ->get();

        
        $cursosAgregados = NULL;

        if ( (!Auth::guest()) && (Auth::user()->role_id == 1) ){
            $misCursos = DB::table('courses_students')
                                ->where('user_id', '=', Auth::user()->id)
                                ->get();

            $cursosAgregados = array();
            foreach ($misCursos as $miCurso){
                array_push($cursosAgregados, $miCurso->course_id);
            }  
        }

        $categoriaSeleccionada = 1;

        $eventos = DB::table('events')
                    ->where('status', '=', 1)
                    ->orderBy('date', 'ASC')
                    ->get();
        
        $cantEventos = $eventos->count();

        $cantMasterClass = DB::table('master_class')
                                ->where('status', '=', 1)
                                ->count();

        $cantPodcasts = DB::table('podcasts')
                                ->where('status', '=', 2)
                                ->count();

        return view('landing.indexNew')->with(compact('cursosDestacados', 'cursosVendidos', 'cursosRecomendados', 'categoriasHome', 'cursosAgregados', 'www', 'categoriaSeleccionada', 'eventos', 'cantEventos', 'cantMasterClass', 'cantPodcasts'));
    }
    
    /** Landing / Home **/
    public function index(){
        $url = explode("www", \Request::url());
        if (count($url) > 1){
            $www = 1;
        }else{
            $www = 0;
        }

        $categorias = DB::table('categories')
                        ->select('id', 'title', 'slug')
                        ->orderBy('id', 'ASC')
                        ->get();

        $cursos = Course::withCount('students')
                    ->where('status', '=', 2)
                    ->where('category_id', '=', 1)
                    ->orderBy('created_at', 'DESC')
                    ->get();

        $cantCursos = $cursos->count();

        $cursosMasVendidos = PurchaseDetail::with('course')
                                ->select('purchase_details.course_id', DB::raw('count(*) as total'))
                                ->where('course_id', '<>', NULL)
                                ->groupBy('course_id')
                                ->orderBy('total', 'DESC')
                                ->take(8)
                                ->get();
        
        $cursosAgregados = NULL;

        if ( (!Auth::guest()) && (Auth::user()->role_id == 1) ){
            $misCursos = DB::table('courses_students')
                                ->where('user_id', '=', Auth::user()->id)
                                ->get();

            $cursosAgregados = array();
            foreach ($misCursos as $miCurso){
                array_push($cursosAgregados, $miCurso->course_id);
            }  
        }

        $categoriaSeleccionada = 1;

        $eventos = DB::table('events')
                    ->where('status', '=', 1)
                    ->orderBy('date', 'ASC')
                    ->get();
        
        $cantEventos = $eventos->count();

        return view('landing.index')->with(compact('cursos', 'cursosMasVendidos', 'categorias', 'cursosAgregados', 'cantCursos', 'www', 'categoriaSeleccionada', 'eventos', 'cantEventos'));
    }

    /** Landing / T- Courses **/
    public function courses($slug = NULL, $categoria = 1){
        $url = explode("www", \Request::url());
        if (count($url) > 1){
            $www = 1;
        }else{
            $www = 0;
        }

        if ($categoria == 100){
            $cursos = MasterClass::where('status', '=', 1)
                        ->orderBy('id', 'DESC')
                        ->get();

            $libros = NULL;
            $cantCursos = $cursos->count();
            $cantLibros = 0;
        }elseif ($categoria == 0){
            $cursos = NULL;

            $libros = Podcast::where('status', '=', 2)
                        ->orderBy('id', 'DESC')
                        ->get();

            $cantCursos = 0;
            $cantLibros = $libros->count();
        }else{
            $cursos = Course::where('category_id', '=', $categoria)
                        ->where('status', '=', 2)
                        ->orderBy('id', 'DESC')
                        ->get();

            $libros = Podcast::where('category_id', '=', $categoria)
                        ->where('status', '=', 2)
                        ->orderBy('id', 'DESC')
                        ->get();

            $cantCursos = $cursos->count();
            $cantLibros = $libros->count();
        }

        $categoriaSeleccionada = $categoria;

        $cursosRegalo = 0;
        if ( (!Auth::guest()) && (Auth::user()->role_id == 1) ){
            $cursosRegalo = DB::table('gifts')
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('checked', '=', 0)
                                ->count();
        }

        return view('landing.courses')->with(compact('cursos', 'cantCursos', 'libros', 'cantLibros', 'categoriaSeleccionada', 'www', 'cursosRegalo'));
    }

    /** Landing / T-Mentor **/
    public function t_mentor(){
        return view('landing.tMentor');
    }

    //*** T- Member ***//
    public function t_member(){
        return view('landing.tMember');
    }

    /** Header / Búsqueda Personalizada **/
    public function search(Request $request){
        $cursosRelacionados = Course::where('search_keys', 'like', "%{$request->get('busqueda')}%")
                                    ->where('status', '=', 2)
                                    ->get();

        $librosRelacionados = Podcast::where('search_keys', 'like', "%{$request->get('busqueda')}%")
                                    ->where('status', '=', 2)
                                    ->get();
        

        return view('landing.search')->with(compact('cursosRelacionados', 'librosRelacionados'));
    }

    public function search_certifications_by_category($slug, $categoria){
        $categoria = Category::where('id', '=', $categoria)
                        ->with(['certifications' => function ($query){
                            $query->where('status', '=', 2);
                        }, 'subcategories', 'subcategories.certifications' => function ($query2){
                            $query2->where('certifications.status', '=', 2);
                        }])
                        ->first();

        $cantCertificacionesCat = 0;
        foreach ($categoria->certifications as $cer){
            if ($cer->status == 2){
                $cantCertificacionesCat++;
            }  
        }
        $categoria->certifications_count = $cantCertificacionesCat;

        foreach ($categoria->subcategories as $subcategoria){
            $subcategoria->certifications_count = DB::table('certifications')
                                                    ->where('subcategory_id', '=', $subcategoria->id)
                                                    ->where('status', '=', 2)
                                                    ->count();
        }

        if (Auth::guest()){
            $certificacionesAgregadas = NULL;
        }else{
            if (Auth::user()->role_id == 1){
                $misCertificaciones = DB::table('certifications_students')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->get();

                $certificacionesAgregadas = array();
                foreach ($misCertificaciones as $miCertificacion){
                    array_push($certificacionesAgregadas, $miCertificacion->certification_id);
                } 
            }else{
                $certificacionesAgregadas = NULL;
            }
        }

        return view('landing.certificationsByCategory')->with(compact('categoria', 'certificacionesAgregadas'));
    }

    public function search_podcasts_by_category($slug, $categoria){
        $categoria = Category::where('id', '=', $categoria)
                        ->with(['podcasts' => function ($query){
                            $query->where('status', '=', 2);
                        }, 'subcategories', 'subcategories.podcasts' => function ($query2){
                            $query2->where('podcasts.status', '=', 2);
                        }])
                        ->first();
        $cantPodcastsCat = 0;
        foreach ($categoria->podcasts as $pod){
            if ($pod->status == 2){
                $cantPodcastsCat++;
            }  
        }
        $categoria->podcasts_count = $cantPodcastsCat;

        foreach ($categoria->subcategories as $subcategoria){
            $subcategoria->podcasts_count = DB::table('podcasts')
                                                ->where('subcategory_id', '=', $subcategoria->id)
                                                ->where('status', '=', 2)
                                                ->count();
        }

        if (Auth::guest()){
            $podcastsAgregados = NULL;
        }else{
            if (Auth::user()->role_id == 1){
                $misPodcasts = DB::table('podcasts_students')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->get();

                $podcastsAgregados = array();
                foreach ($misPodcasts as $miPodcast){
                    array_push($podcastsAgregados, $miPodcast->podcast_id);
                } 
            }else{
                $podcastsAgregados = NULL;
            }
        }

        return view('landing.podcastsByCategory')->with(compact('categoria', 'podcastsAgregados'));
    }

    public function show_instructor_profile($slug, $id){
        $instructor = User::where('id', '=', $id)
                        ->with(['courses' => function($query){
                            $query->where('status', '=', 2);
                        }, 'certifications' => function ($query2){
                            $query2->where('status', '=', 2);
                        }, 'podcasts' => function ($query3){
                            $query3->where('status', '=', 2);
                        }])->withCount('courses', 'certifications', 'podcasts')
                        ->first();

        $cantEstudiantes = 0;
        if ($instructor->courses_count > 0){
            foreach ($instructor->courses as $curso){
                $cant = DB::table('courses_students')
                            ->where('course_id', '=', $curso->id)
                            ->count();

                $cantEstudiantes += $cant;
            }
        }

        if ($instructor->certifications_count > 0){
            foreach ($instructor->certifications as $certificacion){
                $cant = DB::table('certifications_students')
                            ->where('certification_id', '=', $certificacion->id)
                            ->count();

                $cantEstudiantes += $cant;
            }
        }

        if ($instructor->podcasts_count > 0){
            foreach ($instructor->podcasts as $podcast){
                $cant = DB::table('podcasts_students')
                            ->where('podcast_id', '=', $podcast->id)
                            ->count();

                $cantEstudiantes += $cant;
            }
        }

        return view('landing.showInstructorProfile')->with(compact('instructor', 'cantEstudiantes'));
    }
}
