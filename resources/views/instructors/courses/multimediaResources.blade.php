@if (!is_null($curso->cover))
	<tr>
		<td class="uk-text-center uk-table-shrink">{{ $curso->cover_name }}</td>
		<td class="uk-text-center uk-table-shrink">
			<i class="fa fa-file-image img-icon"></i>
		</td>
		<td class="uk-text-center uk-table-shrink">Portada</td>
			<td class="uk-text-right">
			<div uk-lightbox>
				<a href="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
			</div>
		</td>
		<td class="uk-text-left">
			<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_cover"></a>
		</td>
	</tr>
@endif
@if (!is_null($curso->preview))
	<tr>
		<td class="uk-text-center uk-table-expand">{{ $curso->preview_name }}</td>
		<td class="uk-text-center uk-table-shrink">
			<i class="fa fa-file-video img-icon"></i>
		</td>
		<td class="uk-text-center uk-table-shrink">Video Resumen</td>
		<td class="uk-text-right">
			<div uk-lightbox>
				<a href="{{ $curso->preview }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
			</div>
		</td>
		<td class="uk-text-left">
			<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_preview"></a>
		</td>
	</tr>
@endif
@if (!is_null($curso->preview_cover))
	<tr>
		<td class="uk-text-center uk-table-expand">{{ $curso->preview_cover_name }}</td>
		<td class="uk-text-center uk-table-shrink">
			<i class="fa fa-file-image img-icon"></i>
		</td>
		<td class="uk-text-center uk-table-shrink">Portada del Video Resumen</td>
		<td class="uk-text-right">
			<div uk-lightbox>
				<a href="{{ asset('uploads/images/courses/preview_covers/'.$curso->preview_cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
			</div>
		</td>
		<td class="uk-text-left">
			<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_preview_cover"></a>
		</td>
	</tr>
@endif