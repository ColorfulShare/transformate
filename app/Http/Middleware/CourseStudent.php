<?php 

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Closure; use Session; use Route; use DB;

class CourseStudent{
    protected $auth;
    public function __construct(Guard $guard){
        $this->auth=$guard;
    }
    
    public function handle($request, Closure $next){
        $url = explode("/", \Request::url());
        $cant = count($url);
        if ($cant == 9){
            $id = $url[$cant-2];
            $slug = $url[$cant-3];
        }else{
            $id = end($url);
            $slug = $url[$cant-2];
        }
        
        
        $check = DB::table('courses_students')
                    ->where('course_id', '=', $id)
                    ->where('user_id', '=', $this->auth->user()->id)
                    ->first();

        if (is_null($check)){
            return redirect('students/t-courses/show/'.$slug.'/'.$id);
        }

        return $next($request);
    }
}