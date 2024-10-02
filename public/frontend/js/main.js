$(function () {
    "use strict";
    if ($(".main_menu").offset() != undefined) {
        var navoff = $(".main_menu").offset().top;
        $(window).scroll(function () {
            var scrolling = $(this).scrollTop();
            if (scrolling > navoff) {
                $(".main_menu").addClass("menu_fix");
            } else {
                $(".main_menu").removeClass("menu_fix");
            }
        });
    }
    $(".cart_icon").click(function () {
        $(".fp__menu_cart_area").addClass("show_mini_cart");
    });
    $(".close_cart").click(function () {
        $(".fp__menu_cart_area").removeClass("show_mini_cart");
    });
    $(".menu_search").click(function () {
        $(".fp__search_form").addClass("show");
    });
    $(".close_search").click(function () {
        $(".fp__search_form").removeClass("show");
    });
    $("#select_js").niceSelect();
    $("#select_js2").niceSelect();
    $("#select_js3").niceSelect();
    $("#select_js4").niceSelect();
    $(".nice-select").niceSelect();
    $(".banner_slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 4000,
        cssEase: "linear",
        dots: !0,
        arrows: !1,
    });
    $(".offer_item_slider").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 4000,
        dots: !0,
        arrows: !1,
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 3 } },
            { breakpoint: 1200, settings: { slidesToShow: 2 } },
            { breakpoint: 992, settings: { slidesToShow: 1 } },
            { breakpoint: 768, settings: { slidesToShow: 1 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } },
        ],
    });
    var $grid = $(".grid").isotope({});
    $(".menu_filter").on("click", "button", function () {
        var filterValue = $(this).attr("data-filter");
        $grid.isotope({ filter: filterValue });
    });
    $(".menu_filter button").on("click", function (event) {
        $(this).siblings(".active").removeClass("active");
        $(this).addClass("active");
        event.preventDefault();
    });
    var d = new Date(),
        countUpDate = new Date();
    d.setDate(d.getDate() + 365);
    simplyCountdown(".simply-countdown-one", {
        year: d.getFullYear(),
        month: d.getMonth() + 1,
        day: d.getDate(),
        enableUtc: !0,
    });
    $(".team_slider").slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 4000,
        dots: !0,
        arrows: !1,
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 4 } },
            { breakpoint: 1200, settings: { slidesToShow: 3 } },
            { breakpoint: 992, settings: { slidesToShow: 2 } },
            { breakpoint: 768, settings: { slidesToShow: 2 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } },
        ],
    });
    $(".add_slider").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 4000,
        dots: !1,
        arrows: !0,
        nextArrow: '<i class="far fa-long-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-long-arrow-left prevArrow"></i>',
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 3 } },
            { breakpoint: 1200, settings: { slidesToShow: 2 } },
            { breakpoint: 992, settings: { slidesToShow: 2 } },
            { breakpoint: 768, settings: { slidesToShow: 1 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } },
        ],
    });
    $(".counter").countUp();
    $(".testi_slider").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: !1,
        autoplaySpeed: 4000,
        dots: !0,
        arrows: !1,
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 3 } },
            { breakpoint: 1200, settings: { slidesToShow: 2 } },
            { breakpoint: 992, settings: { slidesToShow: 2 } },
            { breakpoint: 768, settings: { slidesToShow: 1 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } },
        ],
    });
    $(".brand_slider").slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 2000,
        dots: !1,
        arrows: !1,
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 5 } },
            { breakpoint: 1200, settings: { slidesToShow: 4 } },
            { breakpoint: 992, settings: { slidesToShow: 3 } },
            { breakpoint: 768, settings: { slidesToShow: 2 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } },
        ],
    });
    $(".fp__scroll_btn").on("click", function () {
        $("html, body").animate({ scrollTop: 0 }, 300);
    });
    $(window).on("scroll", function () {
        var scrolling = $(this).scrollTop();
        if (scrolling > 300) {
            $(".fp__scroll_btn").fadeIn();
        } else {
            $(".fp__scroll_btn").fadeOut();
        }
    });
    $(".venobox").venobox();
    $("#sticky_sidebar").stickit({ top: 10 });
    $(".blog_det_slider").slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 4000,
        dots: !0,
        arrows: !1,
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 2 } },
            { breakpoint: 1200, settings: { slidesToShow: 2 } },
            { breakpoint: 992, settings: { slidesToShow: 3 } },
            { breakpoint: 768, settings: { slidesToShow: 1 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } },
        ],
    });
    $(".related_product_slider").slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 4000,
        dots: !1,
        arrows: !0,
        nextArrow: '<i class="far fa-long-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-long-arrow-left prevArrow"></i>',
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 4 } },
            { breakpoint: 1200, settings: { slidesToShow: 3 } },
            { breakpoint: 992, settings: { slidesToShow: 2 } },
            { breakpoint: 768, settings: { slidesToShow: 2 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } },
        ],
    });
    new WOW().init();
    $(".dash_info_btn").click(function () {
        $(".fp_dash_personal_info").toggleClass("show");
    });
    $(".view_invoice").on("click", function () {
        $(".fp_dashboard_order").fadeOut();
    });
    $(".view_invoice").on("click", function () {
        $(".fp__invoice").fadeIn();
    });
    $(".go_back").on("click", function () {
        $(".fp_dashboard_order").fadeIn();
    });
    $(".go_back").on("click", function () {
        $(".fp__invoice").fadeOut();
    });
    $(".dash_add_new_address").on("click", function () {
        $(".address_body").addClass("show_new_address");
    });
    $(".cancel_new_address").on("click", function () {
        $(".address_body").removeClass("show_new_address");
    });
    // $(".dash_edit_btn").on("click", function () {
    //     $(".address_body").addClass("show_edit_address");
    // });
    $(".cancel_edit_address").on("click", function () {
        $(".address_body").removeClass("show_edit_address");
    });
    $(".banner2_slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 5000,
        dots: !1,
        arrows: !0,
        nextArrow: '<i class="far fa-long-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-long-arrow-left prevArrow"></i>',
    });
    $(".testi_slider2").slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 4000,
        dots: !1,
        arrows: !0,
        nextArrow: '<i class="far fa-long-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-long-arrow-left prevArrow"></i>',
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 2 } },
            { breakpoint: 1200, settings: { slidesToShow: 2 } },
            { breakpoint: 992, settings: { slidesToShow: 1 } },
            { breakpoint: 768, settings: { slidesToShow: 1 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } },
        ],
    });
    if ($("#exzoom").length > 0) {
        $("#exzoom").exzoom({ autoPlay: !0 });
    }
});
