<div class="">
    <div class="uk-background-default">
        @if (Auth::guest())
            <a href="{{ route('landing.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">
                <img src="{{ asset('uploads/images/courses/'.$primerCursoOriginal->cover) }}">
            </a>
        @else
            @if (Auth::user()->role_id == 1)
                <a href="{{ route('students.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">
                    <img src="{{ asset('uploads/images/courses/'.$primerCursoOriginal->cover) }}">
                </a>
            @elseif (Auth::user()->role_id == 2)
                <a href="{{ route('instructors.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">
                    <img src="{{ asset('uploads/images/courses/'.$primerCursoOriginal->cover) }}"> 
                </a>
            @else
                <a href="{{ route('admins.courses.resume', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">
                    <img src="{{ asset('uploads/images/courses/'.$primerCursoOriginal->cover) }}">
                </a>
            @endif
        @endif
    </div>
</div>
<div>
    <div class="">
        <p class="uk-h4 uk-text-white uk-text-left">
            @if (Auth::guest())
                <a href="{{ route('landing.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">{{ $primerCursoOriginal->title }}</a>
            @else
                @if (Auth::user()->role_id == 1)
                    <a href="{{ route('students.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">{{ $primerCursoOriginal->title }}</a>
                @elseif (Auth::user()->role_id == 2)
                    <a href="{{ route('instructors.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">{{ $primerCursoOriginal->title }}</a>
                @else
                    <a href="{{ route('admins.courses.resume', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">{{ $primerCursoOriginal->title }}</a>
                @endif
            @endif
        </p>
        <div class="uk-text-white uk-text-justify uk-margin-medium-bottom">
            {!!  strip_tags($primerCursoOriginal->review) !!}
        </div>
                      
        <div class="uk-child-width-1-2@s uk-text-center" uk-grid >
            <div>
                <p class="discount uk-text-left uk-text-white uk-text-bold">COP$ {{number_format($primerCursoOriginal->price*5, 0, ',', '.') }}</p>
                <p class="uk-text-bold price-course-original uk-text-left">COP$ {{ number_format($primerCursoOriginal->price, 0, ',', '.') }}</p>
            </div>
            @if (!is_null($primerCursoOriginal->preview))
                <a class="uk-button add-sc view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$primerCursoOriginal->id, 'curso']) }}"><i class="fas fa-play-circle"></i> Ver Trailer</a>
            @endif
        </div>
        <br>
        @if (Auth::guest())
            <a href="{{ route('landing.shopping-cart.store', [$primerCursoOriginal->id, 'curso']) }}"><button class="uk-button add-sc btn-lg btn-block"><i class="fa fa-shopping-cart"></i>  Añadir al carrito</button></a>
        @else
            @if (Auth::user()->role_id == 1)
                @php 
                    $check = in_array($primerCursoOriginal->id, $cursosAgregados);
                @endphp
                @if (!$check)
                    <a href="{{ route('students.shopping-cart.store', [$primerCursoOriginal->id, 'curso']) }}"><button class="uk-button add-sc btn-lg btn-block"><i class="fa fa-shopping-cart"></i>  Añadir al carrito</button></a>
                @else
                    <a href="{{ route('students.courses.resume', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}"><button class="uk-button add-sc btn-lg btn-block"><i class="fa fa-shopping-cart"></i>  Continuar Curso</button></a>
                @endif
            @endif
        @endif
    </div>
</div>