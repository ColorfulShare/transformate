<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; use App\Models\Commission;
use Auth; use DB; 

class PartnerController extends Controller{
	public function home(){
        $ganancias = DB::table('commissions')
                        ->where('user_id', '=', Auth::user()->id)
                        ->select(DB::raw('SUM(amount) as total'), 'type')
                        ->groupBy('type')
                        ->get();

        $gananciasRecientes = Commission::where('user_id', '=', Auth::user()->id)
                                ->orderBy('id', 'DESC')
                                ->take(10)
                                ->get();

        return view('partners.index')->with(compact('ganancias', 'gananciasRecientes'));
    }

    //T-Partner / Ver Su Perfil
    public function my_profile(){
        $ganancias = Commission::where('user_id', '=', Auth::user()->id)
                        ->orderBy('date', 'DESC')
                        ->get();

        return view('partners.myProfile')->with(compact('ganancias'));
    }

    //T-Partner / Actualizar Datos de Perfil
    public function update_profile(Request $request){
        $usuario = User::find(Auth::user()->id);
        $usuario->fill($request->all());

        $nombre = $usuario->names." ".$usuario->last_names;
        $usuario->slug = Str::slug($nombre);
        $pic = explode("-", $usuario->slug);
        $usuario->afiliate_code = $pic[0]."pro".$usuario->id;
        $usuario->partner_code = $pic[0]."tp".$usuario->id;

        if ($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/uploads/images/users', $name);
            $usuario->avatar = $name;
        }

        if ($request->clave_actual != NULL){
            if (Hash::check($request->clave_actual, Auth::user()->password)){
                $usuario->password = Hash::make($request->clave);
            }else{
                return redirect('instructors/profile')->with('msj-error', 'La clave ingresada es incorrecta.');
            }
        }

        $usuario->save();

        if (isset($request->status)){
            Auth::logout();
            return redirect('/')->with('msj-exitoso', 'Su cuenta ha sido cerrada permanentemente.');
        }

        return redirect('t-partners/profile')->with('msj-exitoso', 'Sus datos han sido actualizados con éxito.');
    }

    //**** Admin / Usuarios / Partners
    public function index(Request $request){
        $partners = User::where('role_id', '=', 4)
                        ->orderBy('names', 'ASC')
                        ->get();

        return view('admins.users.partners')->with(compact('partners'));
    }
}