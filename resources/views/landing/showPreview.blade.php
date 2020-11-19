<script>
    $(function(){    
        $('.video-responsive').bind('contextmenu',function() { return false; });

        $('.close-trailer').on('click', function(){
            var vid = document.getElementById("video-trailer");
            vid.pause();
        });
    });
</script>

<button class="uk-modal-close-default close-trailer" type="button" uk-close></button>                       
<div class="modal-header-trailer header-ligth-trailer" id="header-trailer">
    <b class="uk-text-medium color-ligth-trailer" id="header-text-trailer">  Resumen</b>
</div>
@if ($type == 'curso')
    <div class="video-responsive">
    	<video src="{{ $preview->preview }}" @if (!is_null($preview->preview_cover)) poster="{{ asset('uploads/images/courses/preview_covers/'.$preview->preview_cover) }}" @endif controls controlslist="nodownload" muted autoplay id="video-trailer"></video>  
    </div>   
@elseif ($type == 'certificacion')
    <div class="video-responsive">
    	<video src="{{ $preview->preview }}" type="video/mp4" @if (!is_null($preview->preview_cover)) poster="{{ asset('uploads/images/certifications/preview_covers/'.$preview->preview_cover) }}" @endif controls controlslist="nodownload" muted autoplay id="video-trailer"></video> 
    </div>
@else
    <div class="video-responsive" style="background: url(https://transformatepro.com/uploads/images/podcasts/{{ $preview->cover }}); background-repeat: no-repeat; background-position: center center;">
        <audio src="{{ $preview->preview }}" type="audio/mp3" controls controlslist="nodownload" style="height: 250px;" id="video-trailer"></audio> 
    </div>
@endif
<div class="uk-modal-body modal-body-trailer background-ligth-trailer" id="body-trailer"> 
    <div class="title-trailer color-ligth-trailer" id="title-trailer">{{ $preview->title }}</div>
    <div class="instructor-trailer color-ligth-trailer" id="instructor-trailer">{{ $preview->user->names }} {{ $preview->user->last_names }}</div>
    <div class="subtitle-trailer color-ligth-trailer" id="subtitle-trailer">{{ $preview->subtitle }}</div>

    <div class="uk-child-width-1-1" uk-grid style="padding-top: 30px;">
        @if (Auth::guest())
            <div class="uk-text-center">
                @if ($type == 'curso')
                    <button class="buttons-trailer btn-course-trailer"><a class="no-link" href="{{ route('landing.shopping-cart.store', [$preview->id, 'curso']) }}"><i class="fas fa-cart-plus"></i>  Comprar T-Curso COP${{ number_format($preview->price, 0, ',', '.') }}</a></button>
                @elseif ($type == 'podcast')
                    <button class="buttons-trailer btn-course-trailer"><a class="no-link" href="{{ route('landing.shopping-cart.store', [$preview->id, 'podcast']) }}"><i class="fas fa-cart-plus"></i>  Comprar T-Book COP${{ number_format($preview->price, 0, ',', '.') }}</a></button>
                @endif
            </div>
        @else
            @if (Auth::user()->role_id == 1)
                <div class="uk-text-center">
                    @if ($type == 'curso')
                        <button class="buttons-trailer btn-course-trailer"><a class="no-link" href="{{ route('students.shopping-cart.store', [$preview->id, 'curso']) }}"><i class="fas fa-cart-plus"></i>  Comprar T-Curso COP${{ number_format($preview->price, 0, ',', '.') }}</a></button>
                    @elseif ($type == 'podcast')
                        <button class="buttons-trailer btn-course-trailer"><a class="no-link" href="{{ route('students.shopping-cart.store', [$preview->id, 'podcast']) }}"><i class="fas fa-cart-plus"></i>  Comprar T-Book COP${{ number_format($preview->price, 0, ',', '.') }}</a></button>
                    @endif
                </div>
            @endif
        @endif    
        <div class="uk-text-center" style="margin-top: 10px; margin-bottom: 20px;">
            @if ($type == 'curso')
                <button class="buttons-trailer btn-show-trailer"><a class="no-link" href="{{ route('landing.courses.show', [$preview->slug, $preview->id]) }}"><i class="fa fa-plus"></i>  Más Información</a></button>
            @elseif ($type == 'podcast')
                <button class="buttons-trailer btn-show-trailer"><a class="no-link" href="{{ route('landing.podcasts.show', [$preview->slug, $preview->id]) }}"><i class="fa fa-plus"></i>  Más Información</a></button>
            @endif
        </div>           
    </div>
</div>  