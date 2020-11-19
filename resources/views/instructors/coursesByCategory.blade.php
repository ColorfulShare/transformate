@extends('layouts.student2')

@section('content')
    <div id="neg2">
        @if ($categoria->courses_count > 0)
            <div class="uk-clearfix boundary-align"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">Categoría: {{ $categoria->title }}</h2> 
                </div> 
            </div>     

            <div class="slider">
                <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider>
                    <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-4@m uk-grid">
                        @foreach ($categoria->courses as $curso)
                            <li>
                                <div class="uk-panel">
                                    <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}">
                                    <div class="overlay">
                                        <p class="tile3">
                                            <a href="{{ route('instructors.courses.show', [$curso->slug, $curso->id]) }}">{{ $curso->title }}</a>
                                        </p>
                                        <p class="tile2">Mentor: {{ $curso->user->names.' '.$curso->user->last_names }}</p>
                                        <p class="tile4">{!! $curso->review !!}</p>
                                        <p class="tile5"><span class="desc">{{ $curso->price*1.50 }} COP</span><br><span class="desc2">{{ $curso->price }} COP</span></p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <a class="uk-position-center-left" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                    <a class="uk-position-center-right" href="#" uk-slidenav-next uk-slider-item="next"></a>
                </div>
            </div>

            @foreach ($categoria->subcategories as $subcategoria)
                @if ($subcategoria->courses_count > 0)
                    <div class="uk-clearfix boundary-align"> 
                        <div class="uk-float-left section-heading none-border"> 
                            <h2 class="sca">Sub-Categoría: {{ $subcategoria->title }}</h2> 
                        </div> 
                    </div>     

                    <div class="slider">
                        <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider>
                            <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-4@m uk-grid">
                                @foreach ($subcategoria->courses as $cursoSub)
                                    <li>
                                        <div class="uk-panel">
                                            <img src="{{ asset('uploads/images/courses/'.$cursoSub->cover) }}">
                                            <div class="overlay">
                                                <p class="tile3">
                                                    <a href="{{ route('instructors.courses.show', [$cursoSub->slug, $cursoSub->id]) }}">{{ $cursoSub->title }}</a>
                                                </p>
                                                <p class="tile2">Mentor: {{ $cursoSub->user->names.' '.$cursoSub->user->last_names }}</p>
                                                <p class="tile4">{!! $cursoSub->review !!}</p>
                                                <p class="tile5"><span class="desc">{{ $cursoSub->price*1.50 }} COP</span><br><span class="desc2">{{ $cursoSub->price }} COP</span></p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="uk-clearfix boundary-align"> 
                <div class="section-heading none-border uk-text-center">
                    <h3>No existen cursos relacionados con ésta categoría aún...</h3>
                    <hr>
                </div>
            </div>
        @endif
    </div>
@endsection