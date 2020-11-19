<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;
use App\Models\Test; use App\Models\Question; use App\Models\Course; use App\Models\Certification;
use App\Models\Module; use App\Models\User;
use DB; use Auth; use Route;

class TestController extends Controller
{   
    //**** Instructor / Evaluaciones ***//
    public function index ($content_type, $slug, $id){
        if ($content_type == 'courses'){
            $evaluaciones = Test::join('modules', 'tests.module_id', '=', 'modules.id')
                                ->join('courses', 'modules.course_id', '=', 'courses.id')
                                ->select('tests.*')
                                ->where('courses.id', '=', $id)
                                ->paginate(20); 
        }else{
            $evaluaciones = Test::join('modules', 'tests.module_id', '=', 'modules.id')
                                ->join('certifications', 'modules.certification_id', '=', 'certifications.id')
                                ->select('tests.*')
                                ->where('certifications.id', '=', $id)
                                ->paginate(20); 
        }

        foreach ($evaluaciones as $evaluacion){
            $evaluacion->presentations = DB::table('tests_students')
                                            ->where('test_id', '=', $evaluacion->id)
                                            ->count();

            $promedio = DB::table('tests_students')
                            ->where('test_id', '=', $evaluacion->id)
                            ->select(DB::raw('avg(score) as avg'))
                            ->first();

            $evaluacion->average = $promedio->avg;
        }

        $cantEvaluaciones = $evaluaciones->count();

        return view('instructors.tests.index')->with(compact('evaluaciones', 'cantEvaluaciones', 'content_type', 'id'));
    }

    public function create(Request $request){     
        $datosModulo = Module::where('id', '=', $request->modulo)
                        ->first();

        $cantPreguntas = $request->cant_preguntas;

        return view('instructors.tests.create')->with(compact('datosModulo', 'cantPreguntas'));
    }

    public function store(Request $request){
        $evaluacion = new Test($request->all());
        $evaluacion->slug = Str::slug($evaluacion->title);
        $evaluacion->save();

        for ($i = 1; $i <= $request->cant_preguntas; $i++){
            $nombrePregunta = "pregunta-".$i;
            $nombreRespuesta1 = "respuesta-1-".$i;
            $nombreRespuesta2 = "respuesta-2-".$i;
            $nombreRespuesta3 = "respuesta-3-".$i;
            $nombreRespuesta4 = "respuesta-4-".$i;
            $nombreRespuestaC = "respuesta_correcta_".$i;

            $pregunta = new Question();
            $pregunta->test_id = $evaluacion->id;
            $pregunta->order = $i;
            $pregunta->question = $request->$nombrePregunta;
            $pregunta->possible_answer_1 = $request->$nombreRespuesta1;
            $pregunta->possible_answer_2 = $request->$nombreRespuesta2;
            $pregunta->possible_answer_3 = $request->$nombreRespuesta3;
            $pregunta->possible_answer_4 = $request->$nombreRespuesta4;
            $pregunta->correct_answer = $request->$nombreRespuestaC;
            $pregunta->save();
        }

        if (!is_null($evaluacion->module->course_id)){
            return redirect('instructors/tests/courses/'.$evaluacion->module->course->slug.'/'.$evaluacion->module->course_id)->with('msj-exitoso', 'La evaluación del T-Course ha sido creada con éxito');
        }else{
            return redirect('instructors/tests/certifications/'.$evaluacion->module->certification->slug.'/'.$evaluacion->module->certification_id)->with('msj-exitoso', 'La evaluación de la T-Mentoring ha sido creada con éxito');
        }
    }

    public function show($slug, $id){
        $evaluacion = Test::where('id', '=', $id)
                        ->with(['questions' => function($query){
                            $query->orderBy('order', 'ASC');
                        }])->withCount('questions')
                        ->first();

        if (Auth::user()->role_id == 2){
            return view('instructors.tests.show')->with(compact('evaluacion'));
        }else{
            return view('admins.tests.show')->with(compact('evaluacion'));
        }
    }

    //**** Instructor / Ver Resumen (Presentaciones de una Evaluación)
    public function show_resume($slug, $id){
        $evaluacion = Test::where('id', '=', $id)
                        ->withCount(['students' => function($query){
                            $query->orderBy('created_at', 'DESC');
                        }])->first();


        $presentaciones = $evaluacion->students;
        $presentaciones = $presentaciones->paginate(15);
 
        return view('instructors.tests.showResume')->with(compact('presentaciones', 'evaluacion'));
    }

    //**** Instructor / Ver Resumen (Presentaciones de una Evaluación) / Ver Detalles (Respuestas)
    public function show_result($slug_test, $slug_student, $id){
        $datosPresentacion = DB::table('tests_students')
                                ->where('id', '=', $id)
                                ->first();

        $datosEvaluacion = Test::where('id', '=', $datosPresentacion->test_id)
                                ->first();

        $datosEstudiante = DB::table('users')
                                ->where('id', '=', $datosPresentacion->user_id)
                                ->select('names', 'last_names', 'country', 'state', 'profession')
                                ->first();

        $datosRespuestas = DB::table('questions_students')
                            ->where('number_test', '=', $id)
                            ->orderBy('id', 'ASC')
                            ->get();

        $respuestas = collect();
        $cont = 0;
        foreach ($datosRespuestas as $dato){
            $cont++;
            $pregunta = DB::table('questions')
                            ->where('id', '=', $dato->question_id)
                            ->first();

            $pregunta->selected_answer = $dato->selected_answer;
            $pregunta->order = $cont;
            $respuestas->push($pregunta);
        }
 
        return view('instructors.tests.showResult')->with(compact('datosEvaluacion', 'datosPresentacion', 'datosEstudiante', 'respuestas'));
    }

    public function update(Request $request){
        $evaluacion = Test::find($request->test_id);
        $evaluacion->fill($request->all());
        $evaluacion->slug = Str::slug($evaluacion->title);
        $evaluacion->save();

        return redirect('instructors/tests/show/'.$evaluacion->slug.'/'.$request->test_id)->with('msj-exitoso', 'Los datos de la evaluación han sido modificados con éxito');
    }

     public function update_question(Request $request){
        $pregunta = Question::find($request->question_id);
        $pregunta->fill($request->all());
        $pregunta->save();

        return redirect('instructors/tests/show/'.$pregunta->test->slug.'/'.$pregunta->test_id)->with('msj-exitoso', 'La pregunta ha sido modificada con éxito');
    }

    public function delete_question($id){
        $pregunta = Question::find($id);
        $pregunta->delete();

        return redirect('instructors/tests/show/'.$pregunta->test->slug.'/'.$pregunta->test_id)->with('msj-exitoso', 'La pregunta ha sido eliminada con éxito');
    }

    //*** Estudiante / Presentar Evaluación
    public function submit($slug, $id){
        $evaluacion = Test::where('id', '=', $id)
                            ->with(['questions' => function ($query){
                                $query->orderBy('order', 'ASC');
                            }])->withCount('questions')
                            ->first();

        if (!is_null($evaluacion->module->course_id)){
            $check = DB::table('courses_students')
                        ->where('course_id', '=', $evaluacion->module->course_id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->first();
        }else if (!is_null($evaluacion->module->certification_id)){
            $check = DB::table('certifications_students')
                        ->where('certification_id', '=', $evaluacion->module->certification_id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->first();
        }

        if (!is_null($check)){
            $evaluacionPresentada = DB::table('tests_students')
                                        ->where('test_id', '=', $id)
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->first();

            if (is_null($evaluacionPresentada)){
                return view('students.tests.submit')->with(compact('evaluacion'));
            }else{
                if ($evaluacionPresentada->score < 50){
                    return view('students.tests.submit')->with(compact('evaluacion'));
                }else{
                    return redirect('students/tests/show-score/'.$evaluacion->slug.'/'.$evaluacionPresentada->id);
                }
            }
        }else{
            return redirect('students');
        }
        
    }

    //*** Estudiante / Enviar Evaluación
    public function save(Request $request){
        $puntajePregunta = (100 / $request->cant_preguntas);
        $puntaje = 0;

        $id = DB::table('tests_students')
                ->insertGetId(['user_id' => Auth::user()->id,
                                'test_id' => $request->test_id,
                                'score' => 0,
                                'date' => date('Y-m-d')]);

        for ($i=1; $i <= $request->cant_preguntas; $i++) { 
            $pregunta = "pregunta-".$i;
            $respuesta = "respuesta-".$i;
            $seleccion = "seleccion-".$i;

            DB::table('questions_students')
                ->insert(['user_id' => Auth::user()->id,
                          'test_id' => $request->test_id,
                          'question_id' => $request->$pregunta,
                          'selected_answer' => $request->$seleccion,
                          'number_test' => $id]);

            if ($request->$seleccion == $request->$respuesta){
                $puntaje = $puntaje + $puntajePregunta;
            }
        }

        DB::table('tests_students')
            ->where('id', '=', $id)
            ->update(['score' => number_format($puntaje, 2)]);

        //**** Notificar al Instructor ***//
        $notificacion = new NotificationController();
        $datosTest = Test::find($request->test_id);
        
        if (!is_null($datosTest->module->course_id)){
            $notificacion->store($datosTest->module->course->user_id, 'Nueva Presentación de Evaluación', 'Han presentado la evaluación <b>'.$datosTest->title.'</b> de su T-Course <b>'.$datosTest->module->course->title.'</b>', 'fas fa-file-signature', 'instructors/tests/show-result/'.$datosTest->slug.'/'.Auth::user()->slug.'/'.$id);
        }else if (!is_null($datosTest->module->certification_id)){
            $notificacion->store($datosTest->module->certification->user_id, 'Nueva Presentación de Evaluación', 'Han presentado la evaluación <b>'.$datosTest->title.'</b> de su T-Mentoring <b>'.$datosTest->module->certification->title.'</b>', 'fas fa-file-signature', 'instructors/tests/show-result/'.$datosTest->slug.'/'.Auth::user()->slug.'/'.$id);
        }
        
        if ($puntaje >= 50){
            return redirect('students/tests/show-score/'.$datosTest->slug.'/'.$id)->with('msj-exitoso', '¡Felicidades! Has aprobado la evaluación con '.number_format($puntaje).'%');
        }else{
            return redirect('students/tests/show-score/'.$datosTest->slug.'/'.$id)->with('msj-erroneo', '¡Ups! Has reprobado la evaluación con '.number_format($puntaje).'%');
        }
    }

    //Estudiante / Ver Resultados de una Evaluación
    public function show_score($slug, $id){
        $datosEvaluacion = DB::table('tests_students')
                            ->where('id', '=', $id)
                            ->first();

        $evaluacion = Test::where('id', '=', $datosEvaluacion->test_id)
                        ->with(['questions' => function ($query){
                            $query->orderBy('order', 'ASC');
                        }])->withCount('questions')
                        ->first();

        foreach ($evaluacion->questions as $pregunta){
            $respuesta = DB::table('questions_students')
                            ->where('number_test', '=', $id)
                            ->where('question_id', '=', $pregunta->id)
                            ->first();

            $pregunta->selected_answer = $respuesta->selected_answer;
        }

        return view('students.tests.showScore')->with(compact('datosEvaluacion', 'evaluacion'));
    }

    //**** Instructor / Evaluaciones / Eliminar Evaluación
    public function delete(Request $request){
        $presentaciones = DB::table('tests_students')
                            ->where('test_id', '=', $request->test_id)
                            ->get();

        foreach ($presentaciones as $presentacion){
            DB::table('questions_students')
                ->where('number_test', '=', $presentacion->id)
                ->delete();
        }

        DB::table('tests_students')
                ->where('test_id', '=', $request->test_id)
                ->delete();

        $evaluacion = Test::find($request->test_id);
        $evaluacion->delete();

        if (!is_null($evaluacion->module->course_id)){
            return redirect('instructors/tests/courses/'.$evaluacion->module->course->slug.'/'.$evaluacion->module->course_id)->with('msj-exitoso', 'La evaluación ha sido eliminada con éxito.');
        }else{
            return redirect('instructors/tests/mentoring/'.$evaluacion->module->certification->slug.'/'.$evaluacion->module->certification_id)->with('msj-exitoso', 'La evaluación ha sido eliminada con éxito.');
        }
    }

}
