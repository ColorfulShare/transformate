<?php 

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Closure; use Session; use Route; use DB;

class TicketUser{
    protected $auth;
    public function __construct(Guard $guard){
        $this->auth=$guard;
    }
    
    public function handle($request, Closure $next){
        $url = explode("/", \Request::url());
        $cant = count($url);
        $id = end($url);
        
        $check = DB::table('tickets')
                    ->select('user_id')
                    ->where('id', '=', $id)
                    ->first();

        if ($check->user_id != $this->auth->user()->id){
            if ($this->auth->user()->role_id == 1){
                return redirect('students/tickets');
            }else{
                return redirect('instructors/tickets');
            }
        }

        return $next($request);
    }
}