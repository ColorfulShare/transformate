<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str as Str;
use App\Models\User; use App\Models\Product; use App\Models\Notification;
use DB; use Auth; use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request){
        $messages = [
            'email.unique' => 'El correo ingresado ya se encuentra registrado.',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres.',
            'password.max' => 'La contraseña debe contener máximo 25 caracteres.',
            'recaptcha' => 'Debe validar su captcha haciendo clic en "No soy un robot"',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'unique:users',
            'password' => 'min:8|max:25',
            recaptchaFieldName() => recaptchaRuleName()
        ], $messages);

        if ($validator->fails()) {
            if (isset($request->checkout)){
                return redirect('shopping-cart/checkout')
                    ->withErrors($validator)
                    ->withInput();
            }

            return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
        }

        $sponsor = NULL;
        $rol = 1;
        $presentation = NULL;
        $curriculum = NULL;
        $course_review = NULL;
        $status = 1;

        if (isset($request->instructor)){
            $rol = 2;
            if ( (isset($request->code)) && (!is_null($request->code)) ){
                $datosSponsor = DB::table('users')
                                    ->select('id')
                                    ->where('afiliate_code', '=', $request->code)
                                    ->first();

                if ($datosSponsor != NULL){
                    $sponsor = $datosSponsor->id;
                }else{
                    return redirect('/')->with('msj-erroneo', 'El código de afiliación ingresado no es válido');
                } 
            }

            if ($request->hasFile('curriculum')){
                $file = $request->file('curriculum');
                $name = time().$file->getClientOriginalName();
                $file->move(public_path().'/uploads/instructors/resumes', $name);
                $curriculum = $name;
            }

            if ($request->hasFile('video_presentation')){
                $file2 = $request->file('video_presentation');
                $name2 = time().$file2->getClientOriginalName();
                $file2->move(public_path().'/uploads/instructors/presentations', $name2);
                $presentation = $name2;
            }

            $course_review = $request->course_review;
            $status = 2;
        }

        $nombreCompleto = $request->names." ".$request->last_names;

        $user = User::create([
            'names' => $request->names,
            'last_names' => $request->last_names,
            'slug' => Str::slug($nombreCompleto),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            //'birthdate' => $request->birthdate,
            'role_id' => $rol,
            'sponsor_id' => $sponsor,
            'afiliate_code' =>  0,
            'curriculum' => $curriculum,
            'video_presentation' => $presentation,
            'course_review' => $course_review,
            'status' => $status,
        ]);

        $pic = explode("-", $user->slug);
        $user->afiliate_code = $pic[0]."pro".$user->id;
        $user->partner_code = $pic[0]."tp".$user->id;
        $user->email_token = $user->slug."-".$user->id."-tpro";
        $user->save();

        $data['correo'] = $user->email;
        $data['usuario'] = $user->names." ".$user->last_names;
        $data['id_usuario'] = $user->id;
        $data['clave'] = $request->password;
        $data['tipo_usuario'] = $rol;
        $data['token'] = $user->email_token;

        Mail::send('emails.welcome',['data' => $data], function($msg) use ($data){
            $msg->to($data['correo']);
            $msg->subject('Bienvenido a Transfórmate');
        });

        if (!is_null($sponsor)){
            $notificacion = new \App\Http\Controllers\NotificationController();
            $notificacion->store($sponsor, 'Nuevo Referido', 'Tiene un nuevo referido <b>('.$user->email.')</b>', 'fa fa-user-plus', 'instructors/referrals');
        }

        if ($rol == 2){
            //**** Notificar al Administrador ***//
            $notificacion = new Notification();
            $notificacion->user_id = 1;
            $notificacion->title = 'Nuevo Registro de Mentor';
            $notificacion->description = 'Un nuevo mentor ha solicitado unirse a la plataforma';
            $notificacion->icon = 'fa fa-user-plus';
            $notificacion->route = 'admins/users/instructors/pending-list';
            $notificacion->status = 0;
            $notificacion->date = date('Y-m-d');
            $notificacion->save();

            return redirect('/')->with('msj-exitoso', 'Su cuenta ha sido creada con éxito. Por favor revise su correo electrónico para continuar.');
        }else{
            //Auth::attempt(['email' => $request->email, 'password' => $request->password]);

            if ($request->session()->has('cart')) {
                $items = $request->session()->get('cart');
                
                $cont = 0;
                foreach ($items as $item) {
                    $cont++;
                    $tipoItem = explode("-", $item);

                    if ($tipoItem[0] == 'curso'){
                        $itemAgregado = DB::table('shopping_cart')
                                            ->where('user_id', '=', $user->id)
                                            ->where('course_id', '=', $tipoItem[1])
                                            ->count();
                    }else if ($tipoItem[0] == 'certificacion'){
                        $itemAgregado = DB::table('shopping_cart')
                                            ->where('user_id', '=', $user->id)
                                            ->where('certification_id', '=', $tipoItem[1])
                                            ->count();
                    }else if ($tipoItem[0] == 'podcast'){
                        $itemAgregado = DB::table('shopping_cart')
                                            ->where('user_id', '=', $user->id)
                                            ->where('podcast_id', '=', $tipoItem[1])
                                            ->count();
                    }else if ($tipoItem[0] == 'membresia'){
                        $itemAgregado = DB::table('shopping_cart')
                                            ->where('user_id', '=', $user->id)
                                            ->where('membership_id', '=', $tipoItem[1])
                                            ->count();
                    }else{
                        $itemAgregado = DB::table('shopping_cart')
                                            ->where('user_id', '=', $user->id)
                                            ->where('product_id', '=', $tipoItem[1])
                                            ->count();
                    }

                    if ($itemAgregado == 0){
                        $item = new Product();
                        $item->user_id = $user->id;
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
            }

            return redirect('/')->with('msj-exitoso', 'Su cuenta ha sido creada con éxito. Por favor revise su correo electrónico para continuar.');
        }
    }

    public function verify_email($id, $token){
        $usuario = User::find($id);

        if (!is_null($usuario)){
            if ($usuario->email_token == $token){
                $usuario->email_verified_at = date('Y-m-d H:i:s');
                $usuario->email_token = NULL;
                $usuario->save();

                return redirect('/')->with('msj-exitoso', 'Su correo ha sido verificado con éxito. Por favor, inicie sesión para disfrutar de la plataforma.');
            }else{
                return redirect('/')->with('msj-erroneo', 'Su correo no ha podido ser verificado.');
            }
        }else{
            return redirect('/')->with('msj-erroneo', 'Su correo no ha podido ser verificado.');
        }
    }
}
