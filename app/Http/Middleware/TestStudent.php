<?php 

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Test;
use Closure; use Session; use Route; use DB;

class TestStudent{
    protected $auth;
    public function __construct(Guard $guard){
        $this->auth=$guard;
    }
    
    public function handle($request, Closure $next){
        $url = explode("/", \Request::url());
        $cant = count($url);
        $id = end($url);
        
        $evaluacion = Test::where('id', '=', $id)
                        ->first();

        if (!is_null($evaluacion->module->course_id)){
            $check = DB::table('courses_students')
                        ->where('course_id', '=', $evaluacion->module->course_id)
                        ->where('user_id', '=', $this->auth->user()->id)
                        ->first();
        }else if (!is_null($evaluacion->module->certification_id)){
            $check = DB::table('certifications_students')
                        ->where('certification_id', '=', $evaluacion->module->certification_id)
                        ->where('user_id', '=', $this->auth->user()->id)
                        ->first();
        }

        if (is_null($check)){
            return redirect('students/t-courses');
        }

        return $next($request);
    }
}