@if (!is_null($podcast->audio_file))
										<tr>
											<td class="uk-text-center uk-table-expand">{{ $podcast->audio_filename }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-audio img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Audio del T-Book</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ $podcast->audio_file }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_audiofile"></a>
											</td>
										</tr>
									@endif
									@if (!is_null($podcast->cover))
										<tr>
											<td class="uk-text-center uk-table-shrink">{{ $podcast->cover_name }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-image img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Portada</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_cover"></a>
											</td>
										</tr>
									@endif
									@if (!is_null($podcast->preview))
										<tr>
											<td class="uk-text-center uk-table-expand">{{ $podcast->preview_name }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-audio img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Audio Resumen</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ $podcast->preview }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_preview"></a>
											</td>
										</tr>
									@endif