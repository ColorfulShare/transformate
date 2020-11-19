@extends('layouts.admin')

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-video icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Cursos por Instructor</h4>
        </div>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif               
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div uk-grid> 
                <div class="uk-width-expand@m"> 
                    {{ $totalCursos }} Cursos                         
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
                        @foreach ($cursos as $curso)
                            <tr>                                 
                                <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/courses/'.$curso->cover) }}" width="50">
                                </td>                                 
                                <td class="uk-text-center">{{ $curso->title }}</td>
                                <td class="uk-text-center">{{ $curso->category->title }} ({{ $curso->subcategory->title }})</td> 
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$curso->created_at -5 Hours")) }}</td> 
                                <td class="uk-text-center">
                                    @if ($curso->status == 0)
                                        <label class="uk-label uk-label-danger">Sin Publicar</label>
                                    @elseif ($curso->status == 1)
                                        <label class="uk-label uk-label-warning">Esperando Publicación</label>
                                    @elseif ($curso->status == 2)
                                        <label class="uk-label uk-label-success">Publicado</label>
                                    @endif
                                </td> 
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$curso->published_at -5 Hours")) }}</td> 
                                <td class="uk-flex-inline uk-flex-middle"> 
                                    <a href="{{ route('admins.courses.resume', [$curso->slug, $curso->id]) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" uk-tooltip="Ver Detalles"></a>
                                    <a href="{{ route('admins.courses.show', [$curso->slug, $curso->id]) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: pencil;" uk-tooltip="Editar"></a>           
                                </td>                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>    
            {{ $cursos->links() }}               
        </div>                
    </div>
@endsection