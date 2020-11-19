<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Auth; use DB;

class NotificationController extends Controller
{
    //*** Estudiante / Ver Todas las Notificaciones ***//
    public function index(){
        $notificaciones = Notification::where('user_id', '=', Auth::user()->id)
                            ->orderBy('id', 'DESC')
                            ->get();                                                         

        if (Auth::user()->role_id == 1){
            return view('students.notifications')->with(compact('notificaciones'));
        }else if (Auth::user()->role_id == 2){
            return view('instructors.notifications')->with(compact('notificaciones'));
        }else if (Auth::user()->role_id == 3){
            return view('admins.notifications')->with(compact('notificaciones'));
        }else if (Auth::user()->role_id == 4){
            return view('partners.notifications')->with(compact('notificaciones'));
        }
    }

    public function store($usuario, $titulo, $descripcion, $icono, $ruta){
        $notificacion = new Notification();
        $notificacion->user_id = $usuario;
        $notificacion->title = $titulo;
        $notificacion->description = $descripcion;
        $notificacion->icon = $icono;
        $notificacion->route = $ruta;
        $notificacion->status = 0;
        $notificacion->date = date('Y-m-d');
        $notificacion->save();
    }

    public function update($notificacion){
        $notificacion = Notification::find($notificacion);
        $notificacion->status = 1;
        $notificacion->save();

        return redirect($notificacion->route);
    }
}
