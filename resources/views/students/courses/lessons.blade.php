@extends('layouts.lessons')

@push('styles')
    <link rel="stylesheet" href="{{  asset('template/css/lessons.css') }}">  
@endpush

@push('scripts')
    <script>
        function chequear_progreso(){
            if (document.getElementById("sin-video").value == '0'){
                var leccion = document.getElementById("lesson_id").value;
                var video = document.getElementById("current-video"); 
                var inicio = document.getElementById("start_time").value;
                var check_fin = document.getElementById("finished").value;
                $("#icon_lesson_"+leccion).show();
                video.currentTime = inicio;
                if (check_fin == 0){
                    video.onpause = (event) => {
                        updateProgress(leccion, Math.round(video.currentTime), 'pause');
                    };
                    /*video.onended = (event) => {
                        updateProgress(leccion, Math.round(video.currentTime), 'end');
                    };*/
                    setInterval(function(){
                        updateProgress(leccion, Math.round(video.currentTime), 'interval');
                    },20000);
                }  
            }
        }

        $(function(){    
            $('#current-video').bind('contextmenu',function() { return false; });

            if ($("#duration").val() == 0){
                setTimeout(function(){  
                    var v = document.getElementById("current-video");
                    var duration = (v.duration/60);
                    
                    var link = $("#current-video").attr('data-route');
                    var route = link+"/"+duration;
                    $.ajax({
                        url:route,
                        type:'GET',
                        success:function(ans){
                            //$("#alert-success").css('display', 'block');
                        }
                    });
                }, 3000);
            }
           

            chequear_progreso();       
            
            $('.video').on('click',function(){
                var id = $(this).data('lessonid');
                var a = document.getElementById("link-lesson-"+id);
                a.click();
            });
            var leccion = document.getElementById("lesson_id").value;
            var check_fin = document.getElementById("finished").value;
            var url = {{ $www }};
            if (url == 1){
                var route = "https://www.transformatepro.com/ajax/actualizar-barra-progreso/"+leccion;
            }else{
                var route = "https://transformatepro.com/ajax/actualizar-barra-progreso/"+leccion;
            }
            
            //var route = "http://localhost:8000/ajax/actualizar-barra-progreso/"+leccion;
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){   
                    console.log(ans);                   
                }
            });
        });
        
        function updateProgress($leccion, $progreso, $tipo){
            var url = {{ $www }};
            if (url == 1){
                var route = "https://www.transformatepro.com/ajax/actualizar-progreso-leccion/"+$leccion+"/"+$progreso+"/"+$tipo;
            }else{
                var route = "https://transformatepro.com/ajax/actualizar-progreso-leccion/"+$leccion+"/"+$progreso+"/"+$tipo;
            }
            //var route = "http://localhost:8000/ajax/actualizar-progreso-leccion/"+$leccion+"/"+$progreso+"/"+$tipo;
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){                      
                }
            });
        }
    </script>
@endpush

@section('content')
    @if (Session::has('msj-exitoso'))
        <div id="note">
            {{ Session::get('msj-exitoso') }}
            <a class="uk-margin-medium-right uk-margin-remove-bottom uk-position-relative uk-icon-button uk-align-right  uk-margin-small-top" uk-toggle="target: #note; cls:uk-hidden"> <i class="fas fa-times  uk-position-center"></i> </a>
        </div>
    @endif

	<div class="uk-width-3-4@m uk-margin-auto">
        <header class="tm-course-content-header uk-background-grey"> 
            <a href="{{ route('students.courses.resume', [$curso->slug, $curso->id]) }}" class="back-to-dhashboard uk-margin-large-left" uk-tooltip="title: Volver al Resumen del Curso  ; delay: 200 ; pos: bottom-left ;animation:uk-animation-scale-up ; offset:20"> Volver al T-Course</a> 
        </header>
        <!--Course-side icon make Hidden sidebar -->
        <i class="fas fa-angle-right icon-large uk-float-right tm-side-course-icon  uk-visible@m" uk-toggle="target: #course-fliud; cls: tm-course-fliud" uk-tooltip="title: Hide sidebar  ; delay: 200 ; pos: bottom-right ;animation:uk-animation-scale-up ; offset:20"></i>
        <!--Course-side active icon -->
        <i class="fas fa-angle-left icon-large uk-float-right tm-side-course-active-icon uk-visible@m" uk-toggle="target: #course-fliud; cls: tm-course-fliud" uk-tooltip="title: Open sidebar  ; delay: 200 ; pos: bottom-right ;animation:uk-animation-scale-up ; offset:20"></i>
		<div class="uk-padding animation: uk-animation-slide-left-medium, uk-animation-slide-right-medium"> 
            <div class="video-responsive">
                @if (!is_null($leccionEscogida->video))    
                    <input type="hidden" id="sin-video" value="0">
                    <video src="{{ $leccionEscogida->video }}" controls controlslist="nodownload" style="width: auto; height: auto;" id="current-video" data-route="{{ route('students.courses.lessons.load-video-duration', $leccionEscogida->id) }}"></video>
                @else
                    <input type="hidden" id="sin-video" value="1">
                    Disculpe.. esta lección no posee video disponible.
                @endif
                <input type="hidden" id="lesson_id" value="{{ $leccionEscogida->id }}">
                <input type="hidden" id="start_time" value="{{ $leccionEscogida->start_time }}">
                <input type="hidden" id="finished" value="{{ $leccionEscogida->finished }}">
                <input type="hidden" id="duration" value="{{ $leccionEscogida->duration }}">
            </div>      
		</div>
    </div>

    <!-- Sidebar-->                 
    <div class="uk-width-1-4@m uk-offcanvas tm-filters uk-background-default tm-side-course uk-animation-slide-right-medium" id="filters" uk-offcanvas="overlay: true; container: false; flip: true">
        <div class="uk-offcanvas-bar uk-padding-remove uk-preserve-color">
            <div class="course-content">
                Contenido del T-Course
            </div>
            <div class="modules-content">
                <ul uk-accordion="multiple: true" class="lessons-accordion">
                    @foreach ($curso->modules as $modulo)
                        <li class="title-li uk-padding-small @if (!is_null($leccionEscogida)) @if ($leccionEscogida->module_id == $modulo->id) uk-open @endif @endif" @if ($modulo->priority_order == 1) style="margin-top: 20px;" @endif>
                            <a class="uk-accordion-title accordion-lesson-title" href="#" style="color: #505763;">
                                Módulo {{ $modulo->priority_order }}: {{ $modulo->title }}<br>
                                <span class="font-text-xs completed-lessons">{{ $modulo->completed_lessons }} / {{ $modulo->lessons_count }} <i class="fa fa-check"></i></span> {{--  |<span class="font-text-xs duration-module">7 min</span>--}}
                            </a> 
                            <div class="uk-accordion-content accordion-lesson-content">
                                <ul class="lessons-list">
                                    @foreach ($modulo->lessons as $leccion)
                                        <li aria-current="true" class="lesson video @if ($leccion->finished == 0) current @endif" data-lessonId="{{ $leccion->id }}">
                                            <a href="{{ route('students.courses.lessons', [$curso->slug, $curso->id, $leccion->id]) }}" id="link-lesson-{{ $leccion->id }}"></a>
                                            <div class="item-link item-link-default demo-check" tabindex="0" role="link">
                                                <input type="checkbox" @if ($leccion->finished == 1) checked @endif>
                                                <label style="padding-top: 12px;"><span></span></label>
                                                <div class="lesson-info">
                                                    <div class="lesson-title">
                                                        <span width="0">
                                                            <span>
                                                                <span>{{ $leccion->priority_order }}. {{ $leccion->title }}
                                                                </span> 
                                                                <span class="icon-lesson" id="icon_lesson_{{ $leccion->id }}" style="display: none;">
                                                                    <i class="fa fa-play-circle"></i>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div class="lesson-resources">
                                                        <div class="lesson-duration">
                                                            <span class="lesson-duration-icon">
                                                                <span class="fa fa-play-circle icon-play-circle"></span>
                                                            </span>
                                                            <span>
                                                                @if ($leccion->duration > 0) 
                                                                   {{ $leccion->minutes }}m
                                                                @else
                                                                   Sin Capturar
                                                                @endif
                                                            </span>
                                                        </div>
                                                        @if ($leccion->resource_files_count > 0)
                                                            <div class="dropdown-resources">
                                                                <button class="resources-btn" type="button">
                                                                    <span class="fa fa-folder"></span>
                                                                    <span class="dropdown-resources-label">Recursos <i class="fa fa-angle-down"></i></span>
                                                                </button>
                                                                <div uk-dropdown="mode: click" class="resources-list">
                                                                    <ul class="uk-list">
                                                                        @foreach ($leccion->resource_files as $recurso)
                                                                            <li><a href="{{ $recurso->link }}" target="_blank">{{ $recurso->filename }}</a></li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    @if ($modulo->tests_count > 0)
                                        @foreach($modulo->tests as $test) 
                                            <li aria-current="true" class="lesson @if ($test->presented == 0) current @endif">
                                                <div class="item-link item-link-default demo-check" tabindex="0" role="link">
                                                    <input type="checkbox" @if ($test->presented == 1) checked @endif>
                                                    <label style="padding-top: 12px;"><span></span></label>
                                                    <div class="lesson-info">
                                                        <div class="lesson-title">
                                                            <span width="0">
                                                                <span>
                                                                    <span>
                                                                        @if ($test->score < 50)
                                                                            <a href="{{ route('students.tests.submit', [$test->slug, $test->id]) }}">{{ $test->title }}</a>
                                                                        @else
                                                                            <a href="{{ route('students.tests.show-score', [$test->slug, $test->intent_id]) }}">{{ $test->title }}</a>
                                                                        @endif 
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </div>
                                                        <div class="lesson-resources">
                                                            <div class="lesson-duration">
                                                                <span class="lesson-duration-icon">
                                                                    <span class="@if ($test->score >= 50) far fa-thumbs-up @else far fa-thumbs-down @endif icon-play-circle"></span>
                                                                </span>
                                                                <span>{{ $test->score }} / 100</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection