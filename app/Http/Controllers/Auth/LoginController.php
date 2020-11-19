<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;
use App\Models\User; use App\Models\Product;
use Auth; use DB; use Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function post_login(Request $request){
        $messages = [
            'recaptcha' => 'Debe validar su captcha haciendo clic en "No soy un robot"',
        ];

        $validator = Validator::make($request->all(), [
            //recaptchaFieldName() => recaptchaRuleName()
        ], $messages);

        if ($validator->fails()) {
            return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
            if (!is_null(Auth::user()->email_verified_at)){
                if ($request->session()->has('cart')) {
                    $items = $request->session()->get('cart');

                    $cont = 0;
                    foreach ($items as $item) {
                        $cont++;
                        $tipoItem = explode("-", $item);

                        if ($tipoItem[0] == 'curso'){
                            $itemAgregado = DB::table('shopping_cart')
                                                ->where('user_id', '=', Auth::user()->id)
                                                ->where('course_id', '=', $tipoItem[1])
                                                ->count();
                        }else if ($tipoItem[0] == 'certificacion'){
                            $itemAgregado = DB::table('shopping_cart')
                                                ->where('user_id', '=', Auth::user()->id)
                                                ->where('certification_id', '=', $tipoItem[1])
                                                ->count();
                        }else if ($tipoItem[0] == 'podcast'){
                            $itemAgregado = DB::table('shopping_cart')
                                                ->where('user_id', '=', Auth::user()->id)
                                                ->where('podcast_id', '=', $tipoItem[1])
                                                ->count();
                        }else if ($tipoItem[0] == 'membresia'){
                            $itemAgregado = DB::table('shopping_cart')
                                                ->where('user_id', '=', Auth::user()->id)
                                                ->where('membership_id', '=', $tipoItem[1])
                                                ->count();
                        }else{
                            $itemAgregado = DB::table('shopping_cart')
                                                ->where('user_id', '=', Auth::user()->id)
                                                ->where('product_id', '=', $tipoItem[1])
                                                ->count();
                        }

                        if ($itemAgregado == 0){
                            $item = new Product();
                            $item->user_id = Auth::user()->id;
                            $item->date = date('Y-m-d');
                            if ($tipoItem[0] == 'curso'){
                                $item->course_id = $tipoItem[1];
                            }else if ($tipoItem[0] == 'certificacion'){
                                $item->certification_id = $tipoItem[1];
                            }else if ($tipoItem[0] == 'podcast'){
                                $item->podcast_id = $tipoItem[1];
                            }else if ($tipoItem[0] == 'membresia'){
                                $item->membership_id = $tipoItem[1];
                            }else{
                                $item->product_id = $tipoItem[1];
                            }
                            if (isset($tipoItem[2])){
                                $item->instructor_code = $tipoItem[2];
                            }
                            $item->save();
                        }
                    }

                    if (Auth::user()->role_id == 1){
                        return redirect('students/shopping-cart/checkout');
                    }
                }

                return redirect('home');
            }else{ 
                if (Auth::user()->role_id == 1){
                    return redirect('t-courses')->with('msj-informativo', 'Recuerde validar su correo electrónico para una mejor comunicación con la plataforma. Por favor, revise su correo electrónico y presione el link que aparece en el mensaje de bienvenida de TransfórmatePRO.');
                }else{
                    //Auth::logout();
                    //return redirect('/')->with('msj-erroneo', 'Debe verificar su correo para poder ingresar a la plataforma. Por favor, revise su correo electrónico y presione el link que aparece en el mensaje de bienvenida de TransfórmatePRO.');
                    return redirect('instructors');
                }
            }
        }else{
            return redirect('/')->with('msj-erroneo', 'Los datos ingresados no coinciden con nuestros registros.');
        }
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider, Request $request)
    {
        try{
            $user = Socialite::driver($provider)->user();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            abort(403, 'Unauthorized action.');
            return redirect()->to('/');
        }

        $attributes = [
            'provider' => $provider,
            'provider_id' => $user->getId(),
            'email' => $user->getEmail(),
            'password' => isset($attributes['password']) ? $attributes['password'] : Hash::make(str_random(16)),
            'role_id' => 1,
            'names' => $user->getName(),
            'slug' => Str::slug($user->getName()),
            'afiliate_code' =>  uniqid().rand(10,100000)
        ];
 
        $user = User::where('email', $user->getEmail())->first();

        if (!$user){
            try{
                $user = User::create($attributes);
                $pic = explode("-", $user->slug);
                $user->afiliate_code = $pic[0]."pro".$user->id;
                $user->partner_code = $pic[0]."tp".$user->id;
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();

                $data['correo'] = $user->email;
                $data['usuario'] = $user->names." ".$user->last_names;
                $data['id_usuario'] = $user->id;
                $data['clave'] = "";
                $data['tipo_usuario'] = 1;
                $data['token'] = NULL;

                Mail::send('emails.welcome',['data' => $data], function($msg) use ($data){
                    $msg->to($data['correo']);
                    $msg->subject('Bienvenido a Transfórmate');
                });
            }catch (ValidationException $e){
              return redirect()->to('/');
            }
        }
 
        $this->guard()->login($user);

        if ($request->session()->has('cart')) {
            $items = $request->session()->get('cart');
            
            $cont = 0;
            foreach ($items as $item) {
                $cont++;
                $tipoItem = explode("-", $item);

                if ($tipoItem[0] == 'curso'){
                    $itemAgregado = DB::table('shopping_cart')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->where('course_id', '=', $tipoItem[1])
                                        ->count();
                }else if ($tipoItem[0] == 'certificacion'){
                    $itemAgregado = DB::table('shopping_cart')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->where('certification_id', '=', $tipoItem[1])
                                        ->count();
                }else if ($tipoItem[0] == 'podcast'){
                    $itemAgregado = DB::table('shopping_cart')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->where('podcast_id', '=', $tipoItem[1])
                                        ->count();
                }else if ($tipoItem[0] == 'membresia'){
                    $itemAgregado = DB::table('shopping_cart')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->where('membership_id', '=', $tipoItem[1])
                                        ->count();
                }else{
                    $itemAgregado = DB::table('shopping_cart')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->where('product_id', '=', $tipoItem[1])
                                        ->count();
                }

                if ($itemAgregado == 0){
                    $item = new Product();
                    $item->user_id = Auth::user()->id;
                    $item->date = date('Y-m-d');
                    if ($tipoItem[0] == 'curso'){
                        $item->course_id = $tipoItem[1];
                    }else if ($tipoItem[0] == 'certificacion'){
                        $item->certification_id = $tipoItem[1];
                    }else if ($tipoItem[0] == 'podcast'){
                        $item->podcast_id = $tipoItem[1];
                    }else if ($tipoItem[0] == 'membresia'){
                        $item->membership_id = $tipoItem[1];
                    }else{
                        $item->product_id = $tipoItem[1];
                    }
                    if (isset($tipoItem[2])){
                        $item->instructor_code = $tipoItem[2];
                    }
                    $item->save();
                }
            }

            if (Auth::user()->role_id == 1){
                return redirect('students/shopping-cart/checkout')->with('msj-exitoso', 'Su registro se ha realizado con éxito. Ya puede completar su compra.');
            }
        }

        return redirect()->to($this->redirectTo);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
