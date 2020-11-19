@extends('layouts.admin')

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-video icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Podcasts por Instructor</h4>
        </div>              
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div uk-grid> 
                <div class="uk-width-expand@m"> 
                    {{ $totalPodcasts }} Podcasts                         
                </div>                   
            </div> 

            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Cover</th> 
                            <th class="uk-text-center">Título</th>  
                            <th class="uk-text-center">Categoría</th>
                            <th class="uk-text-center">Fecha de Creación</th> 
                            <th class="uk-text-center">Estado</th>
                            <th class="uk-text-center">Fecha de Publicación</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($podcasts as $podcast)
                            <tr>                                 
                                <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" width="50">
                                </td>                                 
                                <td class="uk-text-center">{{ $podcast->title }}</td>
                                <td class="uk-text-center">{{ $podcast->category->title }} ({{ $podcast->subcategory->title }})</td> 
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$podcast->created_at -5 Hours")) }}</td> 
                                <td class="uk-text-center">
                                    @if ($podcast->status == 0)
                                        <label class="uk-label uk-label-danger">Sin Publicar</label>
                                    @elseif ($podcast->status == 1)
                                        <label class="uk-label uk-label-warning">Esperando Publicación</label>
                                    @elseif ($podcast->status == 2)
                                        <label class="uk-label uk-label-success">Publicado</label>
                                    @endif
                                </td> 
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$podcast->published_at -5 Hours")) }}</td> 
                                <td class="uk-flex-inline uk-flex-middle"> 
                                    <a href="{{ route('admins.podcasts.resume', [$podcast->slug, $podcast->id]) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" uk-tooltip="Ver Detalles"></a>
                                    <a href="{{ route('admins.podcasts.show', [$podcast->slug, $podcast->id]) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: pencil;" uk-tooltip="Editar"></a>           
                                </td>                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>    
            {{ $podcasts->links() }}               
        </div>                
    </div>
@endsection