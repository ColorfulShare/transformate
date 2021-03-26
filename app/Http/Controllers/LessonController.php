<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson; use App\Models\Module; use App\Models\ResourceFile;
use DB; use Storage; use Auth;

class LessonController extends Controller
{
    public function store(Request $request){
        $cantLecciones = DB::table('lessons')
                            ->where('module_id', '=', $request->module_id)
                            ->count();

        $datosModulo = Module::find($request->module_id);

        $leccion = new Lesson($request->all());
        $leccion->module_id = $request->module_id;
        $leccion->priority_order = $cantLecciones+1;
        $leccion->save();

        if (!is_null($datosModulo->course_id)){
            return redirect('instructors/t-courses/temary/'.$datosModulo->course->slug.'/'.$datosModulo->course_id)->with('msj-exitoso', 'La lección ha sido agregada con éxito.');
        }else{
            return redirect('instructors/t-mentorings/temary/'.$datosModulo->certification->slug.'/'.$datosModulo->certification_id)->with('msj-exitoso', 'La lección ha sido agregada con éxito.');
        }
    }

    public function show_video($leccion){
        $leccion = Lesson::find($leccion);

        return view('admins.courses.showVideo')->with(compact('leccion'));
    }

    public function load_video_duration($id, $duration){
        $duracion = number_format($duration, 2);
        $leccion = DB::table('lessons')
                        ->where('id', '=', $id)
                        ->update(['duration' => $duracion]);

        return response()->json(
            true
        );
    }

    public function load_video(Request $request){
        $leccion = Lesson::find($request->lesson_id);
        $datosModulo = Module::find($leccion->module_id);

        if (Storage::disk('s3')->has($request->file_path)){ 
            $leccion->filename = $request->filename;
            $leccion->file_extension = $request->file_extension;
            $leccion->file_icon = $this->setIcon($leccion->file_extension);
            $leccion->video = 'https://transformate-content.s3.us-east-2.amazonaws.com/'.$request->file_path; 
            $leccion->save();

            if (!is_null($datosModulo->course_id)){
               return redirect('instructors/t-courses/temary/'.$datosModulo->course->slug.'/'.$datosModulo->course_id)->with('msj-exitoso', 'El video ha sido cargado con éxito.'); 
            }else{
                return redirect('instructors/t-mentorings/temary/'.$datosModulo->certification->slug.'/'.$datosModulo->certification_id)->with('msj-exitoso', 'El video ha sido cargado con éxito.');
            } 
        }else{
            if (!is_null($datosModulo->course_id)){
                return redirect('instructors/t-courses/temary/'.$datosModulo->course->slug.'/'.$datosModulo->course_id)->with('msj-erroneo', 'El video no ha podido cargarse. Intente nuevamente.');
            }else{
                return redirect('instructors/t-mentorings/temary/'.$datosModulo->certification->slug.'/'.$datosModulo->certification_id)->with('msj-erroneo', 'El video no ha podido cargarse. Intente nuevamente.');
            }
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

    public function update(Request $request){
        $leccion = Lesson::find($request->lesson_id);
        $leccion->fill($request->all());
        $leccion->save();

        $datosModulo = Module::find($leccion->module_id);

        if (!is_null($datosModulo->course_id)){
            return redirect('instructors/t-courses/temary/'.$datosModulo->course->slug.'/'.$datosModulo->course_id)->with('msj-exitoso', 'La lección ha sido agregada con éxito.');
        }else{
            return redirect('instructors/t-mentorings/temary/'.$datosModulo->certification->slug.'/'.$datosModulo->certification_id)->with('msj-exitoso', 'La lección ha sido agregada con éxito.');
        }
    }

    public function add_module(Request $request){
        if ($request->content_type == 'curso'){
            $cantModulos = DB::table('modules')
                            ->where('course_id', '=', $request->course_id)
                            ->count();

            $modulo = new Module($request->all());
            $modulo->course_id = $request->course_id;
            $modulo->priority_order = $cantModulos+1;
            $modulo->save();

            return redirect('instructors/t-courses/temary/'.$request->course_slug.'/'.$request->course_id)->with('msj-exitoso', 'El módulo ha sido agregado con éxito.');
        }else{
            $cantModulos = DB::table('modules')
                            ->where('certification_id', '=', $request->certification_id)
                            ->count();

            $modulo = new Module($request->all());
            $modulo->certification_id = $request->certification_id;
            $modulo->priority_order = $cantModulos+1;
            $modulo->save();

            return redirect('instructors/t-mentorings/temary/'.$request->certification_slug.'/'.$request->certification_id)->with('msj-exitoso', 'El módulo ha sido agregado con éxito.');
        }
        
    }

    public function update_module(Request $request){
        $modulo = Module::find($request->module_id);
        $modulo->fill($request->all());
        $modulo->save();

        if (!is_null($modulo->course_id)){
            return redirect('instructors/t-courses/temary/'.$modulo->course->slug.'/'.$modulo->course_id)->with('msj-exitoso', 'El módulo ha sido actualizado con éxito.');
        }else{
            return redirect('instructors/t-mentorings/temary/'.$modulo->certification->slug.'/'.$modulo->certification_id)->with('msj-exitoso', 'El módulo ha sido actualizado con éxito.');
        }
    }

    public function load_resource(Request $request){
        $leccion = Lesson::find($request->lesson_id);

        if (Storage::disk('s3')->has($request->file_path)){ 
            $recurso = new ResourceFile();
            $recurso->lesson_id = $request->lesson_id;
            $recurso->filename = $request->filename;
            $recurso->file_extension = $request->file_extension;
            $recurso->file_icon = $this->setIcon($recurso->file_extension);
            $recurso->link = 'https://transformate-content.s3.us-east-2.amazonaws.com/'.$request->file_path; 
            $recurso->save();

            if (!is_null($leccion->module->course_id)){
                return redirect('instructors/t-courses/temary/'.$leccion->module->course->slug.'/'.$leccion->module->course_id)->with('msj-exitoso', 'El recurso ha sido cargado con éxito.');
            }else{
                return redirect('instructors/t-mentorings/temary/'.$leccion->module->certification->slug.'/'.$leccion->module->certification_id)->with('msj-exitoso', 'El recurso ha sido cargado con éxito.');
            }
        }else{
            if (!is_null($leccion->module->course_id)){
                return redirect('instructors/t-courses/temary/'.$leccion->module->course->slug.'/'.$leccion->module->course_id)->with('msj-erroneo', 'El recurso no ha podido cargarse. Intente nuevamente.');
            }else{
                return redirect('instructors/t-mentorings/temary/'.$leccion->module->certification->slug.'/'.$leccion->module->certification_id)->with('msj-erroneo', 'El recurso no ha podido cargarse. Intente nuevamente.');
            }
        } 
    }

    public function show_resources($lesson){
        $recursos = ResourceFile::where('lesson_id', '=', $lesson)
                        ->orderBy('id', 'DESC')
                        ->get();

        return view('instructors.courses.resources')->with(compact('recursos'));

    }

    public function record($slug_curso, $id_curso){
        $lecciones = Lesson::join('modules', 'lessons.module_id', '=', 'modules.id')
                        ->join('courses', 'modules.course_id', '=', 'courses.id')
                        ->where('courses.id', '=', $id_curso)
                        ->orderBy('lessons.id', 'ASC')
                        ->select('lessons.*', 'modules.priority_order as priority_order_module')
                        ->get();

        foreach ($lecciones as $leccion){
            if ($leccion->duration > 0){
                $tiempo = explode(".", $leccion->duration);
                $segundos = $tiempo[0]*60 + $tiempo[1]; 
                $leccion->hours = floor($segundos/ 3600);
                $leccion->minutes = floor(($segundos - ($leccion->hours * 3600)) / 60);
                $leccion->seconds = $segundos - ($leccion->hours * 3600) - ($leccion->minutes * 60);
            }
        }

        return view('admins.courses.lessons')->with(compact('lecciones'));
    }

    public function delete_module($id){
        $modulo = Module::find($id);

        if (!is_null($modulo->course_id)){
            $modulosRestantes = Module::where('course_id', '=', $modulo->course_id)
                                ->where('priority_order', '>', $modulo->priority_order)
                                ->get();
        }else{
            $modulosRestantes = Module::where('certification_id', '=', $modulo->certification_id)
                                ->where('priority_order', '>', $modulo->priority_order)
                                ->get();
        }
       
        foreach ($modulosRestantes as $moduloRestante) {
            $moduloRestante->priority_order = $moduloRestante->priority_order-1;
            $moduloRestante->save();
        }

        $lecciones = Lesson::where('module_id', '=', $modulo->id)
                        ->get();
        
        foreach ($lecciones as $leccion){
            DB::table('resource_files')
                ->where('lesson_id', '=', $leccion->id)
                ->delete();

            DB::table('lesson_progress')
                ->where('lesson_id', '=', $leccion->id)
                ->delete();

            $leccion->delete();
        }

        $modulo->delete();
        
        if (!is_null($modulo->course_id)){
            return redirect('instructors/t-courses/temary/'.$modulo->course->slug.'/'.$modulo->course_id)->with('msj-exitoso', 'El módulo ha sido eliminado con éxito.'); 
        }else{
            return redirect('instructors/t-mentorings/temary/'.$modulo->certification->slug.'/'.$modulo->certification_id)->with('msj-exitoso', 'El módulo ha sido eliminado con éxito.'); 
        }
    }

    public function delete($id){
        $leccion = Lesson::find($id);

        if (!is_null($leccion->module->course_id)){
            if (Storage::disk('s3')->has('courses/'.$leccion->module->course_id.'/'.$leccion->id)){
                Storage::disk('s3')->delete('courses/'.$leccion->module->course_id.'/'.$leccion->id);
            } 
        }else{
            if (Storage::disk('s3')->has('certifications/'.$leccion->module->certification_id.'/'.$leccion->id)){
                Storage::disk('s3')->delete('certifications/'.$leccion->module->certification_id.'/'.$leccion->id);
            } 
        }
       
        $leccionesRestantes = Lesson::where('module_id', '=', $leccion->module_id)
                                    ->where('priority_order', '>', $leccion->priority_order)
                                    ->get();

        foreach ($leccionesRestantes as $leccionRestante) {
            $leccionRestante->priority_order = $leccionRestante->priority_order-1;
            $leccionRestante->save();
        }

        DB::table('resource_files')
            ->where('lesson_id', '=', $leccion->id)
            ->delete();

         DB::table('lesson_progress')
            ->where('lesson_id', '=', $leccion->id)
            ->delete();

        $leccion->delete();

        if (Auth::user()->role_id == 2){
            if (!is_null($leccion->module->course_id)){
                return redirect('instructors/t-courses/temary/'.$leccion->module->course->slug.'/'.$leccion->module->course_id)->with('msj-exitoso', 'La lección ha sido eliminada con éxito.');
            }else{
                return redirect('instructors/t-mentorings/temary/'.$leccion->module->certification->slug.'/'.$leccion->module->certification_id)->with('msj-exitoso', 'La lección ha sido eliminada con éxito.');
            }
        }else{
            if (!is_null($leccion->module->course_id)){
                return redirect('admins/t-courses/lessons/'.$leccion->module->course->slug.'/'.$leccion->module->course_id)->with('msj-exitoso', 'La lección ha sido eliminada con éxito.');
            }else{
                return redirect('admins/t-mentorings/lessons/'.$leccion->module->certification->slug.'/'.$leccion->module->certification_id)->with('msj-exitoso', 'La lección ha sido eliminada con éxito.');
            }
        }
    }

    public function delete_resource($id){
        $recurso = ResourceFile::find($id);

        $nombreArchivo = explode("https://transformate-videos.s3.us-east-2.amazonaws.com/", $recurso->link);
        if (Storage::disk('s3')->has($nombreArchivo[1])){
            Storage::disk('s3')->delete($nombreArchivo[1]);
        }

        $recurso->delete();

        if (!is_null($recurso->lesson->module->course_id)){
            return redirect('instructors/t-courses/temary/'.$recurso->lesson->module->course->slug.'/'.$recurso->lesson->module->course_id)->with('msj-exitoso', 'El recurso ha sido eliminado con éxito.');
        }else{
            return redirect('instructors/t-mentorings/temary/'.$recurso->lesson->module->certification->slug.'/'.$recurso->lesson->module->certification_id)->with('msj-exitoso', 'El recurso ha sido eliminado con éxito.');
        }
        
    }
}
