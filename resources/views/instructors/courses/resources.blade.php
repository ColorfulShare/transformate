 <table class="uk-table uk-table-striped">
	<thead>
		<tr>
			<th class="uk-text-center">Recurso</th>
			<!--<th class="uk-text-center">Link</th>-->
			<th class="uk-text-center">Acción</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($recursos as $recurso)
			<tr>
				<td class="uk-text-center">{{ $recurso->filename }}</td>
				<!--<td class="uk-text-center">{{ $recurso->link }}</td>-->
				<td class="uk-text-center">
					<a href="{{ route('instructors.courses.temary.delete-resource', $recurso->id) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: trash;" uk-tooltip="Eliminar Recurso"></a>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>