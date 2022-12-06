<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="<?= $lang ?>">

<head>
    <!-- Title -->
    <title><?= (!empty($meta_title) ? stripslashes($meta_title) : (!empty($og_title) ? stripslashes($og_title) : stripslashes($settings->company_name))) ?></title>
    <!-- Title -->

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes, shrink-to-fit=no,minimal-ui">
    <meta name="description" content="<?= clean(@$meta_desc) ?>">
    <?php /*
    <meta name="keywords" content="<?= clean(@$meta_keyw) ?>">
	*/ ?>
    <meta name="subject" content="<?= clean(@$meta_desc) ?>">
    <meta name="copyright" content="<?= $settings->company_name ?>">
    <meta name="language" content="<?= strto("lower|upper", $lang) ?>">
    <meta name="robots" content="all" />
    <meta name="revised" content="<?= turkishDate("d F Y, l H:i:s", date("Y-m-d H:i:s")) ?>" />
    <meta name="abstract" content="<?= clean(@$meta_desc) ?>">
    <meta name="topic" content="<?= clean(@$meta_desc) ?>">
    <meta name="summary" content="<?= clean(@$meta_desc) ?>">
    <meta name="Classification" content="Business">
    <meta name="author" content="Adorra™, emrekilic19983@gmail.com">
    <meta name="designer" content="Adorra™, emrekilic19983@gmail.com">
    <meta name="copyright" content="Adorra™, emrekilic19983@gmail.com 2023 &copy; Tüm Hakları Saklıdır.">
    <meta name="reply-to" content="<?= $settings->email ?>">
    <meta name="owner" content="Adorra™, emrekilic19983@gmail.com">
    <meta name="url" content="<?= clean(base_url()) ?>">
    <meta name="identifier-URL" content="<?= clean(base_url()) ?>">
    <meta name="directory" content="submission">
    <meta name="category" content="Article">
    <meta name="coverage" content="Worldwide">
    <meta name="distribution" content="Global">
    <meta name="rating" content="General">
    <meta name="revisit-after" content="1 days">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta content="yes" name="apple-touch-fullscreen" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta property="og:image:secure" content="<?= clean(@$og_image) ?>">
    <meta property="og:locale" content="<?= strto("lower", $lang) . '_' . strto("lower|upper", $lang) ?>">
    <meta property="og:url" content="<?= (!empty($og_url) ? clean($og_url) : clean(base_url())) ?>" />
    <meta property="og:type" content="<?= (!empty($og_type) ? clean($og_type) : "website") ?>" />
    <meta property="og:title" content="<?= (!empty($meta_title) ? stripslashes($meta_title) : (!empty($og_title) ? stripslashes($og_title) : stripslashes($settings->company_name))) ?>" />
    <meta property="og:description" content="<?= (!empty($og_description) ? clean($og_description) : clean(@$meta_desc)) ?>" />
    <meta property="og:image" content="<?= clean(@$og_image) ?>" />
    <meta property="og:image:secure_url" content="<?= clean(@$og_image) ?>" />
    <meta name="twitter:title" content="<?= (!empty($meta_title) ? stripslashes($meta_title) : (!empty($og_title) ? stripslashes($og_title) : stripslashes($settings->company_name))) ?>">
    <meta name="twitter:description" content="<?= (!empty($og_description) ? clean($og_description) : clean(@$meta_desc)) ?>">
    <meta name="twitter:image" content="<?= clean(@$og_image) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:site_name" content="<?= (!empty($meta_title) ? stripslashes($meta_title) : (!empty($og_title) ? stripslashes($og_title) : stripslashes($settings->company_name))) ?>">
    <meta name="twitter:image:alt" content="<?= (!empty($meta_title) ? stripslashes($meta_title) : (!empty($og_title) ? stripslashes($og_title) : stripslashes($settings->company_name))) ?>">
    <meta name="googlebot" content="archive,follow,imageindex,index,odp,snippet,translate">
    <meta http-equiv="Cache-Control" content="public,max-age=1800,max-stale,stale-while-revalidate=86400,stale-if-error=259200" rem="max-age=30minutes" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="MSThemeCompatible" content="no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="translucent black" />
    <meta name="msapplication-navbutton-color" content="translucent black" />
    <meta name="mssmarttagspreventparsing" content="true" />
    <meta name="theme-color" content="#b1cff4" />
    <meta http-equiv="Page-Enter" content="RevealTrans(Duration=1.0,Transition=1)" />
    <meta http-equiv="Page-Exit" content="RevealTrans(Duration=1.0,Transition=1)" />
    <meta name="publisher" content="Adorra™, emrekilic19983@gmail.com" />
    <link rel="canonical" href="<?= (!empty($og_url) ? clean($og_url) : clean(base_url())) ?>" />
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="preconnect" href="<?= base_url() ?>">
    <link rel="dns-prefetch" href="<?= base_url() ?>">
    <!-- Favicon -->
    <?php $imageSize = getimagesize(get_picture("settings_v", $settings->favicon)) ?? [32, 32]; ?>
    <link rel="shortcut icon" sizes="<?= ($imageSize[0]) ?>x<?= ($imageSize[1]) ?>" href="<?= get_picture("settings_v", $settings->favicon); ?>" type="<?= image_type_to_mime_type(exif_imagetype(get_picture("settings_v", $settings->favicon))) ?>">
    <link rel="icon" sizes="<?= ($imageSize[0]) ?>x<?= ($imageSize[1]) ?>" href="<?= get_picture("settings_v", $settings->favicon); ?>" type="<?= image_type_to_mime_type(exif_imagetype(get_picture("settings_v", $settings->favicon))) ?>">
    <link rel="apple-touch-icon" sizes="<?= ($imageSize[0]) ?>x<?= ($imageSize[1]) ?>" href="<?= get_picture("settings_v", $settings->favicon); ?>" type="<?= image_type_to_mime_type(exif_imagetype(get_picture("settings_v", $settings->favicon))) ?>">
    <!-- META TAGS -->

    <!-- === STYLES === -->
    <!-- iziToast -->
    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/iziToast.min.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/iziToast.min.css") ?>">
    </noscript>
    <!-- #iziToast -->

    <!-- iziModal -->
    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/iziModal.min.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/iziModal.min.css") ?>">
    </noscript>
    <!-- #iziModal -->

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/sweetalert.min.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/sweetalert.min.css") ?>">
    </noscript>

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/unicons.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/unicons.css") ?>">
    </noscript>

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/style.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/style.css") ?>">
    </noscript>

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/night-mode.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/night-mode.css") ?>">
    </noscript>

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/responsive.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/responsive.css") ?>">
    </noscript>

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/all.min.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/all.min.css") ?>">
    </noscript>

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/owl.carousel.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/owl.carousel.css") ?>">
    </noscript>

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/owl.theme.default.min.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/owl.theme.default.min.css") ?>">
    </noscript>

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/bootstrap.min.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/bootstrap.min.css") ?>">
    </noscript>

    <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/bootstrap-select.min.css") ?>">
    <noscript>
        <link rel="stylesheet" type="text/css" href="<?= asset_url("public/css/bootstrap-select.min.css") ?>">
    </noscript>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/css/lightgallery.min.css">
    <noscript>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/css/lightgallery.min.css">
    </noscript>



    <style>
        /*--------------------------------
        Cookies area
        -----------------------------------*/
        .cookie-bar.show {
            bottom: 0;
            -webkit-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        .cookie-bar p {
            color: #fff;
            margin-bottom: 0;
        }

        .cookie-bar a {
            margin-left: 20px;
            color: #fff !important;
        }

        .cookie-bar {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            position: fixed;
            bottom: -100%;
            left: 0;
            width: 100%;
            background-color: var(--main-theme-color);
            padding: 10px;
            -webkit-transition: all 0.5s ease;
            transition: all 0.5s ease;
            z-index: 99;
        }


        body::-webkit-scrollbar {
            width: 6px;
            height: 8px
        }

        body::-webkit-scrollbar-thumb {
            background: var(--main-theme-color) !important
        }

        body::-webkit-scrollbar-track {
            background: var(--dark-bg-color)
        }

        .bg-main {
            background-color: var(--main-theme-color);
        }

        .bg-main-dark {
            background-color: var(--dark-bg-color);
        }

        .bg-main-light {
            background-color: #efefef6b;
        }

        .fixed-phone {
            position: fixed;
            bottom: 135px;
            right: 16px;
            border: 2px solid #fff;
            cursor: pointer;
            border-radius: 50%;
            z-index: 1;
            -webkit-box-shadow: -4px 1px 7px 0 rgb(84 84 84 / 35%);
            box-shadow: -1px 1px 5px 0 rgb(84 84 84 / 35%)
        }

        .fixed-whatsapp {
            position: fixed;
            bottom: 80px;
            right: 16px;
            border: 2px solid #fff;
            cursor: pointer;
            border-radius: 50%;
            z-index: 1;
            -webkit-box-shadow: -4px 1px 7px 0 rgba(84, 84, 84, .35);
            box-shadow: -1px 1px 5px 0 rgba(84, 84, 84, .35)
        }

        .fixed-maps i,
        .fixed-phone i,
        .fixed-phone2 i,
        .fixed-whatsapp i,
        .fixed-whatsapp2 i {
            height: 42px;
            width: 42px;
            line-height: 42px;
            font-size: 20px;
            margin: 2px;
            color: #fff;
            text-align: center;
            border-radius: 50%
        }

        .fixed-maps:hover i,
        .fixed-phone2:hover i,
        .fixed-phone:hover i,
        .fixed-whatsapp2:hover i,
        .fixed-whatsapp:hover i {
            animation: shake 1s cubic-bezier(.36, .07, .19, .97) both;
            transform: translate3d(0, 0, 0);
            backface-visibility: hidden;
            perspective: 1000px;
            color: #fff
        }

        .color-checkboxes {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin: 35px 0
        }

        .color-checkboxes {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin: 35px 0
        }

        .color-checkboxes h4 {
            margin-right: 10px;
            font-size: 16px;
            font-weight: 900;
            color: #000
        }

        .color-checkboxes #col-Blue-label {
            background: #2196f3
        }

        .color-checkboxes #col-Green-label {
            background: #8bc34a
        }

        .color-checkboxes #col-Yellow-label {
            background: #fdd835
        }

        .color-checkboxes #col-Orange-label {
            background: #ff9800
        }

        .color-checkboxes #col-Red-label {
            background: #f44336
        }

        .color-checkboxes #col-Black-label {
            background: #222
        }

        .color-checkbox {
            width: 20px;
            height: 20px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            position: relative;
            border-radius: 16px;
            -webkit-transition: -webkit-transform .1s linear;
            transition: -webkit-transform .1s linear;
            -o-transition: transform .1s linear;
            transition: transform .1s linear;
            transition: transform .1s linear, -webkit-transform .1s linear;
            cursor: pointer
        }

        .color-checkbox::after {
            content: "";
            display: block;
            width: 10px;
            height: 10px;
            position: absolute;
            top: 5px;
            left: 5px;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
            opacity: 0;
            -webkit-transition: opacity .1s;
            -o-transition: opacity .1s;
            transition: opacity .1s;
            text-align: center;
            background: #fff;
            border-radius: 69px
        }

        .color-checkbox__input:checked+.color-checkbox:after {
            opacity: 1
        }

        .color-checkbox__input {
            visibility: hidden;
            width: 0;
            pointer-events: none;
            position: absolute
        }

        .quickview .add-tocart-wrap a.add-to-cart {
            width: 210px
        }

        label.white {
            margin-right: 4px
        }

        .radio+label.label.white:hover,
        input[type=radio]+label.label.white:hover {
            border-color: rgba(145, 145, 145, .7)
        }

        .radio+label.label.white,
        input[type=radio]+label.label.white {
            background-color: #fff;
            border-color: #d2d2d2;
            color: #000
        }

        .radio+label:hover,
        input[type=radio]+label:hover {
            border-color: #919191
        }

        .radio+label.label,
        input[type=radio]+label.label {
            border-radius: 3px;
            -moz-border-radius: 3px;
            -o-border-radius: 3px;
            -webkit-border-radius: 3px;
            -ms-webkit-radius: 3px;
            zoom: 1;
            position: relative;
            z-index: 1;
            z-index: initial;
            padding: 7px 16px 6px;
            background-color: #fff;
            border: 3px solid #fff;
            display: inline-block;
            font-weight: 700;
            line-height: 1
        }

        .radio,
        input[type=radio] {
            display: none
        }

        [type=checkbox],
        [type=radio] {
            box-sizing: border-box;
            padding: 0
        }

        input[type=radio]:checked+label.label {
            border-color: #111 !important
        }

        .address[type=radio]:checked,
        .address[type=radio]:not(:checked) {
            position: absolute;
            left: -9999px
        }

        .address[type=radio]:checked+label,
        .address[type=radio]:not(:checked)+label {
            position: relative;
            padding-left: 40px;
            cursor: pointer;
            line-height: 36px;
            display: inline-block;
            color: #666
        }

        .address[type=radio]:checked+label:before,
        .address[type=radio]:not(:checked)+label:before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 36px;
            height: 36px;
            border: 1px solid #ddd;
            border-radius: 100%;
            background: #fff
        }

        .address[type=radio]:checked+label:after,
        .address[type=radio]:not(:checked)+label:after {
            content: "";
            width: 28px;
            height: 28px;
            background: var(--main-theme-color);
            position: absolute;
            top: 4px;
            left: 4px;
            border-radius: 100%;
            -webkit-transition: all .2s ease;
            transition: all .2s ease
        }

        .address[type=radio]:not(:checked)+label:after {
            opacity: 0;
            -webkit-transform: scale(0);
            transform: scale(0)
        }

        .address[type=radio]:checked+label:after {
            opacity: 1;
            -webkit-transform: scale(1);
            transform: scale(1)
        }

        .product-gallery-popup {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 30px;
            height: 30px;
            padding: 0;
            background-color: var(--main-theme-color);
            font-size: 12px;
            color: #fff;
            text-align: center;
            border: 0;
            border-radius: 3px;
            z-index: 1
        }

        .owl-thumbs {
            width: 100%;
            overflow-x: scroll;
            z-index: unset !important;
            bottom: unset;
            justify-content: unset;
        }

        @media screen and (min-width:1600px) and (max-width:1920px) {
            .owl-thumbs .single-product-thumbb .top-img {
                margin-right: 5px
            }
        }

        @media screen and (min-width:1200px) and (max-width:1599px) {
            .page-title-area {
                height: 347px;
                background-color: #fff;
                position: relative
            }

            .owl-thumbs {
                overflow: auto;
                max-height: 554px;
                z-index: unset !important
            }

            .owl-thumbs .single-product-thumbb .top-img {
                margin-right: 5px
            }
        }

        @media screen and (min-width:768px) and (max-width:1199px) {
            .owl-thumbs {
                overflow-x: auto;
                max-height: 341px
            }

            .owl-thumbs .single-product-thumbb .top-img {
                margin-right: 0;
                margin-bottom: 11px
            }
        }

        @media screen and (max-width:767px) {
            .owl-thumbs {
                overflow-x: auto;
                max-height: 267px
            }

            .owl-thumbs .single-product-thumbb .top-img {
                margin-right: 0;
                margin-bottom: 11px
            }
        }

        @media screen and (max-width:514px) {
            .owl-thumbs {
                overflow-x: auto;
                max-height: 229px
            }

            .owl-thumbs .single-product-thumbb .top-img {
                margin-right: 0;
                margin-bottom: 11px
            }
        }

        @media screen and (max-width:480px) {
            .owl-thumbs {
                overflow-x: auto;
                max-height: 142px
            }

            .owl-thumbs .single-product-thumbb .top-img {
                margin-right: 0;
                margin-bottom: 11px
            }
        }

        .single-product-thumb {
            cursor: pointer
        }

        .owl-thumbs .single-product-thumbb.active .top-img {
            border: 3px solid var(--main-theme-color);
            border-radius: .25rem !important
        }

        .owl-thumbs .single-product-thumbb.active img {
            border-radius: unset !important
        }

        .page-item.active .page-link {
            color: #fff;
            background-color: var(--main-theme-color);
            border-color: var(--main-theme-color)
        }

        .page-link:hover {
            color: var(--main-theme-color);
            background-color: #e9ecef;
            border-color: var(--main-theme-color)
        }

        .page-link {
            position: relative;
            display: block;
            color: var(--main-theme-color);
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #dee2e6;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out
        }

        .owl-carousel {
            z-index: unset !important
        }

        .widget-item .sidebar-price-range p {
            font-size: 12px;
            color: #8a8a8a;
            margin-top: 15px
        }

        .widget-item .sidebar-price-range p input {
            font-size: 12px;
            color: #8a8a8a;
            border: 0;
            background: 0 0;
            display: inline-block;
            width: 200px
        }

        @media only screen and (min-width:992px) and (max-width:1199px) {
            .widget-item .sidebar-price-range p input {
                font-size: 14px
            }
        }

        .widget-item .sidebar-price-range #slider-range {
            width: 100%;
            height: 2px;
            border: none;
            bottom: 23px;
            background: #ccc
        }

        .widget-item .sidebar-price-range #slider-range .ui-widget-header {
            background: var(--main-theme-color)
        }

        .widget-item .sidebar-price-range #slider-range .ui-slider-handle {
            top: -5px;
            width: 10px;
            height: 10px;
            background: var(--main-theme-color);
            border-radius: 100%;
            -webkit-box-shadow: none;
            box-shadow: none;
            cursor: pointer;
            border: 0;
            margin-left: 0
        }

        .widget-item .sidebar-price-range #slider-range .ui-slider-handle:focus {
            outline: 0
        }

        .widget-item .sidebar-price-range #slider-range .ui-slider-handle:last-child {
            margin-left: -10px
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            background-color: var(--main-theme-color)
        }

        .nav-pills a {
            color: var(--main-theme-color)
        }

        .fa-facebook.color {
            color: #3a5895
        }

        .fa-instagram.color {
            color: #c83085
        }

        .fa-youtube.color {
            color: #f7021a
        }

        .fa-linkedin.color {
            color: #0170ad
        }

        .fa-pinterest.color {
            color: #cb2027
        }

        .fa-medium.color {
            color: #000
        }

        .fa-whatsapp.color {
            color: #005c4b
        }

        .logout-btn {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
            border-radius: 5px;
            border: 1px solid #6c757d;
            padding: 10px 18px
        }

        @media (min-width:991px) and (max-width:1199px) {
            .page-title-area {
                height: 350px;
                background-color: #fff;
                position: relative
            }
        }

        @media (min-width:992px) and (max-width:1399px) {
            .owl-thumbs {
                display: flex;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                -ms-overflow-style: -ms-autohiding-scrollbar;
                justify-content: unset !important
            }

            .owl-thumbs .owl-thumb-item {
                flex: 0 0 auto;
                width: 24%;
                margin-left: auto
            }

            .owl-thumbs .owl-thumb-item:last-child {
                margin-right: auto
            }
        }

        #paytr_taksit_tablosu {
            clear: both;
            font-size: 12px;
            max-width: 1200px;
            text-align: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        #paytr_taksit_tablosu::before {
            display: table;
            content: " "
        }

        #paytr_taksit_tablosu::after {
            content: "";
            clear: both;
            display: table
        }

        .taksit-tablosu-wrapper {
            margin: 5px;
            width: 280px;
            padding: 12px;
            cursor: default;
            text-align: center;
            display: inline-block;
            border: 1px solid #e1e1e1
        }

        .taksit-logo img {
            max-height: 28px;
            padding-bottom: 10px
        }

        .taksit-tutari-text {
            float: left;
            width: 126px;
            color: #a2a2a2;
            margin-bottom: 5px
        }

        .taksit-tutar-wrapper {
            display: inline-block;
            background-color: #f7f7f7
        }

        .taksit-tutar-wrapper:hover {
            background-color: #e8e8e8
        }

        .taksit-tutari {
            float: left;
            width: 126px;
            padding: 6px 0;
            color: #474747;
            border: 2px solid #fff
        }

        .taksit-tutari-bold {
            font-weight: 700
        }

        @media all and (max-width:600px) {
            .taksit-tablosu-wrapper {
                margin: 5px 0
            }
        }

        .fixed-maps {
            position: fixed;
            bottom: 190px;
            right: 16px;
            border: 2px solid #fff;
            cursor: pointer;
            border-radius: 50%;
            z-index: 1;
            -webkit-box-shadow: -4px 1px 7px 0 rgb(84 84 84 / 35%);
            box-shadow: -1px 1px 5px 0 rgb(84 84 84 / 35%);
        }

        /*--------------------------------
            Preloader
        -----------------------------------*/
        #preloader {
            background-color: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 9999999999999999;
        }

        #status {
            width: 100%;
            display: flex;
            align-items: center;
            height: 100%;
            justify-content: center
        }

        .owl-thumbs.carousel-indicators [data-bs-target] {
            text-indent: unset;
        }

        .carousel-dark .carousel-indicators [data-bs-target] {
            background-color: unset;
        }

        .owl-thumbs .owl-thumb-item {
            height: auto;
            min-width: 15%;
            width: 15%;
            float: left;
        }

        .common-faq-area .faq-item .accordion {
            margin: 0;
            padding: 0
        }

        .common-faq-area .faq-item .accordion li {
            position: relative;
            list-style-type: none;
            margin-bottom: 20px;
            display: block;
            -webkit-box-shadow: 0 0 20px 0 #dddddd75;
            box-shadow: 0 0 20px 0 #dddddd75
        }

        .common-faq-area .faq-item .accordion li:last-child {
            margin-bottom: 0
        }

        .common-faq-area .faq-item .accordion li .faq-head {
            color: #363636;
            font-size: 18px;
            width: 100%;
            display: block;
            cursor: pointer;
            font-weight: 600;
            padding: 18px 35px 18px 18px;
            margin-bottom: 0;
            -webkit-transition: .5s;
            transition: .5s;
            background-color: #f9f9f9;
            border: 1px solid transparent;
        }

        .common-faq-area .faq-item .accordion li .faq-head:after {
            position: absolute;
            right: -25px;
            content: "+";
            top: 5px;
            color: #363636;
            font-size: 25px;
            width: 50px;
            height: 50px;
            line-height: 40px;
            border-radius: 50%;
            border: 5px solid #f5f5f5;
            text-align: center;
            background-color: #fff
        }

        .common-faq-area .faq-item .accordion li .faq-head.active {
            color: #fff;
            background-color: var(--main-theme-color);
            border: 1px solid var(--main-theme-color)
        }

        .common-faq-area .faq-item .accordion li .faq-head.active:after {
            content: '-';
            font-size: 25px;
            color: #363636
        }

        .common-faq-area .faq-item .accordion li .faq-content {
            display: none;
            background-color: #fff;
            padding: 12px 20px 15px 20px
        }

        .common-faq-area .faq-item .accordion li .faq-content .inner-list {
            margin: 0;
            padding: 0
        }

        .common-faq-area .faq-item .accordion li .faq-content .inner-list li {
            list-style-type: none;
            display: block;
            background-color: transparent;
            -webkit-box-shadow: none;
            box-shadow: none;
            padding: 0;
            margin-bottom: 10px
        }

        .common-faq-area .faq-item .accordion li .faq-content .inner-list li:last-child {
            margin-bottom: 0
        }

        .common-faq-area .faq-item .accordion li .faq-content p {
            margin-bottom: 0
        }

        .common-faq-area .faq-item .accordion li .faq-content a {
            display: inline-block;
            color: var(--main-theme-color);
            font-weight: 600
        }

        .common-faq-area .faq-item .accordion li .faq-content a:hover {
            color: var(--main-theme-color)
        }

        .nav-pills .nav-item .nav-link.active {
            color: #fff !important
        }
    </style>





    <!-- SCRIPTS -->
    <?= $settings->analytics ?>
    <?= $settings->metrica ?>
    <?= $settings->live_support ?>
    <script>
        let base_url = "<?= asset_url() ?>";
    </script>

</head>

<body>
    <!--====== preloader Start ======-->

    <!-- Preloader Area -->
    <div id="preloader">
        <div id="status">
            <img class="img-fluid" loading="lazy" width="320" src="<?= get_picture("settings_v", $settings->logo) ?>" alt="<?= $settings->company_name ?>" />
        </div>
    </div>

    <!--====== preloader Ends ======-->