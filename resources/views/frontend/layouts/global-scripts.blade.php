<script>
    function addToCartModal(slugId) {
        $.ajax({
            url: "{{ route('add-to-cart-modal', ':slug') }}".replace(':slug', slugId),
            method: 'GET',
            beforeSend: function() {
                $('.overlay').addClass('active');
            },
            success: function(response) {
                $(".add-to-cart-modal").html(response);
                $('#cartModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            },
            complete: function() {
                $('.overlay').removeClass('active');
            }
        })
    }

    function updateSidebarCart(callback = null) {
        $.ajax({
            url: "{{ route('get-cart-products') }}",
            method: 'GET',
            success: function(response) {
                $('.cart_content').html(response);
                let cartTotal = $('#cart_total').val();
                let cartCount = $('#cart_product_count').val();

                $('.cart_subtotal').text("{{ currencyPosition(':cartTotal') }}"
                    .replace(':cartTotal', cartTotal));
                $('.cart_count').text(cartCount);

                if (callback && typeof callback === 'function') {
                    callback();
                }

            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        })
    }

    function removeProductFromSidebar(rowId) {
        $.ajax({
            url: "{{ route('cart-product-remove', ':rowId') }}".replace(':rowId', rowId),
            method: 'GET',
            beforeSend: function() {
                $('.overlay-container').removeClass('d-none');
                $('.overlay').addClass('active');
            },
            success: function(response) {
                if (response.status === 'success') {
                    updateSidebarCart(function() {
                        toastr.success(response.message);
                        $('.overlay').removeClass('active');
                        $('.overlay-container').addClass('d-none');
                    })
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            },
            complete: function() {
                $('.overlay').removeClass('active');
                $('.overlay-container').addClass('d-none');
            }
        })
    }
</script>
