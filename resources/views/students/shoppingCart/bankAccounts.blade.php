<div id="cuentasBancarias" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h4>Listado de Cuentas Bancarias Disponibles</h4>
        </div>
	    <div class="uk-modal-body">
            <div class="uk-grid uk-grid-divider">
                @foreach ($cuentasDisponibles as $cuenta)
                    <div class="uk-width-1-3">
                        Banco: <b>{{ $cuenta->bank }}</b><br>
                        Razón Social: <b>{{ $cuenta->business_name }}</b><br>
                        DNI: <b>{{ $cuenta->identification }}</b><br>
                        N° Cuenta: <b>{{ $cuenta->account_number }}</b><br>
                        Tipo de Cuenta: <b>{{ $cuenta->account_type }}</b><hr>
                    </div>
                @endforeach
	        </div>
        </div>
	    <div class="uk-modal-footer uk-text-right">
	        <button class="uk-button uk-button-default uk-modal-close" type="button">Cerrar</button>
	    </div>
    </div>
</div>