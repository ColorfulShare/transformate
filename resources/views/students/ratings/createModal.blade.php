<div id="modalValorar" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h4>Valorar Contenido</h4>
        </div>
        <form action="{{ route('students.ratings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="course_id" id="course_id">
            <input type="hidden" name="certification_id" id="certification_id">
            <input type="hidden" name="podcast_id" id="podcast_id">
	        <div class="uk-modal-body">
	            <div class="uk-margin">
	                <div class="uk-width-1-1">
	                    <input class="uk-input" type="text" name="title" placeholder="Título de tu valoración" required>
	                </div>
	            </div>
	            <div class="uk-margin">
	                <div class="uk-width-1-1">
	                    <textarea class="uk-textarea" rows="5" name="comment" placeholder="¿Qué te pareció el curso?" required></textarea>
	                </div>
	            </div>
	            <p class="clasificacion uk-text-center">
				    <input id="radio1c" type="radio" name="points" value="5"><label for="radio1c"><i class="fa fa-star"></i></label>
				    <input id="radio2c" type="radio" name="points" value="4"><label for="radio2c"><i class="fa fa-star"></i></label>
				    <input id="radio3c" type="radio" name="points" value="3"><label for="radio3c"><i class="fa fa-star"></i></label>
				    <input id="radio4c" type="radio" name="points" value="2"><label for="radio4c"><i class="fa fa-star"></i></label>
				    <input id="radio5c" type="radio" name="points" value="1"><label for="radio5c"><i class="fa fa-star"></i></label>
				  </p>
	        </div>
	        <div class="uk-modal-footer uk-text-right">
	            <button class="uk-button uk-button-default uk-modal-close" type="button">Recordármelo Después</button>
	            <input type="submit" class="uk-button uk-button-primary" value="Publicar Valoración" />
	        </div>
	    </form>
    </div>
</div>