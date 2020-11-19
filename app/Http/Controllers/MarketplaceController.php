<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;
use App\Models\MarketProduct; use App\Models\MarketCategory;
use DB; use Auth;

class MarketplaceController extends Controller
{
    //***** Todos / Marketplace ****//
    public function index($slug = NULL, $category = 1){
        $url = explode("www", \Request::url());
        if (count($url) > 1){
            $www = 1;
        }else{
            $www = 0;
        }

        $categoriasMarket = MarketCategory::orderBy('id', 'ASC')->get();
        
        $productosDestacados = MarketProduct::where('featured', '=', 1)
                                ->where('status', '=', 2)
                                ->orderBy('id', 'DESC')
                                ->get();

        $productos = MarketProduct::where('category_id', '=', $category)
                        ->where('status', '=', 2)
                        ->orderBy('id', 'DESC')
                        ->get();

        return view('landing.marketplace')->with(compact('categoriasMarket', 'productosDestacados', 'productos', 'www'));
    }

    //***** Todos / Marketplace / Filtrar por Categorías o Subcategorías ****//
    public function products_by_category($slug_category, $id_category, $slug_subcategory = null, $id_subcategory = null){
        $categoriasP = Category::orderBy('id', 'ASC')
                        ->withCount('products')
                        ->get();

        $categoriaSelec = DB::table('categories')
                            ->where('id', '=', $id_category)
                            ->first();

        if (is_null($id_subcategory)){
            $productos = MarketProduct::where('category_id', '=', $id_category)
                            ->orderBy('id', 'DESC')
                            ->get(); 

            $cantProductos = $productos->count();
            $subcategoriaSelec = NULL;
        }else{
            $productos = MarketProduct::where('subcategory_id', '=', $id_subcategory)
                            ->orderBy('id', 'DESC')
                            ->get(); 
            
            $cantProductos = $productos->count();

            $subcategoriaSelec = DB::table('subcategories')
                                    ->where('id', '=', $id_subcategory)
                                    ->first();
        }

        return view('landing.productsByCategory')->with(compact('categoriasP', 'categoriaSelec', 'subcategoriaSelec', 'productos', 'cantProductos', 'id_category', 'id_subcategory'));
    }

    //**** Instructor / Mis Productos ****//
    public function my_products(){
        $productos = MarketProduct::where('user_id', '=', Auth::user()->id)
                        ->orderBy('id', 'DESC')
                        ->paginate(20);

        $cantProductos = MarketProduct::where('user_id', '=', Auth::user()->id)
                            ->count();

        return view('instructors.products.index')->with(compact('productos', 'cantProductos'));
    }

    //**** Instructor / Mis Productos / Crear Producto ****//
    public function create(){
        $categorias = DB::table('categories')
                        ->orderBy('id', 'ASC')
                        ->get();

        return view('instructors.products.create')->with(compact('categorias'));
    }

    //**** Instructor / Mis Productos / Crear Producto / Guardar  ****//
    public function store(Request $request){
        $producto = new MarketProduct($request->all());
        $producto->user_id = Auth::user()->id;
        $producto->slug = Str::slug($request->name);

        if ($request->hasFile('cover')){
            $file = $request->file('cover');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/uploads/images/products', $name);
            $producto->cover_name = $file->getClientOriginalName();
            $producto->cover = $name;
        }

        if ($request->hasFile('file')){
            $file2 = $request->file('file');
            $name2 = time().$file2->getClientOriginalName();
            $file2->move(public_path().'/uploads/products', $name2);
            $producto->filename = $file2->getClientOriginalName();
            $producto->file = $name2;
            $ext = explode(".", $producto->filename);
            $producto->file_extension = $ext[1];
            $producto->file_icon = $this->setIcon($producto->file_extension);
        }
        
        $producto->save();

        return redirect('instructors/products')->with('msj-exitoso', 'El producto ha sido creado con éxito');
    }

    //**** Instructor / Mis Productos / Editar Producto ****//
    public function edit($slug, $id){
        $producto = MarketProduct::find($id);

        $categorias = DB::table('categories')
                        ->orderBy('id', 'ASC')
                        ->get();

        $subcategorias = DB::table('subcategories')
                            ->where('category_id', '=', $producto->category_id)
                            ->orderBy('id', 'ASC')
                            ->get();

        return view('instructors.products.edit')->with(compact('producto', 'categorias', 'subcategorias'));
    }

    //**** Instructor / Mis Productos / Editar Producto / Guardar Cambios ****//
    public function update(Request $request){
        $producto = MarketProduct::find($request->product_id);
        $producto->fill($request->all());
        $producto->slug = Str::slug($request->name);

        if ($request->hasFile('cover')){
            $file = $request->file('cover');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/uploads/images/products', $name);
            $producto->cover_name = $file->getClientOriginalName();
            $producto->cover = $name;
        }

        if ($request->hasFile('file')){
            $file2 = $request->file('file');
            $name2 = time().$file2->getClientOriginalName();
            $file2->move(public_path().'/uploads/products', $name2);
            $producto->filename = $file2->getClientOriginalName();
            $producto->file = $name2;
            $ext = explode(".", $producto->filename);
            $producto->file_extension = $ext[1];
            $producto->file_icon = $this->setIcon($producto->file_extension);
        }
        
        $producto->save();

        if(!$request->ajax()){
            return redirect('instructors/products')->with('msj-exitoso', 'El producto ha sido modificado con éxito');
        }else{
            return response()->json(
                "Operación Exitosa."
            );
        }
    }

    //*** Instructor / Productos / Historial de Compras de un Producto ***//
    public function purchases_record($slug, $id){
        $compras = PurchaseDetail::where('product_id', '=', $id)
                        ->with('commission')
                        ->orderBy('created_at', 'DESC')
                        ->paginate(25);

        $cantCompras = PurchaseDetail::where('product_id', '=', $id)
                        ->count();

        return view('instructors.purchases.index')->with(compact('compras', 'cantCompras'));
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

    public function destroy($id)
    {
        //
    }

    //**** Estudiante - Instructor / Mis Productos Comprados ****//
    public function my_purchased_products(){
        $misProductos = DB::table('products_users')
                            ->where('user_id', '=', Auth::user()->id)
                            ->get();

        $cantProductos = $misProductos->count();

        $productos = collect();
        if ($cantProductos > 0){
            foreach ($misProductos as $miProducto){
                $producto = MarketProduct::where('id', '=', $miProducto->product_id)->first();
                $productos->push($producto);
            }
        }
        
        if (Auth::user()->role_id == 1){
            return view('students.myProducts')->with(compact('productos', 'cantProductos'));
        }else{
            return view('instructors.products.myProducts')->with(compact('productos', 'cantProductos'));
        }
    }
}
