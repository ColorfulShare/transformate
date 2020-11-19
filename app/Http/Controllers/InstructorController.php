<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str as Str;
use App\Models\Course; use App\Models\Certification; use App\Models\Podcast; use App\Models\User;
use App\Models\Commission; use App\Models\PurchaseDetail;
use Auth; use DB; use Mail;

class InstructorController extends Controller
{
    public function home(){
        $ganancias = DB::table('commissions')
                        ->where('user_id', '=', Auth::user()->id)
                        ->select(DB::raw('SUM(amount) as total'), 'wallet')
                        ->groupBy('wallet')
                        ->get();

        /*foreach ($ganancias as $ganancia){
            if ($ganancia->type == 'Plan de Afiliado'){
                $ganancia->type = 'Ganancias por Referidos';
            }else if ($ganancia->type == 'Compra Orgánica'){
                $ganancia->type = 'Ganancias Orgánicas';
            }else if ($ganancia->type == 'Compra Directa'){
                $ganancia->type = 'Ganancias Directas';
            }else if ($ganancia->type == 'Compra Destacada'){
                $ganancia->type = 'Ganancias por Destacados';
            }
        }*/

        $cursos = Course::where('user_id', '=', Auth::user()->id)
                    ->withCount('students')
                    ->where('status', '=', 2)
                    ->get();

        $alumnos = collect();
        foreach ($cursos as $curso){
            $dato = new \stdClass;
            $dato->curso = $curso->title;
            $dato->alumnos = $curso->students_count;
            $alumnos->push($dato);
        }

        $cursosMasComprados = PurchaseDetail::join('courses', 'purchase_details.course_id', '=', 'courses.id')
                                ->where('purchase_details.course_id', '<>', NULL)
                                ->where('courses.user_id', '=', Auth::user()->id)
                                ->select('purchase_details.course_id', DB::raw('count(*) as total'))
                                ->groupBy('purchase_details.course_id')
                                ->orderBy('total', 'DESC')
                                ->take(5)
                                ->get();

        return view('instructors.index')->with(compact('ganancias', 'alumnos', 'cursosMasComprados'));
    }

    //****** Admin / Usuarios / Instructores
    public function index(Request $request){
        $instructores = User::where('role_id', '=', 2)
                        ->where('status', '=', 1)
                        ->orderBy('names', 'ASC')
                        ->get();

        return view('admins.users.instructors')->with(compact('instructores'));
    }

    //Estudiante - Ver Perfil del Instructor
    public function profile($slug, $id){
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

        return view('students.instructors.showProfile')->with(compact('instructor', 'cantEstudiantes'));
    }

    //Instructor - Ver Su Perfil
    public function my_profile(){
        $ganancias = Commission::where('user_id', '=', Auth::user()->id)
                        ->orderBy('date', 'DESC')
                        ->get();

        return view('instructors.myProfile')->with(compact('ganancias'));
    }

    //Instructor / Actualizar Datos de Perfil
    public function update_profile(Request $request){
        $usuario = User::find(Auth::user()->id);
        $usuario->fill($request->all());

        $nombre = $usuario->names." ".$usuario->last_names;
        $usuario->slug = Str::slug($nombre);
        $pic = explode("-", $usuario->slug);
        $usuario->afiliate_code = $pic[0]."pro".$usuario->id;
        $usuario->partner_code = $pic[0]."tp".$usuario->id;
        
        if (isset($request->pagos)){
            if (isset($request->save_payment_method)){
                $usuario->save_payment_method = 1;
            }else{
                $usuario->save_payment_method = 0;
            }
        }

        if ($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/uploads/images/users', $name);
            $usuario->avatar = $name;
        }

        if ($request->clave_actual != NULL){
            if (Hash::check($request->clave_actual, Auth::user()->password)){
                $usuario->password = Hash::make($request->clave);
            }else{
                return redirect('instructors/profile')->with('msj-error', 'La clave ingresada es incorrecta.');
            }
        }

        $usuario->save();

        if (isset($request->status)){
            Auth::logout();
            return redirect('/')->with('msj-exitoso', 'Su cuenta ha sido cerrada permanentemente.');
        }

        return redirect('instructors/profile')->with('msj-exitoso', 'Sus datos han sido actualizados con éxito.');
    }

    public function my_referrals(){
        $referidos = DB::table('users')
                        ->select('id', 'names', 'last_names', 'avatar', 'country', 'state', 'created_at')
                        ->where('sponsor_id', '=', Auth::user()->id)
                        ->paginate(25);

        $cantReferidos = DB::table('users')
                            ->where('sponsor_id', '=', Auth::user()->id)
                            ->count();

        return view('instructors.referrals.index')->with(compact('referidos', 'cantReferidos'));
    }

    //**** Admin / Usuarios / Instructores / Listado de Instructores Pendientes ****//
    public function pending_list(){
        $instructores = User::where('role_id', '=', 2)
                            ->where('status', '=', 2) 
                            ->orderBy('created_at', 'ASC')
                            ->get();

        return view('admins.users.instructorsPending')->with(compact('instructores'));
    }

     //**** Admin / Usuarios / Instructores / Listado de Instructores Pendientes / Aprobar - Denegar ****//
    public function approve($id, $accion){
        $usuario = User::find($id);
        $usuario->status = $accion;
        $usuario->save();

        if ($accion == 1){
            $data['correo'] = $usuario->email;
            $data['cliente'] = $usuario->names." ".$usuario->last_names;

            Mail::send('emails.approveInstructor',['data' => $data], function($msg) use ($data){
                $msg->to($data['correo']);
                $msg->subject('Bienvenido a Transfórmate');
                $msg->attach('https://www.transformatepro.com/documents/PASOS_RUTA_DEL_MENTOR.docx');
            });

            return redirect('admins/users/instructors/pending-list')->with('msj-exitoso', 'El instructor ha sido aprobado con éxito.');
        }else{
            return redirect('admins/users/instructors/pending-list')->with('msj-exitoso', 'El instructor ha sido rechazado con éxito.');
        }
    }
}
