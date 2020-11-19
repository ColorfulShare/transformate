@if (!is_null($clase->video_file))
										<tr>
											<td class="uk-text-center uk-table-expand">{{ $clase->video_filename }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-video img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Video de la T-Master Class</td>
											<td class="uk-text-center">
												<a href="{{ $clase->video_file }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
											</td>
										</tr>
									@endif
									@if (!is_null($clase->cover))
										<tr>
											<td class="uk-text-center uk-table-shrink">{{ $clase->cover_name }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-image img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Portada</td>
											<td class="uk-text-center">
												<div uk-lightbox>
													<a href="{{ asset('uploads/images/master-class/'.$clase->cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;" target="_blank"></a>
												</div>
											</td>
										</tr>
									@endif