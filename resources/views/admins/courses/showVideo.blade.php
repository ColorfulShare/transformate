@extends('layouts.admin')

@push('scripts')
    <script>
         $(function(){
            setTimeout(function(){  
                var v = document.getElementById("player");
                var duration = (v.duration/60);
                
                var link = $("#player").attr('data-route');
                var route = link+"/"+duration;
                $.ajax({
                    url:route,
                    type:'GET',
                    success:function(ans){
                        $("#alert-success").css('display', 'block');
                    }
                });
            }, 3000);
           
            /*var link = $("#player").attr('data-route');
            var route = link+"/"+duration;
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    //alert("Listo");
                }
            });*/
        });
    </script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-video icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Video de Lección {{ $leccion->title }}</h4>                          
        </div>  

        <div class="uk-alert-success" uk-alert id="alert-success" style="display: none;">
            <strong>La duración del video ha sido capturada con éxito.</strong>
        </div> 

        <div class="uk-text-center" style="padding: 15px 0;">
            <a class="uk-button uk-button-primary" href="{{ route('admins.courses.lessons', [$leccion->module->course->slug, $leccion->module->course_id]) }}">Regresar a Lecciones</a>
        </div>           
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <video src="{{ $leccion->video }}" @if (!is_null($leccion->module->course->preview_cover)) poster="{{ asset('uploads/images/courses/preview_covers/'.$leccion->module->course->preview_cover) }}" @endif controls controlslist="nodownload" data-route="{{ route('admins.courses.lessons.load-video-duration', $leccion->id) }}" id="player"></video> 
            </div>                 
        </div>                
    </div>
@endsection