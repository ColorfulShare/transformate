<?php 

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Closure; use Session; use Route; use DB;

class CertificationInstructor{
    protected $auth;
    public function __construct(Guard $guard){
        $this->auth=$guard;
    }
    
    public function handle($request, Closure $next){
        $url = explode("/", \Request::url());
        $cant = count($url);
        $id = end($url);
        
        $check = DB::table('certifications')
                    ->select('user_id')
                    ->where('id', '=', $id)
                    ->first();

        if ($check->user_id != $this->auth->user()->id){
            return redirect('instructors');
        }

        return $next($request);
    }
}