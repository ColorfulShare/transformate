@if (!is_null($certificacion->cover))
	<tr>
		<td class="uk-text-center uk-table-shrink">{{ $certificacion->cover_name }}</td>
		<td class="uk-text-center uk-table-shrink">
			<i class="fa fa-file-image img-icon"></i>
		</td>
		<td class="uk-text-center uk-table-shrink">Portada</td>
			<td class="uk-text-right">
			<div uk-lightbox>
				<a href="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
			</div>
		</td>
		<td class="uk-text-left">
			<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_cover"></a>
		</td>
	</tr>
@endif
@if (!is_null($certificacion->preview))
	<tr>
		<td class="uk-text-center uk-table-expand">{{ $certificacion->preview_name }}</td>
		<td class="uk-text-center uk-table-shrink">
			<i class="fa fa-file-video img-icon"></i>
		</td>
		<td class="uk-text-center uk-table-shrink">Video Resumen</td>
		<td class="uk-text-right">
			<div uk-lightbox>
				<a href="{{ $certificacion->preview }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
			</div>
		</td>
		<td class="uk-text-left">
			<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_preview"></a>
		</td>
	</tr>
@endif
@if (!is_null($certificacion->preview_cover))
	<tr>
		<td class="uk-text-center uk-table-expand">{{ $certificacion->preview_cover_name }}</td>
		<td class="uk-text-center uk-table-shrink">
			<i class="fa fa-file-image img-icon"></i>
		</td>
		<td class="uk-text-center uk-table-shrink">Portada del Video Resumen</td>
		<td class="uk-text-right">
			<div uk-lightbox>
				<a href="{{ asset('uploads/images/certifications/preview_covers/'.$certificacion->preview_cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
			</div>
		</td>
		<td class="uk-text-left">
			<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_preview_cover"></a>
		</td>
	</tr>
@endif