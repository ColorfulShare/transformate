@extends('layouts.student2')

@section('content')
    <div class="uk-container uk-margin-medium-bottom padding-top">
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-shopping-cart icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> T-Gift Comprados </h4>                   
        </div>
        
        <div class="uk-background-default"> 
            <div class="uk-overflow-auto">
                <table class="uk-table uk-table-hover uk-table-divider">
                    <thead>
                        <tr>
                            <th class="uk-text-center uk-width-medium">Fecha</th>
                            <th class="uk-text-center uk-table-medium">Contenido</th> 
                            <th class="uk-text-center uk-table-medium">Descripción</th> 
                            <th class="uk-text-center uk-width-small">Código T-Gift</th>
                            <th class="uk-text-center uk-width-medium">Beneficiario</th> 
                            <th class="uk-text-center uk-width-medium">Fecha de Aplicación</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($regalosComprados as $regalo)
                            <tr>                             
                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$regalo->created_at -5 Hours")) }}</td> 
                                <td class="uk-text-center">
                                    @if (!is_null($regalo->course_id))
                                        <img  src="{{ asset('uploads/images/courses/'.$regalo->course->cover) }}" style="width: 50%;">
                                    @elseif (!is_null($regalo->certification_id))
                                        <img  src="{{ asset('uploads/images/certifications/'.$regalo->certification->cover) }}" style="width: 50%;">
                                    @elseif (!is_null($regalo->podcast_id))
                                        <img src="{{ asset('uploads/images/podcasts/'.$regalo->podcast->cover) }}" style="width: 50%;">
                                    @elseif (!is_null($regalo->product_id))
                                        <img src="{{ asset('uploads/images/products/'.$regalo->market_product->cover) }}" style="width: 50%;">
                                    @elseif (!is_null($regalo->membership_id))
                                        <img src="{{ asset('uploads/images/memberships/'.$regalo->membership->image) }}" style="width: 50%;">
                                    @endif
                                </td> 
                                <td class="uk-text-center">
                                    @if (!is_null($regalo->course_id)) 
                                        {{ $regalo->course->title }} (T-Course)
                                    @elseif (!is_null($regalo->podcast_id))
                                        {{ $regalo->podcast->title }} (T-Book)
                                    @endif
                                </td> 
                                <td class="uk-text-center">{{ $regalo->code }}</td> 
                                <td class="uk-text-center">@if (!is_null($regalo->user_id)) {{ $regalo->user->names }} {{ $regalo->user->last_names }} @else Sin Aplicar @endif</td>       
                                <td class="uk-text-center">@if (!is_null($regalo->user_id)) {{ date('d-m-Y', strtotime("$regalo->applied_at -5 Hours")) }} @else - @endif</td>                     
                            </tr>   
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-shopping-cart icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> T-Gift Obsequiados </h4>                   
        </div>
        
        <div class="uk-background-default"> 
            <div class="uk-overflow-auto">
                <table class="uk-table uk-table-hover uk-table-divider">
                    <thead>
                        <tr>
                            <th class="uk-text-center uk-width-medium">Fecha</th>
                            <th class="uk-text-center uk-table-medium">Contenido</th> 
                            <th class="uk-text-center uk-table-medium">Descripción</th> 
                            <th class="uk-text-center uk-width-medium">Patrocinador</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($regalosObtenidos as $regalo2)
                            <tr>                             
                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$regalo2->applied_at -5 Hours")) }}</td> 
                                <td class="uk-text-center">
                                    @if (!is_null($regalo2->course_id))
                                        <img src="{{ asset('uploads/images/courses/'.$regalo2->course->cover) }}" style="width: 50%;">
                                    @elseif (!is_null($regalo2->podcast_id))
                                        <img src="{{ asset('uploads/images/podcasts/'.$regalo2->podcast->cover) }}" style="width: 50%;">
                                    @elseif (!is_null($regalo2->event_id))
                                        <img src="{{ asset('uploads/events/images/'.$regalo2->event->image) }}" style="width: 50%;">
                                    @endif
                                </td> 
                                <td class="uk-text-center">
                                    @if (!is_null($regalo2->course_id)) 
                                        <a href="{{ route('students.courses.resume', [$regalo2->course->slug, $regalo2->course_id]) }}">{{ $regalo2->course->title }} (T-Course)</a>
                                    @elseif (!is_null($regalo2->podcast_id))
                                    <a href="{{ route('students.podcasts.resume', [$regalo2->podcast->slug, $regalo2->podcast_id]) }}">{{ $regalo2->podcast->title }} (T-Book)</a>
                                    @elseif (!is_null($regalo2->event_id))
                                        <a href="{{ route('landing.events.show', [$regalo2->event->slug, $regalo2->event_id]) }}">{{ $regalo2->event->title }} (T-Event)</a>
                                    @endif
                                </td> 
                                <td class="uk-text-center">
                                    @if (!is_null($regalo2->buyer_id))
                                        {{ $regalo2->buyer->names }} {{ $regalo2->buyer->last_names }}
                                    @else
                                        Regalo Administrativo
                                    @endif
                                </td>       
                            </tr>   
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection