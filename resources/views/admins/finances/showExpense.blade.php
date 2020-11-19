@extends('layouts.admin')

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold">
                <i class="fas fa-fa-money-check-alt uk-margin-small-right"></i> Detalles del Egreso #{{ $egreso->id }}
            </div>              
            <div class="uk-card-body"> 
                <div uk-grid> 
                    <div class="uk-width-1-3@m uk-text-center"> 
                        <img src="{{ asset('uploads/images/users/'.$egreso->user->avatar) }}" class="uk-border-circle" style="width: 200px;">
                    </div>                             
                    <div class="uk-width-2-3@m"> 
                        <div class="uk-grid-small" uk-grid> 
                            <div class="uk-width-1-1"> 
                                <div class="uk-form-label">
                                    Id de Cliente: <b>{{ $egreso->user_id }}</b><br>
                                    Cliente: <b>{{ $egreso->user->names }} {{ $egreso->user->last_names }}</b><br>
                                    Ubicación: <b>{{ $egreso->user->state }} ({{ $egreso->user->country }})</b><br>
                                    Fecha de Retiro: <b>{{ date('d-m-Y H:i A', strtotime("$egreso->created_at -5 Hours")) }}</b><br>
                                    Método de Pago: <b>{{ $egreso->payment_method }}</b><br>
                                    Id de Transacción: <b>{{ $egreso->transaction_id }}</b><br>
                                </div> 
                            </div>                  
                        </div>                            
                    </div>                             
                </div>                         
            </div>              
        </div> 

        <div class="uk-margin-medium"> 
            <div uk-grid> 
                @foreach ($comisiones as $comision)
                    <div class="uk-width-1-2"> 
                        <div class="uk-card-small uk-card-default"> 
                            <div class="uk-card-header">
                                Comisión #{{ $comision->id }}
                            </div>   
                            <div class="uk-card-body uk-padding-remove-top">  
                                Monto: <b>{{ $comision->amount }}$</b><br>
                                Id del Referido: <b>{{ $comision->user_id }}</b><br>
                                Referido: <b>{{ $comision->referred->names }} {{ $comision->referred->last_names }}</b><br>
                                Tipo: <b>{{ $comision->type }}</b>
                            </div>                             
                        </div>                             
                    </div>   
                @endforeach                
            </div> 
        </div> 
    </div>
@endsection