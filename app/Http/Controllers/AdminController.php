<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;
use App\Models\User; use App\Models\Course; use App\Models\Certification; use App\Models\Podcast; use App\Models\PurchaseDetail; use App\Models\Commission; use App\Models\Purchase;
use App\Models\Transaction;
use Carbon\Carbon;
use DB; use Auth; use Hash; use Route; use Mail;

class AdminController extends Controller
{
    //*** Admin / Inicio ***//
    public function home(){
        $ventas = DB::table('purchases')
                    ->select(DB::raw('SUM(amount) as ventas'), DB::raw('MONTH(date) AS mes'))
                    ->groupBy('mes')
                    ->orderBy('mes', 'asc')
                    ->get();

        foreach ($ventas as $venta){
            $mes = $this->getMonthName($venta->mes);
            $venta->mes = $mes;
        }

        $estudiantes = DB::table('users')
                            ->where('role_id', '=', 1)
                            ->count();

        $mentores = DB::table('users')
                        ->where('role_id', '=', 2)
                        ->count();

        $usuarios = collect();

        $dataEstudiantes = new \stdClass;
        $dataEstudiantes->usuario = 'Estudiantes';
        $dataEstudiantes->cantidad = $estudiantes;

        $dataMentores = new \stdClass;
        $dataMentores->usuario = 'Mentores';
        $dataMentores->cantidad = $mentores;

        $usuarios->push($dataEstudiantes);
        $usuarios->push($dataMentores);

        $cursosMasComprados = PurchaseDetail::with('course')
                                ->select('purchase_details.course_id', DB::raw('count(*) as total'))
                                ->where('course_id', '<>', NULL)
                                ->groupBy('course_id')
                                ->orderBy('total', 'DESC')
                                ->take(6)
                                ->get();

        $mentoresMasGanancias = Commission::with('user')
                                    ->select('commissions.user_id', DB::raw('SUM(amount) as ganancia'))
                                    ->where('user_id', '<>', NULL)
                                    ->groupBy('user_id')
                                    ->orderBy('ganancia', 'DESC')
                                    ->take(6)
                                    ->get();
       
    	return view('admins.index')->with(compact('ventas', 'usuarios', 'cursosMasComprados', 'mentoresMasGanancias'));
    }

    public function getMonthName($month){
        switch ($month) {
            case 1:
                $mes = 'Enero';
            break;
            case 2:
                $mes = 'Febrero';
            break;
            case 3:
                $mes = 'Marzo';
            break;
            case 4:
                $mes = 'Abril';
            break;
            case 5:
                $mes = 'Mayo';
            break;
            case 6:
                $mes = 'Junio';
            break;
            case 7:
                $mes = 'Julio';
            break;
            case 8:
                $mes = 'Agosto';
            break;
            case 9:
                $mes = 'Septiembre';
            break;
            case 10:
                $mes = 'Octubre';
            break;
            case 11:
                $mes = 'Noviembre';
            break;
            case 12:
                $mes = 'Diciembre';
            break;
        }

        return $mes;
    }

    //*** Admin / Usuarios / Administradores ***//
    public function index(Request $request){
        $administradores = User::where('role_id', '=', 3)
                            ->where('id', '<>', Auth::user()->id)
                            ->orderBy('names', 'ASC')
                            ->get();

        $perfiles = DB::table('profiles')
                        ->select('id', 'name')
                        ->where('status', '=', 1)
                        ->orderBy('name', 'ASC')
                        ->get();

        return view('admins.users.administrators')->with(compact('administradores', 'perfiles'));
    }

    //*** Admin / Mi Cuenta ***//
    public function my_profile(){
        return view('admins.myProfile');
    }

    //*** Admin / Mi Cuenta / Actualizar Datos ***//
    public function update_profile(Request $request){
        $usuario = User::find(Auth::user()->id);
        $usuario->fill($request->all());

        $nombre = $usuario->names." ".$usuario->last_names;
        $usuario->slug = Str::slug($nombre);
        $pic = explode("-", $usuario->slug);
        $usuario->afiliate_code = $pic[0]."pro".$usuario->id;
        $usuario->partner_code = $pic[0]."tp".$usuario->id;

        if ($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/uploads/images/users', $name);
            $usuario->avatar = $name;
        }

        if ($request->clave_actual != NULL){
            if (Auth::attempt(['email' => Auth::user()->email, 'password' => $request->clave_actual])) {
                $usuario->password = Hash::make($request->clave);
            }else{
                return redirect('admins/profile')->with('msj-error', 'La clave ingresada es incorrecta.');
            }
        }

        $usuario->save();

        return redirect('admins/profile')->with('msj-exitoso', 'Sus datos han sido actualizados con éxito.');
    }

    //*** Admin / Usuarios / Nuevo Usuario ***//
    public function store_user(Request $request){
        $nombreCompleto = $request->names." ".$request->last_names;

        $usuario = new User($request->all());
        $usuario->password = Hash::make($request->password);
        $usuario->slug = Str::slug($nombreCompleto);
        $usuario->afiliate_code = 0;
        $usuario->save();

        $pic = explode("-", $usuario->slug);
        $usuario->afiliate_code = $pic[0]."pro".$usuario->id;
        $usuario->partner_code = $pic[0]."tp".$usuario->id;
        $usuario->save();

        if ($request->role_id == 1){
            return redirect('admins/users/students')->with('msj-exitoso', 'El alumno ha sido creado con éxito.');
        }else if ($request->role_id == 2){
            return redirect('admins/users/instructors')->with('msj-exitoso', 'El instructor ha sido creado con éxito.');
        }else if ($request->role_id == 3){
            return redirect('admins/users/administrators')->with('msj-exitoso', 'El administrador ha sido creado con éxito.');
        }else{
            return redirect('admins/users/t-partners')->with('msj-exitoso', 'El t-partner ha sido creado con éxito.');
        }
    }

    //*** Admin / Usuarios / Ver - Editar Usuario ***//
    public function show_user($id){
        $usuario = User::find($id);

        $perfiles = DB::table('profiles')
                        ->select('id', 'name')
                        ->where('status', '=', 1)
                        ->orderBy('name', 'ASC')
                        ->get();

        return view('admins.users.show')->with(compact('usuario', 'perfiles'));
    }

    //**** Admin / Usuarios / Ver - Editar Usuario / Guardar Cambios ***//
    public function update_user(Request $request){
        $usuario = User::find($request->user_id);
        $usuario->fill($request->all());

        $nombre = $usuario->names." ".$usuario->last_names;
        $usuario->slug = Str::slug($nombre);
        $pic = explode("-", $usuario->slug);
        $usuario->afiliate_code = $pic[0]."pro".$usuario->id;
        $usuario->partner_code = $pic[0]."tp".$usuario->id;

        if (isset($request->password)){
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect('admins/users/show/'.$request->user_id)->with('msj-exitoso', 'Los datos del usuario han sido modificados con éxito');
    }

    //**** Admin / Usuarios / Eliminar - Restaurar Usuario ***//
    public function change_status_user(Request $request){
        DB::table('users')
            ->where('id', '=', $request->user_id)
            ->update(['status' => $request->action]);

        if ($request->action == 0){
            return redirect('admins/users/students')->with('msj-exitoso', 'El usuario ha sido inhabilitado con éxito');
        }else{
            return redirect('admins/users/students')->with('msj-exitoso', 'El usuario ha sido restaurado con éxito');
        }
    }

    //**** Admin / T-Courses - T-Mentorings - T-Books / Aprobar o Denegar Publicación - Eliminar - Restaurar ***//
    public function change_status(Request $request){
        $notificacion = new NotificationController();

        if ($request->action == 'Aprobar'){
            if (isset($request->course_id)){
                DB::table('courses')
                    ->where('id', '=', $request->course_id)
                    ->update(['status' => 2,
                              'evaluation_review' => NULL,
                              'reviewed_at' => date('Y-m-d'),
                              'published_at' => date('Y-m-d')]);

                //**** Notificar al Instructor ***//
                $datosCurso = Course::where('id', '=', $request->course_id)
                                ->first();

                $notificacion->store($datosCurso->user_id, 'Nueva Publicación', 'La publicación de su T-Course <b>'.$datosCurso->title.'</b> ha sido aprobada.', 'fa fa-check', 'instructors/t-courses/show/'.$datosCurso->slug.'/'.$request->course_id);

                //**** Notificar a los estudiantes que tienen un curso del instructor ***//
                $cursosInstructor = DB::table('courses')
                                        ->select('id')
                                        ->where('user_id', '=', $datosCurso->user_id)
                                        ->where('id', '<>', $request->course_id)
                                        ->get();

                $estudiantesNotificados = array();
                foreach ($cursosInstructor as $cursoI){
                    $estudiantes = DB::table('courses_students')
                                        ->select('user_id')
                                        ->where('course_id', '=', $cursoI->id)
                                        ->get();

                    foreach ($estudiantes as $estudiante){
                        if (!in_array($estudiante->user_id, $estudiantesNotificados)) {
                            $notificacion->store($estudiante->user_id, 'Nuevo Curso', 'El instructor <b>'.$datosCurso->user->names.' '.$datosCurso->user->last_names.'</b> ha publicado un nuevo T-Course <b>('.$datosCurso->title.')</b>.', 'fas fa-video', 'students/t-courses/show/'.$datosCurso->slug.'/'.$request->course_id);

                            array_push($estudiantesNotificados, $estudiante->user_id);
                        }
                    }
                }

                return redirect('admins/t-courses/pending')->with('msj-exitoso', 'El T-Course ha sido publicado con éxito.');
            }else if (isset($request->certification_id)){
                DB::table('certifications')
                    ->where('id', '=', $request->certification_id)
                    ->update(['status' => 2,
                              'evaluation_review' => NULL,
                              'reviewed_at' => date('Y-m-d'),
                              'published_at' => date('Y-m-d')]);

                
                //**** Notificar al Instructor ***//
                $datosCertificacion= Certification::where('id', '=', $request->certification_id)
                                        ->first();

                $notificacion->store($datosCertificacion->user_id, 'Nueva Publicación', 'La publicación de su T-Mentoring <b>'.$datosCertificacion->title.'</b> ha sido aprobada.', 'fa fa-check', 'instructors/t-mentorings/show/'.$datosCertificacion->slug.'/'.$request->certification_id);

                //**** Notificar a los estudiantes que tienen una certificación del instructor ***//
                $certificacionesInstructor = DB::table('certifications')
                                                ->select('id')
                                                ->where('user_id', '=', $datosCertificacion->user_id)
                                                ->where('id', '<>', $request->certification_id)
                                                ->get();

                $estudiantesNotificados = array();
                foreach ($certificacionesInstructor as $certificacionI){
                    $estudiantes = DB::table('certifications_students')
                                        ->select('user_id')
                                        ->where('certification_id', '=', $certificacionI->id)
                                        ->get();

                    foreach ($estudiantes as $estudiante){
                        if (!in_array($estudiante->user_id, $estudiantesNotificados)) {
                            $notificacion->store($estudiante->user_id, 'Nueva Certificación', 'El instructor <b>'.$datosCertificacion->user->names.' '.$datosCertificacion->user->last_names.'</b> ha publicado una nueva T-Mentoring <b>('.$datosCertificacion->title.')</b>.', 'fas fa-landmark', 'students/t-mentorings/show/'.$datosCertificacion->slug.'/'.$request->certification_id);

                            array_push($estudiantesNotificados, $estudiante->user_id);
                        }
                    }
                }

                return redirect('admins/t-mentorings/pending')->with('msj-exitoso', 'La T-Mentoring ha sido publicada con éxito.');
            }else if (isset($request->podcast_id)){
                DB::table('podcasts')
                    ->where('id', '=', $request->podcast_id)
                    ->update(['status' => 2,
                              'evaluation_review' => NULL,
                              'reviewed_at' => date('Y-m-d'),
                              'published_at' => date('Y-m-d')]);

                //**** Notificar al Instructor ***//
                $datosPodcast = Podcast::where('id', '=', $request->podcast_id)
                                    ->first();

                $notificacion->store($datosPodcast->user_id, 'Nueva Publicación', 'La publicación de su T-Book <b>'.$datosPodcast->title.'</b> ha sido aprobada.', 'fa fa-check', 'instructors/t-books/show/'.$datosPodcast->slug.'/'.$request->podcast_id);

                //**** Notificar a los estudiantes que tienen un podcast del instructor ***//
                $podcastsInstructor = DB::table('podcasts')
                                        ->select('id')
                                        ->where('user_id', '=', $datosPodcast->user_id)
                                        ->where('id', '<>', $request->podcast_id)
                                        ->get();

                $estudiantesNotificados = array();
                foreach ($podcastsInstructor as $podcastI){
                    $estudiantes = DB::table('podcasts_students')
                                        ->select('user_id')
                                        ->where('podcast_id', '=', $podcastI->id)
                                        ->get();

                    foreach ($estudiantes as $estudiante){
                        if (!in_array($estudiante->user_id, $estudiantesNotificados)) {
                            $notificacion->store($estudiante->user_id, 'Nuevo Podcast', 'El instructor <b>'.$datosPodcast->user->names.' '.$datosPodcast->user->last_names.'</b> ha publicado un nuevo T-Book <b>('.$datosPodcast->title.')</b>.', 'fas fa-microphone', 'students/t-books/show/'.$datosPodcast->slug.'/'.$request->course_id);

                            array_push($estudiantesNotificados, $estudiante->user_id);
                        }
                    }
                }

                return redirect('admins/t-books/pending')->with('msj-exitoso', 'El T-Book ha sido publicado con éxito.');
            }
        }else if ($request->action == 'Eliminar'){
            if (isset($request->course_id)){
                DB::table('courses')
                    ->where('id', '=', $request->course_id)
                    ->update(['status' => 3]);

                return redirect('admins/t-courses')->with('msj-exitoso', 'El T-Course ha sido inhabilitado con éxito.');
            }else if (isset($request->certification_id)){
                DB::table('certifications')
                    ->where('id', '=', $request->certification_id)
                    ->update(['status' => 3]);

                return redirect('admins/t-mentorings')->with('msj-exitoso', 'La T-Mentoring ha sido inhabilitada con éxito.');
            }else if (isset($request->podcast_id)){
                DB::table('podcasts')
                    ->where('id', '=', $request->podcast_id)
                    ->update(['status' => 3]);

                return redirect('admins/t-books')->with('msj-exitoso', 'El T-Book ha sido inhabilitado con éxito.');
            }
        }else{
            if (isset($request->course_id)){
                DB::table('courses')
                    ->where('id', '=', $request->course_id)
                    ->update(['status' => 2]);

                return redirect('admins/t-courses/disabled')->with('msj-exitoso', 'El T-Course ha sido restaurado con éxito.');
            }else if (isset($request->certification_id)){
                DB::table('certifications')
                    ->where('id', '=', $request->certification_id)
                    ->update(['status' => 2]);

                return redirect('admins/t-mentorings/disabled')->with('msj-exitoso', 'La T-Mentoring ha sido restaurada con éxito.');
            }else if (isset($request->podcast_id)){
                DB::table('podcasts')
                    ->where('id', '=', $request->podcast_id)
                    ->update(['status' => 2]);

                return redirect('admins/t-books/disabled')->with('msj-exitoso', 'El T-Book ha sido restaurado con éxito.');
            }
        }
    }

    //**** Admin / T-Courses - T-Mentorings - T-Books / Enviar Correcciones ***//
    public function send_corrections(Request $request){
        $notificacion = new NotificationController();

        if ($request->content_type == 'curso'){
            $curso = Course::find($request->course_id);
            $curso->evaluation_review = $request->corrections;
            $curso->status = 4;
            $curso->reviewed_at = date('Y-m-d H:i:s');
            $curso->save();

            //**** Notificar al Instructor ***//
            $notificacion->store($curso->user_id, 'Nueva Evaluación de Revisión', 'Su T-Course <b>'.$curso->title.'</b> ha sido devuelto con correcciones.', 'far fa-edit', 'instructors/t-courses/show/'.$curso->slug.'/'.$request->course_id);

            return redirect("admins/t-courses/pending")->with('msj-exitoso', 'El T-Course ha sido devuelto para correcciones con éxito.');
        }else if ($request->content_type == 'certificacion'){
            $certificacion = Certification::find($request->certification_id);
            $certificacion->evaluation_review = $request->corrections;
            $certificacion->status = 4;
            $certificacion->reviewed_at = date('Y-m-d H:i:s');
            $certificacion->save();

            //**** Notificar al Instructor ***//
            $notificacion->store($certificacion->user_id, 'Nueva Evaluación de Revisión', 'Su T-Mentoring <b>'.$certificacion->title.'</b> ha sido devuelta con correcciones.', 'far fa-edit', 'instructors/t-mentorings/show/'.$certificacion->slug.'/'.$request->certification_id);

            return redirect("admins/t-mentorings/pending")->with('msj-exitoso', 'La T-Mentoring ha sido devuelta para correcciones con éxito.');
        }else if ($request->content_type == 'podcast'){
            $podcast = Podcast::find($request->podcast_id);
            $podcast->evaluation_review = $request->corrections;
            $podcast->status = 4;
            $podcast->reviewed_at = date('Y-m-d H:i:s');
            $podcast->save();

            //**** Notificar al Instructor ***//
            $notificacion->store($podcast->user_id, 'Nueva Evaluación de Revisión', 'Su T-Book <b>'.$podcast->title.'</b> ha sido devuelto con correcciones.', 'far fa-edit', 'instructors/t-books/show/'.$podcast->slug.'/'.$request->certification_id);

            return redirect("admins/t-books/pending")->with('msj-exitoso', 'El T_Book ha sido devuelta para correcciones con éxito.');
        }
    }

    //**** Admin / T-Courses - T-Mentorings - T-Books / Configuraciones / Destacados ***//
    public function featured(Request $request){
        $route = Route::currentRouteName();
        $ruta = explode(".", $route);

        if ($ruta[1] == 'courses'){
            $cursosDisponibles = Course::where('status', '=', 2)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();

            return view('admins.courses.featured')->with(compact('cursosDisponibles'));
        }else if ($ruta[1] == 'certifications'){
            $certificacionesDisponibles = Certification::where('status', '=', 2)
                                            ->orderBy('created_at', 'DESC')
                                            ->get();

            return view('admins.certifications.featured')->with(compact('certificacionesDisponibles'));
        }else if ($ruta[1] == 'podcasts'){
            $podcastsDisponibles = Podcast::where('status', '=', 2)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();

            return view('admins.podcasts.featured')->with(compact('podcastsDisponibles'));
        }
    }

    //**** Admin / T-Courses - T-Mentorings - T-Books / Configuraciones / Destacados / Destacar - Quitar ***//
    public function update_featured(Request $request){
        if (isset($request->course_id)){
            DB::table('courses')
                ->where('id', '=', $request->course_id)
                ->update(['featured' => $request->featured]);

            if ($request->featured == 0){
                return redirect('admins/t-courses/featured')->with('msj-exitoso', 'El T-Course ha sido removido de los destacados con éxito.');
            }else{
                 return redirect('admins/t-courses/featured')->with('msj-exitoso', 'El T-Course ha sido agregado a los destacados con éxito.');
            }
        }else if (isset($request->certification_id)){
             DB::table('certifications')
                ->where('id', '=', $request->certification_id)
                ->update(['featured' => $request->featured]);

            if ($request->featured == 0){
                return redirect('admins/t-mentorings/featured')->with('msj-exitoso', 'La T-Mentoring ha sido removida de los destacados con éxito.');
            }else{
                 return redirect('admins/t-mentorings/featured')->with('msj-exitoso', 'La T-Mentoring ha sido agregada a los destacados con éxito.');
            }
        }else if (isset($request->podcast_id)){
            DB::table('podcasts')
                ->where('id', '=', $request->podcast_id)
                ->update(['featured' => $request->featured]);

            if ($request->featured == 0){
                return redirect('admins/t-books/featured')->with('msj-exitoso', 'El T-Book ha sido removido de los destacados con éxito.');
            }else{
                 return redirect('admins/t-books/featured')->with('msj-exitoso', 'El T-Book ha sido agregado a los destacados con éxito.');
            }
        }
    }

    //*** Admin / Finanzas / Balance ***//
    public function balance(){
        $balance = Transaction::orderBy('created_at', 'DESC')
                        ->get();

        return view('admins.finances.balance')->with(compact('balance'));
    }

    public function send_mail_students_by_course(Request $request){
        $estudiantes = DB::table('courses_students')
                        ->select('user_id')
                        ->where('course_id', '=', $request->course_id)
                        ->get();

        foreach ($estudiantes as $estudiante){
            $datosEstudiante = DB::table('users')
                                ->select('email')
                                ->where('id', '=', $estudiante->user_id)
                                ->first();

            $data['content'] = $request->description;
            $data['title'] = $request->title;

            $data['correo'] = $datosEstudiante->email; 
            Mail::send('emails.mailToStudents',['data' => $data], function($msg) use ($data){
                $msg->to($data['correo']);
                 $msg->subject($data['title']);
            });
        }     
        
        return redirect("admins/t-courses/reports/show-students/".$request->course_id)->with('msj-exitoso', 'El correo ha sido enviado a los estudiantes con éxito.'); 
    }

    public function send_mail_all_students(Request $request){
        if ($request->selection == 0){
            $estudiantes = DB::table('users')
                        ->select('email')
                        ->where('role_id', '=', 1)
                        ->where('status', '=', 1)
                        ->get();
        }else{
            $estudiantes = DB::table('users')
                            ->select('email')
                            ->whereIn('id', $request->students_selected)
                            ->get();
        }

        $data['adjunto'] = NULL;
        if ($request->hasFile('adjunto')){
            $file = $request->file('adjunto');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/documents/events/', $name);
            $data['adjunto'] = $name;
        }

        $data['content'] = $request->description;
        $data['title'] = $request->title;

        foreach ($estudiantes as $estudiante){
            $data['correo'] = $estudiante->email; 
            Mail::send('emails.mailToStudents',['data' => $data], function($msg) use ($data){
                $msg->to($data['correo']);
                $msg->subject($data['title']);
                if (!is_null($data['adjunto'])){
                    $msg->attach('https://www.transformatepro.com/documents/events/'.$data['adjunto']);
                }
            });
        }     
        
        return redirect("admins/users/students")->with('msj-exitoso', 'El correo ha sido enviado a los estudiantes con éxito.'); 
    }

    public function add_sponsor(Request $request){
        $patrocinador = User::where('email', '=', $request->sponsor_email)
                            ->first();
        $usuario = User::find($request->user_id);

        if (!is_null($patrocinador)){
            $usuario->sponsor_id = $patrocinador->id;
            $usuario->save();

            $comisionesUsuario = DB::table('commissions')
                                    ->where('user_id', '=', $usuario->id)
                                    ->where('purchase_detail_id', '<>', NULL)
                                    ->orderBy('id', 'ASC')
                                    ->get();
            $cont = 0;
            foreach ($comisionesUsuario as $comisionUsuario){
                if ($cont == 0){
                    $fechaPrimeraCompra = new Carbon($comisionUsuario->date);
                    $fechaActual = Carbon::now();
                    $fechaFinal = $fechaPrimeraCompra->addYear();
                }

                if ($fechaActual <= $fechaFinal){
                    $comision = new Commission();
                    $comision->user_id = $patrocinador->id;
                    $comision->amount = (($comisionUsuario->amount * 10) / 100);
                    $comision->type = 'Plan de Afiliado';
                    $comision->referred_id = $usuario->id;
                    $comision->purchase_detail_id = $comisionUsuario->purchase_detail_id;
                    $comision->status = 0;
                    $comision->date = date('Y-m-d');
                    $comision->save();

                    $patrocinador->balance = $patrocinador->balance + $comision->amount;
                    $patrocinador->save();
                }

                $cont++;
            } 
                    
            return redirect('admins/users/show/'.$usuario->id)->with('msj-exitoso', 'El patrocinador ha sido asignado con éxito.');
        }else{
            return redirect('admins/users/show/'.$usuario->id)->with('msj-erroneo', 'El correo ingresado no coincide con ningún usuario registrado en la plataforma.');
        }
        
    }

    function set_general_price(Request $request){
        $fecha = date('Y-m-d H:i:s');
        
        if ($request->type == 'courses'){
            DB::table('courses')
            ->update(['price' => $request->price, 'updated_at' => $fecha]);
        }else if ($request->type == 'certifications'){
            DB::table('certifications')
            ->update(['price' => $request->price, 'updated_at' => $fecha]);
        }else if ($request->type == 'podcasts'){
            DB::table('podcasts')
            ->update(['price' => $request->price, 'updated_at' => $fecha]);
        }
        
        
        return redirect()->back()->with('msj-exitoso', 'El precio de los cursos ha sido cambiado con éxito.');
    }
}
