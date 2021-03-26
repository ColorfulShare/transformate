<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterClass; use App\Models\ResourceFile;
use DB; use Storage; use Auth;

class MasterClassController extends Controller
{
    //***** Admin / Master Class / Disponibles ****/
    public function index(){
        $clases = MasterClass::where('status', '=', 1)
                    ->orderBy('id', 'DESC')
                    ->get();

        return view('admins.masterClass.index')->with(compact('clases'));
    }

    //***** Admin / Master Class / Deshabilitadas ****/
    public function disabled(){
        $clases = MasterClass::where('status', '=', 0)
                    ->orderBy('id', 'DESC')
                    ->get();

        return view('admins.masterClass.disabled')->with(compact('clases'));
    }

    //***** Admin / Master Class / Nueva Master Class ****/
    public function create(){
        $etiquetas = DB::table('tags')
                        ->orderBy('tag', 'ASC')
                        ->get();

        return view('admins.masterClass.create')->with(compact('etiquetas'));
    }

    //***** Admin / Master Class / Nueva Master Class / Guardar ****/
    public function store(Request $request){
        $messages = [
            'title.required' => 'El campo Título es requerido.',
            'subtitle.required' => 'El campo Subtítulo es requerido.',
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'subtitle' => 'required|max:255',
        ], $messages);

        if ($validator->fails()) {
            return redirect('admins/t-master-class/create')
                ->withErrors($validator)
                ->withInput();
        }

        $clase = new MasterClass($request->all());
        $clase->slug = Str::slug($clase->title);
        $clase->save();

        if (!is_null($request->tags)){
            foreach ($request->tags as $tag){
                DB::table('master_class_tags')->insert(
                    ['master_class_id' => $clase->id, 'tag_id' => $tag]
                );
            }
        }

        return redirect('admins/t-master-class/edit/'.$clase->slug.'/'.$clase->id)->with('msj-exitoso', 'La T-Master Class ha sido creada con éxito. Por favor proceda a cargar el contenido multimedia.');
    }

    public function show($slug, $id){
        $clase = MasterClass::where('id', '=', $id)
                    ->with('tags')
                    ->withCount('resources')
                    ->first();

        return view('landing.showMasterClass')->with(compact('clase'));
    }

    public function edit($slug, $id){
        $clase = MasterClass::find($id);

        $cantRecursos = $clase->resources->count();

        $etiquetasActivas = [];
        foreach ($clase->tags as $etiq){
            array_push($etiquetasActivas, $etiq->id);
        }

        $etiquetas = DB::table('tags')
                        ->orderBy('tag', 'ASC')
                        ->get();

        return view('admins.masterClass.edit')->with(compact('clase', 'cantRecursos', 'etiquetasActivas', 'etiquetas'));
    }

    public function update(Request $request){
        if(!$request->ajax()){
            $messages = [
                'title.required' => 'El campo Título es requerido.',
                'subtitle.required' => 'El campo Subtítulo es requerido.',
            ];

            $validator = Validator::make($request->all(), [
                'title' => 'required|max:255',
                'subtitle' => 'required|max:100',
            ], $messages);

            $clase = MasterClass::find($request->master_class_id);

            if ($validator->fails()) {
                return redirect('admins/t-master-class/edit/'.$clase->slug.'/'.$clase->id)
                        ->withErrors($validator)
                        ->withInput();
            }
            
            $clase->fill($request->all());
            $clase->slug = Str::slug($clase->title);

            DB::table('master_class_tags')
                ->where('master_class_id', '=', $clase->id)
                ->delete();

            if (!is_null($request->tags)){
                foreach ($request->tags as $tag){
                    DB::table('master_class_tags')->insert(
                        ['master_class_id' => $clase->id, 'tag_id' => $tag]
                    );
                }
            }

            $clase->save();

            return redirect('admins/t-master-class/edit/'.$clase->slug.'/'.$clase->id)->with('msj-exitoso', 'Los datos de la T-Master Class han sido actualizados con éxito');
        }else{
            $clase = MasterClass::find($request->master_class_id);

            if (isset($request->file_type)){
                if ($request->file_type == 'cover'){
                    $imagen = public_path().'/uploads/images/master-class/'.$clase->cover;
                    if (getimagesize($imagen)) {
                        unlink($imagen);
                    }

                    $clase->cover = NULL;
                    $clase->cover_name = NULL;
                }
            }else{
                if ($request->hasFile('cover')){
                    /*if (!is_null($clase->cover)){
                       $imagen = public_path().'/uploads/images/master-class/'.$clase->cover;
                        if (getimagesize($imagen)) {
                            unlink($imagen);
                        }
                    }*/

                    $file = $request->file('cover');
                    $name = time().$file->getClientOriginalName();
                    $file->move(public_path().'/uploads/images/master-class', $name);
                    $clase->cover = $name;
                    $clase->cover_name = $file->getClientOriginalName();
                }else if ($request->hasFile('video_file')){
                    if (!is_null($clase->video_file)){
                        $nombreArchivo = explode("master-class/".$clase->id."/", $clase->video_file);
                        if (Storage::disk('s3')->has('master-class/'.$clase->id."/".$nombreArchivo[1])){
                            Storage::disk('s3')->delete('master-class/'.$clase->id."/".$nombreArchivo[1]);
                        }
                    }
                    
                    $file = $request->file('video_file');
                    $upload = Storage::disk('s3')->put('master-class/'.$clase->id, $file, 'public');
                    $clase->video_file = 'https://transformate-content.s3.us-east-2.amazonaws.com/'.$upload;
                    $clase->video_filename = $file->getClientOriginalName();
                }
            }

            $clase->save();

            return response()->json(
                "Operación Exitosa."
            );
        }
    }

    public function load_resource(Request $request){
        $file = $request->file('resource');

        $recurso = new ResourceFile();
        $recurso->master_class_id = $request->master_class_id;
        $recurso->filename = $file->getClientOriginalName();
        $recurso->file_extension = $file->getClientOriginalExtension();
        $recurso->file_icon = $this->setIcon($recurso->file_extension);
        //$recurso->link = 'https://transformate-videos.s3.us-east-2.amazonaws.com/'.$request->direccion; 
        $recurso->save();

        $upload = Storage::disk('s3')->put('master-class/'.$request->master_class_id.'/resources', $file, 'public');
        $recurso->link = 'https://transformate-content.s3.us-east-2.amazonaws.com/'.$upload;
        $recurso->save();

        return response()->json(
            true
        );
    }

    public function setIcon($extension){
        switch ($extension) {
            case 'mp4':
                $icono = 'far fa-file-video';
            break;
            case 'docx':
                $icono = 'far fa-file-word';
            break;
            case 'pptx':
                $icono = 'far fa-file-powerpoint';
            break;
            case 'jpg':
                $icono = 'far fa-file-image';
            break;
            case 'jpeg':
                $icono = 'far fa-file-image';
            break;
            case 'png':
                $icono = 'far fa-file-image';
            break;
            case 'svg':
                $icono = 'far fa-file-image';
            break;
            case 'gif':
                $icono = 'far fa-file-image';
            break;
            case 'mp3':
                $icono = 'far fa-file-audio';
            break;
            case 'xlsx':
                $icono = 'far fa-file-excel';
            break;
            case 'zip':
                $icono = 'far fa-file-archive';
            break;
            case 'rar':
                $icono = 'far fa-file-archive';
            break;
            case 'txt':
                $icono = 'far fa-file-alt';
            break;
            case 'csv':
                $icono = 'far fa-file-csv';
            break;
            default:
                $icono = 'far fa-file';
            break;
        }

        return $icono;
    }

    //** Instructor / Listado de recursos de una T-Master Class
    //Método Ajax que actualiza después de cargar un nuevo recurso)
    public function resources($clase){
        $recursos = ResourceFile::where('master_class_id', '=', $clase)
                        ->get();

        $cantRecursos = $recursos->count();

        return view('admins.masterClass.downloadResources')->with(compact('recursos', 'cantRecursos'));
    }

    //** Admin / Editar T-Master Class / Eliminar Recurso Descargable 
    public function delete_resource(Request $request){
        $recurso = ResourceFile::find($request->resource_id);

        $nombreArchivo = explode("resources/", $recurso->link);
        if (Storage::disk('s3')->has('master-class/'.$recurso->master_class_id.'/resources/'.$nombreArchivo[1])){
            Storage::disk('s3')->delete('master-class/'.$recurso->master_class_id.'/resources/'.$nombreArchivo[1]);
        }
        
        $recurso->delete();

        return response()->json(
            true
        );
    }

    public function change_status($id, $status){
        DB::table('master_class')
            ->where('id', '=', $id)
            ->update(['status' => $status]);

        if ($status == 0){
            return redirect('admins/t-master-class')->with('msj-exitoso', 'La T-Master Class ha sido deshabilitada con éxito.');
        }else{
            return redirect('admins/t-master-class/disabled')->with('msj-exitoso', 'La T-Master Class ha sido habilitada con éxito.');
        }
    }
}
