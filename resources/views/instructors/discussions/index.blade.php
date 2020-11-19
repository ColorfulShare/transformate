@extends('layouts.instructor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/discussions.css') }}">
@endpush

@section('content')
    <div class="discussions">
        <div class="uk-container">
            <ul class="uk-tab uk-margin-remove-top uk-background-default uk-margin-left uk-margin-right" uk-tab>
                <li aria-expanded="true" class="uk-active">
                    <a href="#"> T-COURSES</a>
                </li>                 
                <li>
                    <a href="#"> T-MENTORING</a>
                </li>                 
                <li>
                    <a href="#"> T-BOOKS</a>
                </li>             
            </ul>
            
            <ul class="uk-switcher uk-margin uk-padding-small uk-margin-auto">
                <!-- T-Courses -->
                <li class="uk-active">
                    <ul class="forums-list">
                        @foreach ($cursos as $curso)
                            <li class="paper">
                                <div class="paper__body paper__body--no-padded-top paper__body--no-padded-bottom">
                                    <div class="uk-grid">
                                        <div class="uk-width-expand">
                                            <div class="topic-badge-wrapper">
                                                <div class="topic-badge topic-badge--m">

                                                    <h3 class="title">
                                                        <a href="{{ route('instructors.discussions.group', ['course', $curso->slug, $curso->id]) }}">{{ $curso->title }}</a>
                                                    </h3>
                                                    <div class="metadata">
                                                        {{ $curso->subtitle }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-auto">
                                            <p class="topic-count">
                                                <a href="{{ route('instructors.discussions.group', ['course', $curso->slug, $curso->id]) }}">
                                                    <i class="fa fa-comment i-space-right"></i>{{ $curso->discussions_count }} foros.
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul> 
                </li>

                <!-- T-Mentoring -->
                <li >
                    <ul class="forums-list">
                        @foreach ($certificaciones as $certificacion)
                            <li class="paper">
                                <div class="paper__body paper__body--no-padded-top paper__body--no-padded-bottom">
                                    <div class="uk-grid">
                                        <div class="uk-width-expand">
                                            <div class="topic-badge-wrapper">
                                                <div class="topic-badge topic-badge--m">
                                                    <a href="#">
                                                       <span class="avatar avatar--s">
                                                            <img width="32" height="32" title="{{ $certificacion->title }}" src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}">
                                                        </span>
                                                    </a>
                                                    <h3 class="title">
                                                        <a href="{{ route('instructors.discussions.group', ['certification', $certificacion->slug, $certificacion->id]) }}">{{ $certificacion->title }}</a>
                                                    </h3>
                                                    <div class="metadata">
                                                        {{ $certificacion->subtitle }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-auto">
                                            <p class="topic-count">
                                                <a href="{{ route('instructors.discussions.group', ['certification', $certificacion->slug, $certificacion->id]) }}">
                                                    <i class="fa fa-comment i-space-right"></i>{{ $certificacion->discussions_count }} foros.
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul> 
                </li>

                <!-- T-Books -->
                <li>
                    <ul class="forums-list">
                        @foreach ($podcasts as $podcast)
                            <li class="paper">
                                <div class="paper__body paper__body--no-padded-top paper__body--no-padded-bottom">
                                    <div class="uk-grid">
                                        <div class="uk-width-expand">
                                            <div class="topic-badge-wrapper">
                                                <div class="topic-badge topic-badge--m">
                                                    <a href="#">
                                                       <span class="avatar avatar--s">
                                                            <img width="32" height="32" title="{{ $podcast->title }}" src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}">
                                                        </span>
                                                    </a>
                                                    <h3 class="title">
                                                        <a href="{{ route('instructors.discussions.group', ['podcast', $podcast->slug, $podcast->id]) }}">{{ $podcast->title }}</a>
                                                    </h3>
                                                    <div class="metadata">
                                                        {{ $podcast->subtitle }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-auto">
                                            <p class="topic-count">
                                                <a href="{{ route('instructors.discussions.group', ['podcast', $podcast->slug, $podcast->id]) }}">
                                                    <i class="fa fa-comment i-space-right"></i>{{ $podcast->discussions_count }} foros.
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul> 
                </li>
            </ul>
        </div>
    </div>
@endsection