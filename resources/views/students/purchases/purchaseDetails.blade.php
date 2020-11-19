<div class="uk-overflow-auto">
    <table class="uk-table uk-table-hover uk-table-divider">
        <thead>
            <tr>
                <th class="uk-text-center uk-table-shrink">Compra</th> 
                <th class="uk-width-medium">Descripción</th> 
                <th class="uk-text-center uk-width-small">Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $detalle)
                <tr>     
                    <td class="uk-text-center">
                        @if (!is_null($detalle->course_id))
                            <img  src="{{ asset('uploads/images/courses/'.$detalle->course->cover) }}" style="width: 100%;">
                        @elseif (!is_null($detalle->certification_id))
                            <img  src="{{ asset('uploads/images/certifications/'.$detalle->certification->cover) }}" style="width: 100%;">
                        @elseif (!is_null($detalle->podcast_id))
                            <img src="{{ asset('uploads/images/podcasts/'.$detalle->podcast->cover) }}" style="width: 100%;">
                        @elseif (!is_null($detalle->product_id))
                            <img src="{{ asset('uploads/images/products/'.$detalle->market_product->cover) }}" style="width: 100%;">
                        @endif
                    </td> 
                    <td>
                    	@if (!is_null($detalle->course_id)) 
                            {{ $detalle->course->title }}
                        @elseif (!is_null($detalle->certification_id))
                            {{ $detalle->certification->title }}
                        @elseif (!is_null($detalle->podcast_id))
                            {{ $detalle->podcast->title }}
                        @elseif (!is_null($detalle->product_id))
                            {{ $detalle->market_product->name }}
                        @endif
                    </td> 
                    <td class="uk-text-center">COP$ {{ $detalle->amount }}</td>
                </tr>   
            @endforeach
        </tbody>
    </table>
</div>