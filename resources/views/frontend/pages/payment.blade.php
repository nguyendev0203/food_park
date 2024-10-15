@extends('frontend.layouts.master')

@section('content')
    <!--=============================
                                BREADCRUMB START
                            ==============================-->
    <section class="fp__breadcrumb" style="background: url({{ asset('frontend/images/counter_bg.jpg') }});">
        <div class="fp__breadcrumb_overlay">
            <div class="container">
                <div class="fp__breadcrumb_text">
                    <h1>payment</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">home</a></li>
                        <li><a href="javascript:;">payment</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
                                BREADCRUMB END
                            ==============================-->


    <!--============================
                                PAYMENT PAGE START
                            ==============================-->
    <section class="fp__payment_page mt_100 xs_mt_70 mb_100 xs_mb_70">
        <div class="container">
            <h2>Choose your payment gateway</h2>
            <div class="row">
                <div class="col-lg-8">
                    <div class="fp__payment_area">
                        <div class="row">
                            @if (config('gatewaySettings.paypal_status'))
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="fp__single_payment payment-card" data-name="paypal" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" href="#">
                                        <img src="{{ asset(config('gatewaySettings.paypal_logo')) }}" alt="payment method"
                                            class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif
                            @if (config('gatewaySettings.stripe_status'))
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="fp__single_payment payment-card" data-name="stripe" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" href="#">
                                        <img src="{{ asset(config('gatewaySettings.stripe_logo')) }}" alt="payment method"
                                            class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif
                            @if (config('gatewaySettings.razorpay_status'))
                                <div class="col-lg-3 col-6 col-sm-4 col-md-3 wow fadeInUp" data-wow-duration="1s">
                                    <a class="fp__single_payment payment-card" data-name="razorpay" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" href="#">
                                        <img src="{{ asset(config('gatewaySettings.razorpay_logo')) }}" alt="payment method"
                                            class="img-fluid w-100">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt_25 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__cart_list_footer_button">
                        <h6>total cart</h6>
                        <p>subtotal: <span>{{ currencyPosition($subtotal) }}</span></p>
                        <p>delivery: <span>{{ currencyPosition($shippingCost) }}</span></p>
                        <p>discount: <span>{{ currencyPosition($discount) }}</span></p>
                        <p class="total"><span>total:</span> <span>{{ currencyPosition($grandTotal) }}</span></p>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--============================
                                PAYMENT PAGE END
                            ==============================-->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.payment-card').on('click', function(e) {
                e.preventDefault();
                const paymentGateway = $(this).data('name');
                $.ajax({
                    type: "POST",
                    url: "{{ route('make.payment') }}",
                    data: {
                        payment_gateway: paymentGateway
                    },
                    beforeSend: () => showLoader(),
                    success: ({
                        redirect_url
                    }) => {
                        window.location.href = redirect_url;
                    },
                    error: (xhr, status, error) => {
                        const errors = xhr.responseJSON.errors;
                        errors.forEach(error => toastr.error(error));
                    },
                    complete: () => hideLoader()
                });
            });
        });
    </script>
@endpush
