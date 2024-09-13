<input type="hidden" value="{{ cartTotal() }}" id="cart_total">
<input type="hidden" value="{{ count(Cart::content()) }}" id="cart_product_count">
@foreach (Cart::content() as $item)
    <li>
        <div class="menu_cart_img">
            <img src="{{ asset($item->options->info['image']) }}" alt="menu" class="img-fluid w-100">
        </div>
        <div class="menu_cart_text">
            <a class="title" href="{{ route('product.show', $item->options->info['slug']) }}">{!! $item->name !!} </a>
            <p class="size">Qty: {{ $item->qty }}</p>
            <p class="size">{{ @$item->options->size['name'] }}
                {{ @$item->options->size['price'] ? '(' . currencyPosition(@$item->options->size['price']) . ')' : '' }}
            </p>
            @foreach ($item->options->extra as $option)
                <span class="extra">{{ $option['name'] }} ({{ currencyPosition($option['price']) }})</span>
            @endforeach
            <p class="price">{{ currencyPosition($item->price) }}</p>
        </div>
        <span class="del_icon" onclick="removeProductFromSidebar('{{ $item->rowId }}')"><i class="fal fa-times"></i></span>
    </li>
@endforeach
