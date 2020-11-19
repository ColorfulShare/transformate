<?php 

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Closure; use Session; use Route; use DB;

class PodcastStudent{
    protected $auth;
    public function __construct(Guard $guard){
        $this->auth=$guard;
    }
    
    public function handle($request, Closure $next){
        $url = explode("/", \Request::url());
        $cant = count($url);
        $id = end($url);
        $slug = $url[$cant-2];
        
        $check = DB::table('podcasts_students')
                    ->where('podcast_id', '=', $id)
                    ->where('user_id', '=', $this->auth->user()->id)
                    ->first();

        if (is_null($check)){
            return redirect('students/t-books/show/'.$slug.'/'.$id);
        }

        return $next($request);
    }
}