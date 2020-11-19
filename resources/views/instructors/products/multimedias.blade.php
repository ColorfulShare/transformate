@if (!is_null($producto->cover))
									<tr>
										<td class="uk-text-center uk-table-shrink">{{ $producto->cover_name }}</td>
										<td class="uk-text-center uk-table-shrink">
											<i class="fa fa-file-image img-icon"></i>
										</td>
										<td class="uk-text-center uk-table-shrink">Portada</td>
										<td class="uk-text-center">
											<div uk-lightbox>
												<a href="{{ asset('uploads/images/products/'.$producto->cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
											</div>
										</td>
									</tr>
								@endif
								@if (!is_null($producto->file))
									<tr>
										<td class="uk-text-center uk-table-expand">{{ $producto->filename }}</td>
										<td class="uk-text-center uk-table-shrink">
											<i class="fa fa-file-image img-icon"></i>
										</td>
										<td class="uk-text-center uk-table-shrink">Archivo del Producto</td>
										<td class="uk-text-center">
											<a href="{{ asset('uploads/products/'.$producto->file) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;" target="_blank"></a>
										</td>
									</tr>
								@endif