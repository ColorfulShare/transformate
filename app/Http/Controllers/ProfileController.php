<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    //**** Admin / Perfiles Administrativos / Listado ****//
    public function index(){
        $perfiles = Profile::orderBy('name', 'ASC')
                        ->withCount('users')
                        ->get();

        return view('admins.profiles.index')->with(compact('perfiles'));
    }

    //**** Admin / Perfiles Administrativos / Guardar Nuevo Perfil ****//
    public function store(Request $request){
        $perfil = new Profile($request->all());
        $perfil->save();

        return redirect('admins/administrative-profiles')->with('msj-exitoso', 'El perfil administrativo ha sido creado con éxito');
    }

    //**** Admin / Perfiles Administrativos / Ver Perfil ****//
    public function show($id){
        $perfil = Profile::find($id);

        return view('admins.profiles.show')->with(compact('perfil'));
    }

    //**** Admin / Perfiles Administrativos / Actualizar Perfil ****//
    public function update(Request $request){
        $perfil = Profile::find($request->id);
        $perfil->fill($request->all());
        $perfil->save();

        return redirect('admins/administrative-profiles/show/'.$request->id)->with('msj-exitoso', 'El perfil administrativo ha sido modificado con éxito'); 
    }

    //**** Admin / Perfiles Administrativos / Eliminar Perfil ****//
    public function delete($id){
        Profile::destroy($id);

         return redirect('admins/administrative-profiles')->with('msj-exitoso', 'El perfil administrativo ha sido eliminado con éxito');
    }
}
