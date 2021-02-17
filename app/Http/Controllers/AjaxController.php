<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson; use App\Models\Course; use App\Models\Certification; use App\Models\Podcast; 
use App\Models\PurchaseDetail; use App\Models\MarketProduct; use App\Models\MasterClass;
use DB; use Auth;

class AjaxController extends Controller
{
    //Cargar credenciales de S3
    public function load(Request $request){
        $c1 = base64_decode($request->c1);
        $c2 = base64_decode($request->c2);
        $c3 = 'us-east-2';

        return response()->json(
            ['c1' => $c1, 'c2' => $c2, 'c3' => $c3]
        );
    }

    //** Instructor / Actualizar archivos multimedia de un producto. (portada o archivo)
    public function multimedias_por_producto($id_producto){
        $producto = MarketProduct::where('id', '=', $id_producto)
                        ->select('cover', 'cover_name', 'file', 'filename', 'file_icon')
                        ->first();

        return view('instructors.products.multimedias')->with(compact('producto'));
       
    }

    public function load_preview($id, $type){        
        if ($type == 'curso'){
            $preview = Course::where('id', '=', $id)
                        ->first();
        }else if ($type == 'certificacion'){
            $preview = Certification::where('id', '=', $id)
                        ->first();
        }else if ($type == 'podcast'){
            $preview = Podcast::where('id', '=', $id)
                        ->first();
        }else if ($type == 'clase'){
            $preview = MasterClass::where('id', '=', $id)
                        ->first();
        }

        return view('landing.showPreview')->with(compact('preview', 'type'));
    }
    
    public function datos_cobro_mentor($mentor){
        $usuario = DB::table('users')
                    ->select('names', 'last_names', 'charging_method', 'paypal_email', 'business_name', 'identification', 'bank', 'account_type', 'account_number')
                    ->where('id', '=', $mentor)
                    ->first();

        return view('admins.liquidations.chargingData')->with(compact('usuario'));
    }

    public function detalles_compra($id_compra){
        $detalles = PurchaseDetail::where('purchase_id', '=', $id_compra)
                        ->get();

        return view('students.purchases.purchaseDetails')->with(compact('detalles'));
    }

    public function verificar_etiqueta($etiqueta){
        $existe = DB::table('tags')
                    ->where('tag', '=', $etiqueta)
                    ->first();

        if (is_null($existe)){
            $respuesta = 0;
        }else{
            $respuesta = 1;
        }

        return response()->json(
            $respuesta  
        );
    }

    //** Instructor / Actualizar archivos por lección en el actualizar temario (cursos y certificaciones)
    public function archivos_por_leccion($leccion){
        $leccion = Lesson::where('id', '=', $leccion)
                    ->withCount('resource_files')
                    ->first();

        return view('instructors.courses.lessonFiles')->with(compact('leccion'));
    }

    //** Instructor / Actualizar archivos multimedia de un curso, certificación o podcast. (cover, preview y preview_cover)
    public function multimedias_por_contenido($tipo_contenido, $id_contenido){
        if ($tipo_contenido == 'curso'){
            $curso = Course::where('id', '=', $id_contenido)
                        ->select('cover', 'cover_name', 'preview', 'preview_name', 'preview_cover', 'preview_cover_name')
                        ->first();

            return view('instructors.courses.multimediaResources')->with(compact('curso'));
        }else if ($tipo_contenido == 'certificacion'){
            $certificacion = Certification::where('id', '=', $id_contenido)
                                ->select('cover', 'cover_name', 'preview', 'preview_name', 'preview_cover', 'preview_cover_name')
                                ->first();

            return view('instructors.certifications.multimediaResources')->with(compact('certificacion'));
        }else if ($tipo_contenido == 'podcast'){
            $podcast = Podcast::where('id', '=', $id_contenido)
                            ->select('audio_file', 'audio_filename', 'cover', 'cover_name', 'preview', 'preview_name')
                            ->first();

            return view('instructors.podcasts.multimediaResources')->with(compact('podcast'));
        }else if ($tipo_contenido == 'master-class'){
            $clase = MasterClass::where('id', '=', $id_contenido)
                            ->select('video_file', 'video_filename', 'cover', 'cover_name', 'preview', 'preview_name')
                            ->first();

            return view('admins.masterClass.multimediaResources')->with(compact('clase'));
        }
    }

    //** Instructor / Cargar Evaluación a un Curso
    public function modulos_por_curso($id, $contenido){
        if ($contenido == 'curso'){
            $modulos = DB::table('modules')
                        ->select('id', 'title')
                        ->where('course_id', '=', $id)
                        ->orderBy('priority_order', 'ASC')
                        ->get();
        }else{
            $modulos = DB::table('modules')
                        ->select('id', 'title')
                        ->where('certification_id', '=', $id)
                        ->orderBy('priority_order', 'ASC')
                        ->get();
        }

        return response()->json(
            $modulos
        );
    }

    //Estudiante / Editar Perfil / Cambiar Correo
    //Instructor / Editar Perfil / Cambiar Correo
    //Admin / Crear Nuevo Usuario (Alumno, Instructor y Administrador)
    //Verificar si el correo electrónico ya se encuentra registrado
    public function verificar_correo($correo){
        $usuario = DB::table('users')
                    ->where('email', '=', $correo)
                    ->count();

        return response()->json(
            $usuario
        );
    }

    //** Instructor / Discusiones / Filtros de Búsqueda / Cargar Contenido según Tipo
    //** Instructor / Cargar Evaluación / Cargar Contenido por Tipo 
    public function contenido_por_tipo($tipo){
        if ($tipo == 'curso'){
            $contenido = DB::table('courses')
                            ->select('id', 'title')
                            ->where('user_id', '=', Auth::user()->id)
                            ->orderBy('title', 'ASC')
                            ->get();
        }else if ($tipo == 'certificacion'){
            $contenido = DB::table('certifications')
                            ->select('id', 'title')
                            ->where('user_id', '=', Auth::user()->id)
                            ->orderBy('title', 'ASC')
                            ->get();
        }else{
            $contenido = DB::table('podcasts')
                            ->select('id', 'title')
                            ->where('user_id', '=', Auth::user()->id)
                            ->orderBy('title', 'ASC')
                            ->get();
        }

        return response()->json(
            $contenido
        );
    }

    //** Instructor / Crear Contenido / Cargar Subcategorias
    public function cargar_subcategorias($categoria){
        $subcategorias = DB::table('subcategories')
                            ->select('id', 'title')
                            ->where('category_id', '=', $categoria)
                            ->get();

        return response()->json(
            $subcategorias
        );
    }

    //Admin / Usuarios / Instructores / Crear Instructor
    //Verificar si el sponsor existe y está activo
    public function verificar_sponsor($sponsor){
        $usuario = DB::table('users')
                    ->select('status', 'role_id')
                    ->where('id', '=', $sponsor)
                    ->first();

        if ( (!is_null($usuario)) && ($usuario->status == 1) && ($usuario->role_id == 2) ){
            $existe = 1;
        }else{
            $existe = 0;
        }

        return response()->json(
            $existe
        );
    }

    public function actualizar_barra_progreso($leccion_id){
        $datosLeccion = Lesson::find($leccion_id);

        $modulos = DB::table('modules')
                    ->select('id')
                    ->where('course_id', '=', $datosLeccion->module->course_id)
                    ->where('id', '<=', $datosLeccion->module_id)
                    ->orderBy('priority_order', 'ASC')
                    ->get();

        $lecciones = collect();
        foreach ($modulos as $modulo) {
            $leccionesMod = DB::table('lessons')
                                ->select('id')
                                ->where('module_id', '=', $modulo->id)
                                ->where('id', '<=', $leccion_id)
                                ->get();

            foreach ($leccionesMod as $leccion) {
                $lecciones->push($leccion);
            }
        }

        foreach ($lecciones as $leccion2){
            $leccionExistente = DB::table('lesson_progress')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->where('lesson_id', '=', $leccion2->id)
                                    ->first();

            if (!is_null($leccionExistente)){
                DB::table('lesson_progress')
                    ->where('user_id', '=', Auth::user()->id)
                    ->where('lesson_id', '=', $leccion2->id)
                    ->update(['finished' => 1]);
            }else{
                DB::table('lesson_progress')->insert(
                    ['user_id' => Auth::user()->id, 'lesson_id' => $leccion2->id, 'progress' => 0, 'finished' => 1, 'updated_at' => date('Y-m-d H:i:s')]
                );
            }
        }

        $cursoCont = new CourseController();
        $progreso = $cursoCont->update_progress($datosLeccion->module->course_id);

        return response()->json(
            $progreso
        );

    }
    //Estudiante / Lecciones
    //Actualiza el progreso del estudiante en una lección
    public function actualizar_progreso_leccion($leccion, $progreso, $tipo){
        try{
            if (!Auth::guest()){
               $leccionIniciada = DB::table('lesson_progress')
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('lesson_id', '=', $leccion)
                                ->first();

                if (!is_null($leccionIniciada)){
                    DB::table('lesson_progress')
                            ->where('id', '=', $leccionIniciada->id)
                            ->update(['progress' => $progreso,
                                      'updated_at' => date('Y-m-d H:i:s')]);

                        return response()->json(
                            false
                        );
                    /*if ($tipo == 'end'){
                        DB::table('lesson_progress')
                            ->where('id', '=', $leccionIniciada->id)
                            ->update(['progress' => $progreso,
                                      'finished' => true,
                                      'updated_at' => date('Y-m-d H:i:s')]);  

                        $infoLeccion = Lesson::where('id', '=', $leccion)
                                            ->first();

                        if (!is_null($infoLeccion->module->course_id)){
                            $contr = new CourseController();
                            $contr->update_progress($infoLeccion->module->course_id);
                        }else if (!is_null($infoLeccion->module->certification_id)){
                            $contr = new CertificationController();
                            $contr->update_progress($infoLeccion->module->certification_id);
                        }else if (!is_null($infoLeccion->module->podcast_id)){
                            $contr = new PodcastController();
                            $contr->update_progress($infoLeccion->module->podcast_id);
                        }

                        return response()->json(
                            true
                        );     
                    }else{
                        DB::table('lesson_progress')
                            ->where('id', '=', $leccionIniciada->id)
                            ->update(['progress' => $progreso,
                                      'updated_at' => date('Y-m-d H:i:s')]);

                        return response()->json(
                            false
                        );
                    }*/
                }else{
                    DB::table('lesson_progress')
                        ->insert(['user_id' => Auth::user()->id, 'lesson_id' => $leccion, 'progress' => $progreso, 'updated_at' => date('Y-m-d H:i:s')]);

                    return response()->json(
                        false
                    );
                } 
            }
        }catch (\Exception $e) {
            $error = explode('Stack trace', $e);
            \Log::error("Módulo: Estudiante. Acción: Actualizar progreso de una lección. # Lección: ".$leccion." Error: ".$error[0]);
        }catch (\Throwable $ex) {
            $error = explode('Stack trace', $ex);
            \Log::error("Módulo: Estudiante. Usuario Autenticado: ".Auth::user()->id.". Acción: Actualizar progreso de una lección. ´# Lección: ".$leccion." Error: ".$error[0]);
        }
    }
}
