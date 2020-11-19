<button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>     
<div class="uk-modal-header"> 
    <h4>Datos de Cobro de {{ $usuario->names }} {{ $usuario->last_names }}</h4> 
</div>                    
<div class="uk-modal-body">
    @if (is_null($usuario->charging_method))
        El mentor no posee ningún método de cobro seleccionado aún.
    @endif 

    <div @if ($usuario->charging_method == 'Paypal') style="display: block;" @else style="display: none;" @endif>
        <div class="uk-grid">
            <div class="uk-width-1-1">
               Correo Electrónico Paypal: <input class="uk-input" type="email" name="paypal_email" value="{{ $usuario->paypal_email }}">
            </div>
        </div>
    </div>
                                            
    <div @if ($usuario->charging_method == 'Transferencia Bancaria') style="display: block;" @else style="display: none;" @endif>
        <div class="uk-grid">
            <div class="uk-width-1-1">
                Banco: <input class="uk-input" type="text" value="{{ $usuario->bank }}">
            </div>
            <div class="uk-width-1-2">
                Razón Social: <input class="uk-input" type="text" value="{{ $usuario->business_name }}">
            </div>
            <div class="uk-width-1-2">
                N° Identificación: <input class="uk-input" type="text" value="{{ $usuario->identification }}">
            </div>
            <div class="uk-width-1-2">
                Tipo de Cuenta: <input class="uk-input" type="text" value="{{ $usuario->account_type }}"> 
            </div>
            <div class="uk-width-1-2">
                N° de Cuenta: <input class="uk-input" type="text" value="{{ $usuario->account_number }}">
            </div>
        </div>
    </div>            
</div>                             
<div class="uk-modal-footer uk-text-right"> 
    <button class="uk-button uk-button-default uk-modal-close" type="button">Cerrar</button>  
</div>  