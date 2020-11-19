@if ($cantRecursos > 0)
	@foreach ($recursos as $recurso)
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
				<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="{{ $recurso->id }}"></a>
			</td>
		</tr>
	@endforeach
@endif