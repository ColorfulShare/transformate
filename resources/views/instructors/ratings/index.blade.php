@extends('layouts.instructor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/discussions.css') }}">
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
@endpush

@section('content')
    <div class="discussions">
       <div class="uk-container">
            <div class="uk-grid">
            	{{-- Sección Izquierda --}}
                <div class="uk-width-2-3">
                    <div class="anchor" id="comments">
                        <div>
                            <h2><i class="fas fa-star"></i> Valoraciones</h2>
                        </div>
                        <ul class="comments-list" id="last_comment">
                            @if ($content->ratings_count > 0)
                                @foreach ($content->ratings as $rating)
                                    <li class="comment-unit">
                                        <div>
                                        	<div class="uk-grid uk-child-width-1-2">
	                                            <div class="comment-unit__head ">
                                                    @if ($rating->user_id != 0)
	                                                   <h4>{{ $rating->user->names }} {{ $rating->user->last_names }}</h4>
                                                    @else
                                                        <h4>{{ $rating->name }}</h4>
                                                    @endif
	                                                <div class="comment-unit__time">
	                                                    <a class="link-secondary" href="#">
	                                                        <i class="fa fa-comment"></i>
	                                                            &nbsp;<span class="dateposted">{{ date('d-m-Y H:i A', strtotime("$rating->created_at -5 Hours")) }}</span>
	                                                    </a>
	                                                </div>
	                                                <a href="#">
	                                                    <span class="avatar avatar--s">
                                                            @if ($rating->user_id != 0)
	                                                           <img width="32" height="32" src="{{ asset('uploads/images/users/'.$rating->user->avatar) }}">
                                                            @else
                                                                <img width="32" height="32" src="{{ asset('uploads/images/users/avatar.png') }}">
                                                            @endif
	                                                    </span>
	                                                </a>
	                                            </div>
	                                            <div class="uk-text-right">
	                                            	<p class="uk-light uk-text-bold uk-text-small"> 
											            @if ($rating->points >= 1) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
											            @if ($rating->points >= 2) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
											            @if ($rating->points >= 3) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
											            @if ($rating->points >= 4) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
											            @if ($rating->points >= 5) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
											        </p>
	                                            </div>
	                                        </div>
                                            <div class="comment-unit__body text-body-bigger">
                                                <div class="text-item">{!! $rating->comment !!}</div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                            	<article class="paper">
                            		No hay valoraciones aún...
                            	</article>
                            @endif
                        </ul>
                    </div>
                </div>
                {{-- Sección Derecha --}}
                <div class="uk-width-1-3 blak">
                    <aside>
                        <div class="block">
                        	@if ($content_type == 'course')
                            	<h5>T-COURSE</h5>
                            @elseif ($content_type == 'certification')
                            	<h5>T-MENTORING</h5>
                            @else
                            	<h5>T-BOOK</h5>
                            @endif

                            <div class="user-item user-badge user-badge--xl user-badge--vertical">
                                <span class="avatar avatar--xl">
                                	@if ($content_type == 'course')
		                            	<img src="{{ asset('uploads/images/courses/'.$content->cover) }}">
		                            @elseif ($content_type == 'certification')
		                            	<img width="96" height="96" src="{{ asset('uploads/images/certifications/'.$content->cover) }}">
		                            @else
		                            	<img width="96" height="96" src="{{ asset('uploads/images/podcasts/'.$content->cover) }}">
		                            @endif
                                </span><br>
                                <strong class="nickname">{{ $content->title }}</strong>
                                <div class="since">
                                	{{ $content->subtitle }}<br>
                                    <i class="fa fa-clock"></i> Creado el {{ date('d-m-Y H:i A', strtotime("$content->created_at -5 Hours")) }}
                                </div>
                                <div class="location">
                                    <p class="uk-light uk-text-bold uk-text-small"> 
							            @if ($content->promedio >= 1) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
							            @if ($content->promedio >= 2) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
							            @if ($content->promedio >= 3) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
							            @if ($content->promedio >= 4) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
							            @if ($content->promedio >= 5) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif<br>
							            <span class="uk-margin-small-right uk-text-warning"> {{ number_format($content->promedio, 1) }} ({{ $content->ratings_count }} Valoraciones) </span> <br> 
							        </p>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection