<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB; use Mail;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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

    public function recover_password(Request $request){
        $usuario = DB::table('users')
                    ->select('id', 'names', 'last_names')
                    ->where('email', '=', $request->email)
                    ->first();

        if (!is_null($usuario)){
            $claveTemporal = strtolower(Str::random(9));

            DB::table('users')
                ->where('id', '=', $usuario->id)
                ->update(['password' => Hash::make($claveTemporal)]);

            $data['correo'] = $request->email;
            $data['cliente'] = $usuario->names." ".$usuario->last_names;
            $data['clave'] = $claveTemporal;

            Mail::send('emails.recoverPassword',['data' => $data], function($msg) use ($data){
                $msg->to($data['correo']);
                $msg->subject('Recuperar Contraseña');
            });

            return redirect('/')->with('msj-exitoso', 'Se ha enviado una clave temporal a su correo registrado.');

        }else{
            return redirect('/')->with('msj-erroneo', 'El correo ingresado no se encuentra registrado.');
        }
    }
}
