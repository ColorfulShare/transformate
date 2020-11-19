<div id="modalEditar" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h4>Editar mi Valoración</h4>
        </div>
        <form action="{{ route('students.ratings.update') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="rating_id">
	        <div class="uk-modal-body">
	            <div class="uk-margin">
	                <div class="uk-width-1-1">
	                    <input class="uk-input" type="text" name="title" id="title" required>
	                </div>
	            </div>
	            <div class="uk-margin">
	                <div class="uk-width-1-1">
	                    <textarea class="uk-textarea" rows="5" name="comment" id="comment" required></textarea>
	                </div>
	            </div>
	            <p class="clasificacion uk-text-center">
				    <input id="radio1" type="radio" name="points" value="5"><label for="radio1"><i class="fa fa-star"></i></label>
				    <input id="radio2" type="radio" name="points" value="4"><label for="radio2"><i class="fa fa-star"></i></label>
				    <input id="radio3" type="radio" name="points" value="3"><label for="radio3"><i class="fa fa-star"></i></label>
				    <input id="radio4" type="radio" name="points" value="2"><label for="radio4"><i class="fa fa-star"></i></label>
				    <input id="radio5" type="radio" name="points" value="1"><label for="radio5"><i class="fa fa-star"></i></label>
				  </p>
	        </div>
	        <div class="uk-modal-footer uk-text-right">
	            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
	            <input type="submit" class="uk-button uk-button-primary" value="Guardar Cambios" />
	        </div>
	    </form>
    </div>
</div>