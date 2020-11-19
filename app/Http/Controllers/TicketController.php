<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ticket; use App\Models\TicketTracking; use App\Models\TicketCategory;
use Auth; use DB;

class TicketController extends Controller
{
    /*** Instructor _ Estudiante / Tickets  ***/
    public function index(){
        $tickets = Ticket::where('user_id', '=', Auth::user()->id)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(20);

        $totalTickets = Ticket::where('user_id', '=', Auth::user()->id)
                            ->count();

        $categorias = TicketCategory::orderBy('title')
                        ->select('id', 'title')
                        ->get();

        if (Auth::user()->role_id == 1){
            return view('students.tickets.index')->with(compact('tickets', 'totalTickets', 'categorias'));
        }else if (Auth::user()->role_id == 2){
            return view('instructors.tickets.index')->with(compact('tickets', 'totalTickets', 'categorias'));
        }
    }

    /*** Instructor - Estudiante / Tickets / Crear Ticket ***/
    public function store(Request $request){
        $messages = [
            'description.required' => 'Es necesario que especifique una descripción para el ticket.',
        ];

        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ], $messages);

        if ($validator->fails()) {
            if (Auth::user()->role_id == 1){
                return redirect('students/tickets')
                    ->withErrors($validator)
                    ->withInput();
            }else{
                return redirect('instructors/tickets')
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $ticket = new Ticket($request->all());
        $ticket->user_id = Auth::user()->id;
        $ticket->save();

        if (Auth::user()->role_id == 1){
            return redirect('students/tickets')->with('msj-exitoso', 'El ticket ha sido creado con éxito.');
        }else if (Auth::user()->role_id == 2){
            return redirect('instructors/tickets')->with('msj-exitoso', 'El ticket ha sido creado con éxito.');
        }
    }

    /*** Instructor / Tickets / Cerrar Ticket ***/
    public function close($id){
        DB::table('tickets')
            ->where('id', '=', $id)
            ->update(['status' => 3,
                      'updated_at' => date('Y-m-d H:i:s'),
                      'resolved_at' => date('Y-m-d H:i:s')]);

        if (Auth::user()->role_id == 1){
            return redirect('students/tickets/show/'.$id)->with('msj-exitoso', 'El ticket ha sido cerrado con éxito.');
        }else if (Auth::user()->role_id == 2){
             return redirect('instructors/tickets/show/'.$id)->with('msj-exitoso', 'El ticket ha sido cerrado con éxito.');
        }
    }

    /*** Admin / Tickets / Nuevos Tickets ***/
    public function new_tickets(){
    	$tickets = Ticket::where('status', '=', 0)
    					->orderBy('created_at', 'ASC')
    					->get();

    	return view('admins.tickets.newTickets')->with(compact('tickets'));
    }

    /*** Admin / Tickets / Tickets Abiertos ***/
    public function open_tickets(){
    	$tickets = Ticket::where('status', '=', 1)
    					->orWhere('status', '=', 2)
    					->orderBy('created_at', 'ASC')
    					->get();
    	return view('admins.tickets.openTickets')->with(compact('tickets'));
    }

    /*** Admin / Tickets / Tickets Cerrados ***/
    public function closed_tickets(){
    	$tickets = Ticket::where('status', '=', 3)
    					->orderBy('resolved_at', 'DESC')
    					->get();

    	return view('admins.tickets.closedTickets')->with(compact('tickets'));
    }

    /*** Admin / Tickets / Listados / Ver Detalles de un Ticket ***/
    /*** Instructor / Tickets / Ver Detalles de un Ticket ***/
    public function show($id){
    	$ticket = Ticket::find($id);

    	$respuestas = TicketTracking::where('ticket_id', '=', $id)
    					->orderBy('reply_order', 'ASC')
    					->get();

        if (Auth::user()->role_id == 1){
            return view('students.tickets.show')->with(compact('ticket', 'respuestas'));
        }else if (Auth::user()->role_id == 2){
            return view('instructors.tickets.show')->with(compact('ticket', 'respuestas'));
        }else if (Auth::user()->role_id == 3){
            return view('admins.tickets.show')->with(compact('ticket', 'respuestas'));
        }
    }

    /*** Admin / Tickets / Listados / Ver Detalles de un Ticket / Responder ***/
    /*** Instructor / Tickets / Ver Detalles de un Ticket / Responder ***/
    public function reply(Request $request){
    	$ultRespuesta = TicketTracking::where('ticket_id', '=', $request->ticket_id)
	    					->count();
		
		$respuesta = new TicketTracking($request->all());
    	$respuesta->user_id = Auth::user()->id;
    	$respuesta->reply_order = $ultRespuesta+1;
    	$respuesta->save();

        if ($request->reply_type == 'Soporte'){
            DB::table('tickets')
                ->where('id', '=', $request->ticket_id)
                ->update(['status' => 1,
                          'updated_at' => date('Y-m-d H:i:s')]);

            return redirect('admins/tickets/show/'.$request->ticket_id)->with('msj-exitoso', 'La respuesta del ticket ha sido enviada con éxito.');
        }else{
            DB::table('tickets')
                ->where('id', '=', $request->ticket_id)
                ->update(['status' => 2,
                          'updated_at' => date('Y-m-d H:i:s')]);

            if (Auth::user()->role_id == 1){
                return redirect('students/tickets/show/'.$request->ticket_id)->with('msj-exitoso', 'La respuesta del ticket ha sido enviada con éxito.');
            }else if (Auth::user()->role_id == 2){
                return redirect('instructors/tickets/show/'.$request->ticket_id)->with('msj-exitoso', 'La respuesta del ticket ha sido enviada con éxito.');
            }
        }
    }

    /*** Admin / Tickets / Gestionar Categorías ***/
    public function categories(){
    	$categoriasTickets = TicketCategory::orderBy('title', 'ASC')
    					->withCount('tickets')
    					->get();

    	return view('admins.tickets.categories')->with(compact('categoriasTickets'));
    }

     /*** Admin / Tickets / Gestionar Categorías / Eliminar ***/
    public function delete_category($id){
    	TicketCategory::destroy($id);

    	return redirect('admins/tickets/categories')->with('msj-exitoso', 'La categoría ha sido eliminada con éxito.');
    }

    /*** Admin / Tickets / Gestionar Categorías / Crear Nueva ***/
    public function create_category(Request $request){
    	$categoria = new TicketCategory($request->all());
    	$categoria->save();

    	return redirect('admins/tickets/categories')->with('msj-exitoso', 'La categoría ha sido creada con éxito.');
    }
}
