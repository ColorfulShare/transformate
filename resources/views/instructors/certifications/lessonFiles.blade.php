@php
	$cantRecursos = $leccion->resource_files->count();
@endphp
@if (!is_null($leccion->video))
	<tr>
		<td class="uk-text-center uk-table-expand">{{ $leccion->filename }}</td>
		<td class="uk-text-center uk-table-shrink">
			<i class="{{ $leccion->file_icon }} img-icon"></i>
		</td>
		<td class="uk-text-center uk-table-expand">{{ date('d-m-Y H:i A', strtotime("$leccion->updated_at -5 Hours")) }}</td>
		<td class="uk-text-center uk-table-shrink">.{{ $leccion->file_extension }}</td>
		<td class="uk-text-right">
			<div uk-lightbox>
				<a href="{{ $leccion->video }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
			</div>
		</td>
		<td class="uk-text-left">
			<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_lesson_{{ $leccion->id }}"></a>
		</td>
	</tr>
@else
	@if ($cantRecursos == 0)
		<tr>
			<td colspan="5" class="uk-text-center">La lección no posee ningún archivo actualmente...</td>
		</tr>
	@endif
@endif

@if ($cantRecursos > 0)
	@foreach ($leccion->resource_files as $recurso)
		<tr>
			<td class="uk-text-center uk-table-expand">{{ $recurso->filename }}</td>
			<td class="uk-text-center uk-table-shrink">
				<i class="{{ $recurso->file_icon }} img-icon"></i>
			</td>
			<td class="uk-text-center uk-table-expand">{{ date('d-m-Y H:i A', strtotime("$recurso->updated_at -5 Hours")) }}</td>
			<td class="uk-text-center uk-table-shrink">.{{ $recurso->file_extension }}</td>
			<td class="uk-text-right">
				@if ( ($recurso->file_extension == 'png') || ($recurso->file_extension == 'jpg') || ($recurso->file_extension == 'gif') || ($recurso->file_extension == 'svg') || ($recurso->file_extension == 'mp4') )
					<div uk-lightbox>
						<a href="{{ $recurso->link }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
					</div>
				@else
					<a href="{{ $recurso->link }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;" target="_blank"></a>
				@endif
			</td>
			<td class="uk-text-left">
				<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_resource_{{ $recurso->id }}"></a>
			</td>
		</tr>
	@endforeach
@endif