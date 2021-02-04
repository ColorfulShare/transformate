<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str as Str;
use App\Models\Course; use App\Models\Certification; use App\Models\Podcast; use App\Models\User; 
use App\Models\Discussion; use App\Models\Category; 
use App\Models\Tag;
use DB; use Auth;

class StudentController extends Controller
{
    //Estudiante / Mis Cursos
    public function my_content(){
        $infoCursos = DB::table('courses_students')
                        ->where('user_id', '=', Auth::user()->id)
                        ->orderBy('start_date', 'DESC')
                        ->get();

        $cantCursos = $infoCursos->count();

        $cursos = collect();
        if ($cantCursos > 0){
            foreach ($infoCursos as $infoCurso) {
                $curso = Course::where('id', '=', $infoCurso->course_id)
                            ->first();

                $curso->progress = $infoCurso->progress;
                $curso->start_date = $infoCurso->start_date;
                $curso->ending_date = $infoCurso->ending_date;

                $cursos->push($curso);
            }
        }

        $infoCertificaciones = DB::table('certifications_students')
                                ->where('user_id', '=', Auth::user()->id)
                                ->get();

        $cantCertificaciones = $infoCertificaciones->count();

        $certificaciones = collect();
        if ($cantCertificaciones > 0){
            foreach ($infoCertificaciones as $infoCertificacion) {
                $certificacion = Certification::where('id', '=', $infoCertificacion->certification_id)
                                    ->first();

                $certificacion->progress = $infoCertificacion->progress;
                $certificacion->start_date = $infoCertificacion->start_date;
                $certificacion->ending_date = $infoCertificacion->ending_date;

                $certificaciones->push($certificacion);
            }
        }

        $infoPodcasts = DB::table('podcasts_students')
                            ->where('user_id', '=', Auth::user()->id)
                            ->get();

        $cantPodcasts = $infoPodcasts->count();

        $podcasts = collect();
        if ($cantPodcasts > 0){
            foreach ($infoPodcasts as $infoPodcast) {
                $podcast = Podcast::where('id', '=', $infoPodcast->podcast_id)
                            ->first();

                $podcasts->push($podcast);
            }
        }

        DB::table('gifts')
            ->where('user_id', '=', Auth::user()->id)
            ->where('checked', '=', 0)
            ->update(['checked' => 1]);
       
        return view('students.myContent')->with(compact('cantCursos', 'cursos', 'cantCertificaciones', 'certificaciones', 'cantPodcasts', 'podcasts'));  
    }

    public function my_content2(){
        $infoCursos = DB::table('courses_students')
                        ->where('user_id', '=', Auth::user()->id)
                        ->orderBy('start_date', 'DESC')
                        ->get();

        $cantCursos = $infoCursos->count();

        $cursos = collect();
        if ($cantCursos > 0){
            foreach ($infoCursos as $infoCurso) {
                $curso = Course::where('id', '=', $infoCurso->course_id)
                            ->first();

                $curso->progress = $infoCurso->progress;
                $curso->start_date = $infoCurso->start_date;
                $curso->ending_date = $infoCurso->ending_date;

                $cursos->push($curso);
            }
        }

        $infoPodcasts = DB::table('podcasts_students')
                            ->where('user_id', '=', Auth::user()->id)
                            ->get();

        $cantPodcasts = $infoPodcasts->count();

        $podcasts = collect();
        if ($cantPodcasts > 0){
            foreach ($infoPodcasts as $infoPodcast) {
                $podcast = Podcast::where('id', '=', $infoPodcast->podcast_id)
                            ->first();

                $podcasts->push($podcast);
            }
        }

        DB::table('gifts')
            ->where('user_id', '=', Auth::user()->id)
            ->where('checked', '=', 0)
            ->update(['checked' => 1]);
       
        return view('students.myContentNew')->with(compact('cantCursos', 'cursos', 'cantPodcasts', 'podcasts'));  
    }

    //Estudiante / Actualizar Datos de Perfil
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
            if (Auth::attempt(['email' => Auth::user()->email, 'password' => $request->clave_actual])) {
                $usuario->password = Hash::make($request->clave);
            }else{
                return redirect('students/profile')->with('msj-error', 'La clave ingresada es incorrecta.');
            }
        }

        $usuario->save();

        if (isset($request->status)){
            Auth::logout();
            return redirect('/')->with('msj-exitoso', 'Su cuenta ha sido cerrada permanentemente.');
        }

        return redirect('students/profile')->with('msj-exitoso', 'Sus datos han sido actualizados con éxito.');
    }

    //****** Admin / Usuarios / Estudiantes
    public function index(Request $request){
        $estudiantes = User::where('role_id', '=', 1)
                        ->orderBy('names', 'ASC')
                        ->get();

        return view('admins.users.students')->with(compact('estudiantes'));
    }
}
