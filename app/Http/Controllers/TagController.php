<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $etiquetas = Tag::withCount('courses', 'certifications', 'podcasts')
                        ->orderBy('tag', 'ASC')
                        ->get();

        return view('admins.tags.index')->with(compact('etiquetas'));
    }

    public function store(Request $request){
        $etiqueta = new Tag($request->all());
        $etiqueta->save();

        return redirect('admins/tags')->with('msj-exitoso', 'La etiqueta ha sido creada con éxito');
    }

    public function edit($id){
        $etiqueta = Tag::find($id);

        return response()->json(
            $etiqueta
        );
    }

    public function update(Request $request){
        $etiqueta = Tag::find($request->tag_id);
        $etiqueta->fill($request->all());
        $etiqueta->save();

        return redirect('admins/tags')->with('msj-exitoso', 'La etiqueta ha sido modificada con éxito');
    }

    public function delete($id){
        Tag::destroy($id);

        return redirect('admins/tags')->with('msj-exitoso', 'La etiqueta ha sido eliminada con éxito');
    }
}
