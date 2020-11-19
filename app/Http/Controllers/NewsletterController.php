<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscriber;
use App\Models\Newsletter;
use DB; use Mail;

class NewsletterController extends Controller
{
    public function new_subscriber(Request $request){
        $messages = [
            'email.unique' => 'El correo ingresado ya se encuentra registrado en nuestro Newsletter',
            'recaptcha' => 'Debe validar su captcha haciendo clic en "No soy un robot"',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'unique:subscribers',
            recaptchaFieldName() => recaptchaRuleName()
        ], $messages);

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

        $check = DB::table('subscribers')
                    ->where('email', '=', $request->email)
                    ->first();
        
        if (is_null($check)){
            $suscriptor = new Subscriber($request->all());
            $suscriptor->save(); 
            
            return redirect('/')->with('msj-exitoso', 'Te has suscrito con éxito a nuestro newsletter');
        }else{
            return redirect('/')->with('msj-erroneo', 'Ya te encuentras suscrito a nuestro newsletter');
        }
    }

    public function index(){
        $boletines = Newsletter::orderBy('id', 'DESC')->get();

        return view('admins.newsletters.index')->with(compact('boletines'));
    }

    public function store(Request $request){
		$usuarios = DB::table('subscribers')
						->select('email')
                        ->get();

        $boletin = new Newsletter($request->all());
		$data['adjunto'] = NULL;
		if ($request->hasFile('adjunto')){
            $file = $request->file('adjunto');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/emails/files/', $name);
            $data['adjunto'] = $name;
            $boletin->file = $name;
        }
        $boletin->save();


        $data['content'] = $boletin->body;
        $data['title'] = $boletin->title;

        foreach ($usuarios as $usuario){
        	$data['correo'] = $usuario->email; 
        	Mail::send('emails.newsletter',['data' => $data], function($msg) use ($data){
                $msg->to($data['correo']);
                $msg->subject($data['title']);
                if (!is_null($data['adjunto'])){
                	$msg->attach('https://www.transformatepro.com/emails/files/'.$data['adjunto']);
                }
            });
        } 
        return redirect("admins/newsletters")->with('msj-exitoso', 'El boletín ha sido enviado a los suscriptores con éxito.'); 
    }

    public function subscribers(){
        $suscriptores = Subscriber::orderBy('id', 'DESC')->get();

        return view('admins.newsletters.subscribers')->with(compact('suscriptores'));
    }
}
