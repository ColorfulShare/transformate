<?php
namespace App\Console\Commands;
 
use Illuminate\Console\Command;
use DB; use Carbon\Carbon; use Mail;
 
class membershipExpirations extends Command{

  	protected $signature = 'membershipExpirations:notifications';
 
  	protected $description = 'Verifica la fecha de expiracion de la membresía de cada usuario para enviar notificaciones cuando esté próxima a vencerse.';

  	public function __construct(){
    	parent::__construct();
  	}
 
  	public function handle(){
  		\Log::info('Cron Activado membershipExpirations');
  		$notificacion = new App\Http\Controller\NotificationController();

      	$fechaActual = Carbon::now();
      	$fecha15 =  Carbon::now();
      	$fecha15->addDay(15);

  		$usuarios = DB::table('users')
                     	->select('id', 'names', 'last_names', 'membership_expiration')
                     	->where('membership_id', '<>', NULL)
   	               		->where('membership_expiration', '>=', $fechaActual)
   	               		->where('membership_purchases', '<=', $fecha15)
   	               		->orderBy('ID', 'ASC')
   	               		->get();

      	foreach ($usuarios as $usuario){
      		$fechaVencimiento = new Carbon($usuario->membership_expiration);
      		$dias = $fechaVencimiento->diffInDays($fechaActual); 
      		if ($dias <= 1){
      			$notificacion->store($usuario->id, 'Vencimiento de Suscripción', 'Su suscripción se vence hoy.', 'fas fa-gem', 'students/membership');
      		}else{
	         	$notificacion->store($usuario->id, 'Vencimiento de Suscripción', 'Su suscripción se vence en <b>'.$dias.' Días.</b>.', 'fas fa-gem', 'students/membership');
	        }
        }
   	}
}