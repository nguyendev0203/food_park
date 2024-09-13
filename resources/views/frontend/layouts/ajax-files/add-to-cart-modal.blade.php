<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
<form action="" id="modal_add_to_cart_form">
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <div class="fp__cart_popup_img">
        <img src="{{ asset($product->thumb_image) }}" alt="menu" class="img-fluid w-100">
    </div>
    <div class="fp__cart_popup_text">
        <a href="#" class="title">{!! $product->name !!}</a>
        <p class="rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
            <i class="far fa-star"></i>
            <span>(201)</span>
        </p>
        <h4 class="price">
            @if ($product->offer_price > 0)
                <input type="hidden" name="base_price" value="{{ $product->offer_price }}">

                {{ currencyPosition($product->offer_price) }}
                <del>{{ currencyPosition($product->price) }}</del>
            @else
                <input type="hidden" name="base_price" value="{{ $product->price }}">

                {{ currencyPosition($product->price) }}
            @endif
        </h4>

        @if ($product->sizes()->exists())
            <div class="details_size">
                <h5>select size</h5>
                @foreach ($product->sizes as $size)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="{{ $size->id }}"
                            data-price="{{ $size->price }}" name="product_size" id="size-{{ $size->id }}">
                        <label class="form-check-label" for="size-{{ $size->id }}">
                            {{ $size->name }} <span>+ {{ currencyPosition($size->price) }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($product->options()->exists())
            <div class="details_extra_item">
                <h5>select option <span>(optional)</span></h5>
                @foreach ($product->options as $option)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="product_option[]"
                            data-price="{{ $option->price }}" value="{{ $option->id }}"
                            id="option-{{ $option->id }}">
                        <label class="form-check-label" for="option-{{ $option->id }}">
                            {{ $option->name }} <span>+ {{ currencyPosition($option->price) }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="details_quentity">
            <h5>select quentity</h5>
            <div class="quentity_btn_area d-flex flex-wrapa align-items-center">
                <div class="quentity_btn">
                    <button class="btn btn-danger decrement"><i class="fal fa-minus"></i></button>
                    <input type="text" id="quantity" name="quantity" placeholder="1" value="1" readonly>
                    <button class="btn btn-success increment"><i class="fal fa-plus"></i></button>
                </div>
                @if ($product->offer_price > 0)
                    <h3 id="total_price">{{ currencyPosition($product->offer_price) }}</h3>
                @else
                    <h3 id="total_price">{{ currencyPosition($product->price) }}</h3>
                @endif
            </div>
        </div>
        <ul class="details_button_area d-flex flex-wrap">
            @if ($product->quantity === 0)
                <li><button type="button" class="common_btn bg-danger">Stock Out</button></li>
            @else
                <li><button type="submit" class="common_btn modal_cart_button">add to cart</button></li>
            @endif
        </ul>
    </div>
</form>


<script>
    $(document).ready(function() {
        $('input[name="product_size"], input[name="product_option[]"]').on('change', updateTotalPrice);

        $('.increment').on('click', function(e) {
            e.preventDefault();
            const quantityInput = $('#quantity');
            let currentQuantity = parseFloat(quantityInput.val()) || 0; // Handle potential NaN
            quantityInput.val(++currentQuantity);
            updateTotalPrice();
        });

        $('.decrement').on('click', function(e) {
            e.preventDefault();
            const quantityInput = $('#quantity');
            let currentQuantity = parseFloat(quantityInput.val()) || 0;
            if (currentQuantity > 1) {
                quantityInput.val(--currentQuantity);
                updateTotalPrice();
            }
        })

        function updateTotalPrice() {
            const basePrice = parseFloat($('input[name="base_price"]').val());
            const quantity = parseFloat($('#quantity').val());

            const selectedSizePrice = $('input[name="product_size"]:checked').data("price") || 0;

            const selectedOptionsPrice = $('input[name="product_option[]"]:checked')
                .toArray() // Convert to an array for easier iteration
                .reduce((sum, option) => sum + parseFloat($(option).data("price")), 0);

            const totalPrice = (basePrice + selectedSizePrice + selectedOptionsPrice) * quantity;
            const formattedTotalPrice = totalPrice.toFixed(2);
            $('#total_price').text("{{ config('settings.site_currency_icon') }}" +
                formattedTotalPrice);
        }

        $("#modal_add_to_cart_form").on('submit', function(e) {
            e.preventDefault();
            const selectedSize = $("input[name='product_size']:checked");
            const size = $('input[name="product_size"]');
            if (size.length > 0 && selectedSize.length === 0) {
                toastr.error('Please select a size');
                return;
            }
            
            const data = $(this).serialize();
            $.ajax({
                method: "POST",
                url: "{{ route('add-to-cart')}}",
                data: data,
                beforeSend: function(){
                    $('.modal_cart_button').attr('disabled', true);
                    $('.modal_cart_button').html('<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span> Loading...')
                },
                success: function (response) {
                    toastr.success(response.message);
                    updateSidebarCart();
                },
                error: function (xhr, status, error) {
                    const errorMessage = xhr.responseJSON.message;
                    toastr.error(errorMessage);
                },
                complete: function(){
                    $('.modal_cart_button').html('Add to Cart');
                    $('.modal_cart_button').attr('disabled', false);
                }
            });
        });
    })
</script>
