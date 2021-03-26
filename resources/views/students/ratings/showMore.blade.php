<div style="height: auto;">
   @foreach ($valoraciones as $valoracion)
      <div>
         <div class="rating-title">
            @if ($valoracion->user_id != 0)
               {{ $valoracion->user->names }} {{ $valoracion->user->last_names }}
            @else
               {{ $valoracion->name }}
            @endif
         </div>
         <div class="rating-date">{{ date('d-m-Y H:i A', strtotime("$valoracion->created_at -5 Hours")) }}</div>
         <div>
            @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
            @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
            @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
            @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
            @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
         </div>
         <div class="rating-comment">{{ $valoracion->comment }}</div>
      </div>
   @endforeach

   @if ($check == 1)
      <div class="uk-text-center" style="padding-top: 10px;"><a href="javascript:;" class="link-back-to-courses" id="link-show-more" onclick="showMoreRatings();" data-route="{{ route('students.ratings.show-more', [$curso_id, $newCant, $tipo]) }}"><b><i class="fas fa-search-plus"></i> Ver más...</b></a></div>
   @endif
</div>