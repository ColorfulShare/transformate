{{-- Sección de Categorías --}}
      <div class="categories">
         <div uk-slider="autoplay: true; autoplay-interval: 3000;">
            <div class="uk-position-relative">
               <div class="uk-slider-container uk-light">
                  <ul class="uk-slider-items uk-child-width-1-4 uk-child-width-1-4@s uk-child-width-1-6@m uk-grid">
                     <li class="category">
                        <a onclick="load_courses(100);">
                           <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #305089;">
                              <div class="category-icon"><i class="fab fa-tumblr"></i></div>
                              <div class="category-title">T-Master Clases</div>
                           </div>
                        </a>
                     </li>
                     <li class="category">
                        <a onclick="load_courses(0);">
                           <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #006B9B;">
                              <div class="category-icon"><i class="fas fa-book"></i></div>
                              <div class="category-title">T-Libros</div>
                           </div>
                        </a>
                     </li>
                     @foreach ($categorias as $categoria)
                        <li class="category">
                           <a onclick="load_courses({{$categoria->id}});">
                              <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: {{ $categoria->color }};">
                                 <div class="category-icon"><i class="{{ $categoria->icon }}"></i></div>
                                 <div class="category-title">{{ $categoria->title}}</div>
                              </div>
                           </a>
                        </li>
                     @endforeach
                  </ul>
               </div>
            </div>
         </div>
      </div>

      <div class="uk-text-center" id="wait" style="display: none;"> 
         <span uk-spinner="ratio: 4"></span>
      </div>
      
      {{-- Sección de Cursos por Categoría --}}
      <div id="cursos">
         {{-- Cursos Versión Móvil (4 Cards Verticales) --}}
         <div class="courses uk-hidden@s">
            @if ($cantCursos > 0)
               @if ($cantCursos >= 4)
                  @for($i=0; $i < 4; $i++)
                     <div class="uk-card uk-card-small card-background-ligth" id="curso-{{$cursos[$i]->id}}">
                        <div class="uk-card-media-top image-div">
                           @if (!is_null($cursos[$i]->preview))
                              @if ($categoriaSeleccionada == 100)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'clase']) }}">
                                    <img src="{{ asset('uploads/images/master-class/'.$cursos[$i]->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'podcast']) }}">
                                    <img src="{{ asset('uploads/images/podcasts/'.$cursos[$i]->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @else
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'curso']) }}">
                                    <img src="{{ asset('uploads/images/courses/'.$cursos[$i]->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @endif
                           @else
                              @if ($categoriaSeleccionada == 100)
                                 <a href="{{ route('landing.master-class.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                                    <img src="{{ asset('uploads/images/master-class/'.$cursos[$i]->cover) }}" class="content-image"> 
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a href="{{ route('landing.podcasts.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                                    <img src="{{ asset('uploads/images/podcasts/'.$cursos[$i]->cover) }}" class="content-image"> 
                                 </a>
                              @else
                                 <a href="{{ route('landing.courses.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                                    <img src="{{ asset('uploads/images/courses/'.$cursos[$i]->cover) }}" class="content-image"> 
                                 </a>
                              @endif
                           @endif
                           @if ($categoriaSeleccionada != 100)
                              <div class="image-category-div">{{ $cursos[$i]->category->title }}</div>        
                           @endif           
                        </div>
                        <div class="uk-card-body card-body" style="padding-top: 2%;">
                           @if ($categoriaSeleccionada == 100)
                              <a href="{{ route('landing.master-class.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                           @elseif ($categoriaSeleccionada == 0)
                              <a href="{{ route('landing.podcasts.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                           @else
                              <a href="{{ route('landing.courses.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                           @endif
                              <div style="min-height: 100px;">
                                 <div class="course-title color-ligth2" id="course-title-{{$cursos[$i]->id}}">{{ $cursos[$i]->title }}</div>
                                 @if ($categoriaSeleccionada != 100)
                                    <div class="course-instructor color-ligth2" id="course-instructor-{{$cursos[$i]->id}}">{{ $cursos[$i]->user->names.' '.$cursos[$i]->user->last_names }}</div>
                                 @endif
                                 <div class="course-subtitle color-ligth2" id="course-subtitle-{{$cursos[$i]->id}}">{{ strtolower($cursos[$i]->subtitle) }}</div>
                              </div>                   
                           </a>    
                           <div class="uk-text-center" style="padding-top: 15px;">   
                              <div class="uk-child-width-1-1" uk-grid>  
                                 @if ($categoriaSeleccionada == 100) 
                                    <div>
                                       <a class="link-course" href="{{ route('landing.master-class.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                    </div>
                                 @else
                                    @if ($categoriaSeleccionada != 0)
                                       <div>
                                          <a class="link-course" href="{{ route('landing.courses.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                       </div>
                                       @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                                          @if ($cursos[$i]->price > 0)
                                             <div style="margin-top: 10px;">
                                                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursos[$i]->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
                                             </div>
                                          @endif
                                       @endif
                                    @else
                                       <div>
                                          <a class="link-course" href="{{ route('landing.podcasts.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                       </div>
                                       @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                                          @if ($cursos[$i]->price > 0)
                                             <div style="margin-top: 10px;">
                                                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursos[$i]->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
                                             </div>
                                          @endif
                                       @endif
                                    @endif
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div><br>
                  @endfor
               @else
                  @foreach ($cursos as $curso2)
                     <div class="uk-card uk-card-small card-background-ligth" id="curso-{{$curso2->id}}">
                        <div class="uk-card-media-top image-div">
                           @if (!is_null($curso2->preview))
                              @if ($categoriaSeleccionada == 100)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'clase']) }}">
                                    <img src="{{ asset('uploads/images/master-class/'.$curso2->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'podcast']) }}">
                                    <img src="{{ asset('uploads/images/podcasts/'.$curso2->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @else
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'curso']) }}">
                                    <img src="{{ asset('uploads/images/courses/'.$curso2->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @endif
                           @else
                              @if ($categoriaSeleccionada == 100)
                                 <a href="{{ route('landing.master-class.show', [$curso2->slug, $curso2->id]) }}">
                                    <img src="{{ asset('uploads/images/master-class/'.$curso2->cover) }}" class="content-image"> 
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a href="{{ route('landing.podcasts.show', [$curso2->slug, $curso2->id]) }}">
                                    <img src="{{ asset('uploads/images/podcasts/'.$curso2->cover) }}" class="content-image"> 
                                 </a>
                              @else
                                 <a href="{{ route('landing.courses.show', [$curso2->slug, $curso2->id]) }}">
                                    <img src="{{ asset('uploads/images/courses/'.$curso2->cover) }}" class="content-image"> 
                                 </a>
                              @endif
                           @endif
                           @if ($categoriaSeleccionada != 100)
                              <div class="image-category-div">{{ $curso2->category->title }}</div>    
                           @endif               
                        </div>
                        <div class="uk-card-body card-body" style="padding-top: 2%;">
                           @if ($categoriaSeleccionada == 100)
                              <a href="{{ route('landing.master-class.show', [$curso2->slug, $curso2->id]) }}">
                           @elseif ($categoriaSeleccionada == 0)
                              <a href="{{ route('landing.podcasts.show', [$curso2->slug, $curso2->id]) }}">
                           @else
                              <a href="{{ route('landing.courses.show', [$curso2->slug, $curso2->id]) }}">
                           @endif
                              <div style="min-height: 100px;">
                                 <div class="course-title color-ligth2" id="course-title-{{$curso2->id}}">{{ $curso2->title }}</div>
                                 @if ($categoriaSeleccionada != 100)
                                    <div class="course-instructor color-ligth2" id="course-instructor-{{$curso2->id}}">{{ $curso2->user->names.' '.$curso2->user->last_names }}</div>
                                 @endif
                                 <div class="course-subtitle color-ligth2" id="course-subtitle-{{$curso2->id}}">{{ strtolower($curso2->subtitle) }}</div>
                              </div>                   
                           </a>    
                           <div class="uk-text-center" style="padding-top: 15px;">   
                              <div class="uk-child-width-1-1" uk-grid>  
                                 @if ($categoriaSeleccionada == 100) 
                                    <div>
                                       <a class="link-course" href="{{ route('landing.master-class.show', [$curso2->slug, $curso2->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                    </div>
                                 @else
                                    @if ($categoriaSeleccionada != 0)
                                       <div>
                                          <a class="link-course" href="{{ route('landing.courses.show', [$curso2->slug, $curso2->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                       </div>
                                       @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                                          @if ($curso2->price > 0)
                                             <div style="margin-top: 10px;">
                                                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso2->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
                                             </div>
                                          @endif
                                       @endif
                                    @else
                                       <div>
                                          <a class="link-course" href="{{ route('landing.podcasts.show', [$curso2->slug, $curso2->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                       </div>
                                       @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                                          @if ($curso2->price > 0)
                                             <div style="margin-top: 10px;">
                                                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso2->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
                                             </div>
                                          @endif
                                       @endif
                                    @endif
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div><br>
                  @endforeach
               @endif
            @endif
         </div>

         {{-- Cursos Versión Escritorio (Slider) --}}
         <div class="courses uk-visible@s">
            <div uk-slider class="content-carousel">
               <div class="uk-position-relative">
                  <div class="uk-slider-container uk-light">
                     <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl" uk-grid>
                        @foreach ($cursos as $curso)                            
                           <li class="course uk-transition-toggle" tabindex="0">
                              <div class="uk-card uk-card-small card-background-ligth" id="curso-pc-{{$curso->id}}">
                                 <div class="uk-card-media-top image-div">
                                    @if (!is_null($curso->preview))
                                       @if ($categoriaSeleccionada == 100)
                                          <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'clase']) }}">
                                             <img src="{{ asset('uploads/images/master-class/'.$curso->cover) }}" class="content-image">  
                                             <div class="uk-overlay uk-position-center">
                                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                             </div>
                                          </a>
                                       @elseif ($categoriaSeleccionada == 0)
                                          <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'podcast']) }}">
                                             <img src="{{ asset('uploads/images/podcasts/'.$curso->cover) }}" class="content-image">  
                                             <div class="uk-overlay uk-position-center">
                                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                             </div>
                                          </a>
                                       @else
                                          <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}">
                                             <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image">  
                                             <div class="uk-overlay uk-position-center">
                                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                             </div>
                                          </a>
                                       @endif
                                    @else
                                       @if ($categoriaSeleccionada == 100)
                                          <a href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">
                                             <img src="{{ asset('uploads/images/master-class/'.$curso->cover) }}" class="content-image"> 
                                          </a>
                                       @elseif ($categoriaSeleccionada == 0)
                                          <a href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}">
                                             <img src="{{ asset('uploads/images/podcasts/'.$curso->cover) }}" class="content-image"> 
                                          </a>
                                       @else
                                          <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
                                             <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                          </a>
                                       @endif
                                    @endif
                                    @if ($categoriaSeleccionada != 100)
                                       <div class="image-category-div">{{ $curso->category->title }}</div>
                                    @endif
                                 </div>
                                 <div class="uk-card-body card-body" style="padding-top: 2%; ">
                                    @if ($categoriaSeleccionada == 100)
                                       <a href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">
                                    @elseif ($categoriaSeleccionada == 0)
                                       <a href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}">
                                    @else
                                       <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
                                    @endif
                                       <div style="min-height: 120px;">
                                          <div class="course-title color-ligth2" id="course-title-pc-{{$curso->id}}">{{ $curso->title }}</div>
                                          @if ($categoriaSeleccionada != 100)
                                             <div class="course-instructor color-ligth2" id="course-instructor-pc-{{$curso->id}}">{{ $curso->user->names.' '.$curso->user->last_names }}</div>
                                          @endif
                                          <div class="course-subtitle color-ligth2" id="course-subtitle-pc-{{$curso->id}}">{{ strtolower($curso->subtitle) }}</div>
                                       </div>                    
                                    </a>    
                                    <div class="uk-text-center" style="padding-top: 15px;">
                                       <div class="uk-child-width-1-1" uk-grid>   
                                          @if ($categoriaSeleccionada == 100)
                                             <div>
                                                <a class="link-course" href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                             </div> 
                                          @else
                                             @if ($categoriaSeleccionada != 0)
                                                <div>
                                                   <a class="link-course" href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                                </div>
                                                @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                                                   @if ($curso->price > 0)
                                                      <div style="margin-top: 10px;">
                                                         <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
                                                      </div>
                                                   @endif
                                                @endif
                                             @else
                                                <div>
                                                   <a class="link-course" href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                                </div>
                                                @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                                                   @if ($curso->price > 0)
                                                      <div style="margin-top: 10px;">
                                                         <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
                                                      </div>
                                                   @endif
                                                @endif
                                             @endif
                                          @endif
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </li>
                        @endforeach
                     </ul>                       
                  </div>
                  <div class="controls">
                     <a class="uk-position-center-left-out" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                     <a class="uk-position-center-right-out" href="#" uk-slidenav-next uk-slider-item="next"></a>
                  </div> 
               </div>
            </div>
         </div>
      </div>