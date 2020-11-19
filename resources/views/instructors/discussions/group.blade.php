@extends('layouts.instructor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/discussions.css') }}">
@endpush

@section('content')
    <div class="discussions">
        <div class="uk-container">
            <h2><i class="fa fa-comments"></i> Foro</h2>
            <ul class="forums-list">
                @if ($cantDiscusiones > 0)
                    @foreach ($discusiones as $discusion)
                        <li class="paper">
                            <div class="paper__body paper__body--no-padded-top paper__body--no-padded-bottom">
                                <div class="uk-grid">
                                    <div class="uk-width-expand">
                                        <div class="topic-badge-wrapper">
                                            <div class="topic-badge topic-badge--m">
                                                <a href="#">
                                                    <span class="avatar avatar--s">
                                                        <img width="32" height="32" title="{{ $discusion->user->names }} {{ $discusion->user->last_names }}" src="{{ asset('uploads/images/users/'.$discusion->user->avatar) }}">
                                                    </span>
                                                </a>
                                                <h3 class="title">
                                                    <a href="{{ route('instructors.discussions.show', [$discusion->slug, $discusion->id]) }}">{{ $discusion->title }}</a>
                                                </h3>
                                                <div class="metadata">
                                                    publicado el {{ date('d-m-Y H:i A', strtotime("$discusion->created_at -5 Hours")) }} por {{ $discusion->user->names }} {{ $discusion->user->last_names }}<br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-auto">
                                        <p class="topic-count">
                                            <a href="{{ route('instructors.discussions.show', [$discusion->slug, $discusion->id]) }}"></i>
                                                <i class="fa fa-comment i-space-right"></i>{{ $discusion->comments_count }} comentarios
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @else 
                    <div class="paper">
                        @if ($tipo == 'course')
                            <h3>El T-Course no posee ningun foro aún...</h3>
                        @elseif ($tipo == 'certification')
                            <h3>La T-Mentoring no posee ningun foro aún...</h3>
                        @else 
                            <h3>El T-Book no posee ningun foro aún...</h3>
                        @endif
                    </div>
                @endif
            </ul>   
            {{ $discusiones->links() }}<br>       
        </div>
    </div>
@endsection