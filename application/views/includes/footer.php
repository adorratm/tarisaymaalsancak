<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section id="grocery_support" class="section145">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <?php foreach ($pages as $pageKey => $pageValue) : ?>
                <?php if ($pageValue->id == 5) : ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-2 col-xxl-2 mb-3">
                        <div class="grcery_support_boxed text-center mx-auto h-100 mb-0 align-items-center align-self-center align-content-center justify-content-center">
                            <i class="fab fa-shopify fa-2x me-2"></i>
                            <a class="text-center text-dark" rel="dofollow" href="<?= base_url(lang("routes_page") . "/" . $pageValue->url) ?>" title="<?= lang("secureShopping") ?>">
                                <h4 class="fs-5"><?= lang("secureShopping") ?></h4>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($pageValue->id == 6) : ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-2 col-xxl-2 mb-3">
                        <div class="grcery_support_boxed text-center mx-auto h-100 mb-0 align-items-center align-self-center align-content-center justify-content-center">
                            <i class="fa fa-credit-card fa-2x me-2"></i>
                            <a class="text-center text-dark" rel="dofollow" href="<?= base_url(lang("routes_page") . "/" . $pageValue->url) ?>" title="<?= lang("InstallmentToCreditCard") ?>">
                                <h4 class="fs-5"><?= lang("InstallmentToCreditCard") ?></h4>
                            </a>
                        </div>
                    </div>
                <?php endif ?>
                <?php if ($pageValue->id == 4) : ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-2 col-xxl-2 mb-3">
                        <div class="grcery_support_boxed text-center mx-auto h-100 mb-0 align-items-center align-self-center align-content-center justify-content-center">
                            <i class="fa fa-truck fa-2x me-2"></i>
                            <a class="text-center text-dark" rel="dofollow" href="<?= base_url(lang("routes_page") . "/" . $pageValue->url) ?>" title="<?= lang("fastCargo") ?>">
                                <h4 class="fs-5"><?= lang("fastCargo") ?></h4>
                            </a>
                        </div>
                    </div>
                <?php endif ?>
                <?php if ($pageValue->id == 4) : ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-2 col-xxl-2 mb-3">
                        <div class="grcery_support_boxed text-center mx-auto h-100 mb-0 align-items-center align-self-center align-content-center justify-content-center">
                            <i class="fa fa-undo fa-2x me-2"></i>
                            <a class="text-center text-dark" rel="dofollow" href="<?= base_url(lang("routes_page") . "/" . $pageValue->url) ?>" title="<?= lang("moneyBackGuarantee") ?>">
                                <h4 class="fs-5"><?= lang("moneyBackGuarantee") ?></h4>
                            </a>
                        </div>
                    </div>
                <?php endif ?>
                <?php if ($pageValue->id == 7) : ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-2 col-xxl-2 mb-3">
                        <div class="grcery_support_boxed text-center mx-auto h-100 mb-0 align-items-center align-self-center align-content-center justify-content-center">
                            <i class="fa fa-face-smile fa-2x me-2"></i>
                            <a class="text-center text-dark" rel="dofollow" href="<?= base_url(lang("routes_page") . "/" . $pageValue->url) ?>" title="<?= lang("customerHappiness") ?>">
                                <h4 class="fs-5"><?= lang("customerHappiness") ?></h4>
                            </a>
                        </div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
</section>

</div>

<footer class="footer">
    <div class="footer-first-row">
        <div class="container">
            <div class="row align-items-center align-self-center align-content-center">
                <div class="col-md-6 col-sm-6">
                    <ul class="call-email-alt pt-0">
                        <li>
                            <a class="callemail" rel="dofollow" href="tel:<?= $settings->phone_1 ?>" title="<?= lang("phone") ?>"><i class="uil uil-phone-alt"></i> <?= $settings->phone_1 ?></a>
                        </li>
                        <li>
                            <a class="callemail" rel="dofollow" href="mailto:<?= $settings->email ?>" title="Email"><i class="uil uil-envelope-alt"></i> <?= $settings->email ?></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="social-links-footer">
                        <ul>
                            <?php if (!empty($settings->facebook)) : ?>
                                <li>
                                    <a rel="dofollow" href="<?= $settings->facebook ?>" title="Facebook" target="_blank">
                                        <i class='fab fa-facebook text-white'></i>
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($settings->twitter)) : ?>
                                <li>
                                    <a rel="dofollow" href="<?= $settings->twitter ?>" title="Twitter" target="_blank">
                                        <i class='fab fa-twitter text-white'></i>
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($settings->instagram)) : ?>
                                <li>
                                    <a rel="dofollow" href="<?= $settings->instagram ?>" title="Instagram" target="_blank">
                                        <i class='fab fa-instagram text-white'></i>
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($settings->linkedin)) : ?>
                                <li>
                                    <a rel="dofollow" href="<?= $settings->linkedin ?>" title="Linkedin" target="_blank">
                                        <i class='fab fa-linkedin text-white'></i>
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($settings->youtube)) : ?>
                                <li>
                                    <a rel="dofollow" href="<?= $settings->youtube ?>" title="Youtube" target="_blank">
                                        <i class='fab fa-youtube text-white'></i>
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($settings->medium)) : ?>
                                <li>
                                    <a rel="dofollow" href="<?= $settings->medium ?>" title="Medium" target="_blank">
                                        <i class='fab fa-medium text-white'></i>
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($settings->pinterest)) : ?>
                                <li>
                                    <a rel="dofollow" href="<?= $settings->pinterest ?>" title="Pinterest" target="_blank">
                                        <i class='fab fa-pinterest text-white'></i>
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($settings->phone_3)) : ?>
                                <li>
                                    <a rel="dofollow" href="https://api.whatsapp.com/send?phone=<?= $settings->phone_3 ?>&amp;text=<?= urlencode($settings->company_name) ?>." target="_blank" title="WhatsApp"><i class="fab fa-whatsapp text-white"></i></a>
                                </li>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-second-row">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="second-row-item">
                        <ul>
                            <li>
                                <a rel="dofollow" href="<?= base_url() ?>" title="<?= $settings->company_name ?>">
                                    <picture>
                                        <img loading="lazy" width="250" height="162" data-src="<?= get_picture("settings_v", $settings->logo) ?>" alt="<?= $settings->company_name ?>" class="lazyload img-fluid">
                                    </picture>
                                </a>
                            </li>
                            <li>
                                <a rel="nofollow" href="https://maps.google.com/maps?q=<?= @urlencode(clean($settings->address)) ?>" target="_blank" title="<?= lang("address") ?>"><i class="fa fa-map-marker-alt me-2"></i> <?= clean($settings->address) ?></a>
                            </li>
                            <li>
                                <a rel="dofollow" title="<?= lang("phone") ?>" href="tel:<?= $settings->phone_1 ?>"><i class="fa fa-phone-volume me-2"></i> <?= $settings->phone_1 ?></a>
                            </li>
                            <?php if (!empty($settings->phone_2)) : ?>
                                <li>
                                    <a rel="dofollow" title="<?= lang("phone") ?>" href="tel:<?= $settings->phone_2 ?>"><i class="fa fa-phone-volume me-2"></i> <?= $settings->phone_2 ?></a>
                                </li>
                            <?php endif ?>
                            <li>
                                <a rel="dofollow" title="Whatsapp" target="_blank" href="https://api.whatsapp.com/send?phone=<?= $settings->phone_3 ?>&amp;text=<?= urlencode($settings->company_name) ?>."><i class="fab fa-whatsapp me-2"></i> <?= $settings->phone_3 ?></a>
                            </li>
                            <?php if (!empty($settings->fax_1)) : ?>
                                <li>
                                    <a rel="dofollow" title="<?= lang("fax") ?>" href="tel:<?= $settings->fax_1 ?>"><i class="fa fa-fax me-2"></i> <?= $settings->fax_1 ?></a>
                                </li>
                            <?php endif ?>
                            <li>
                                <a rel="dofollow" title="Email" href="mailto:<?= $settings->email ?>"><i class="fa fa-envelope-open me-2"></i> <?= $settings->email ?></a>
                            </li>
                            <li>
                                <a href="<?= base_url() ?>" title="<?= $settings->company_name ?>" rel="dofollow">
                                    <img width="1200" height="133" loading="lazy" class="lazyload img-fluid" data-src="<?= asset_url("public/images/test.webp") ?>" alt="<?= $settings->company_name ?>">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="second-row-item">
                        <h4><?= lang("menus") ?></h4>
                        <?= $footer_menus ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="second-row-item">
                        <h4><?= lang("productCategories") ?></h4>
                        <ul>
                            <?php if (!empty($menuCategories)) : ?>
                                <?php foreach ($menuCategories as $key => $value) : ?>
                                    <?php if ($value->top_id == 0) : ?>
                                        <li><a rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . $value->seo_url) ?>" title="<?= $value->title ?>"><?= $value->title ?></a></li>
                                    <?php endif ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-last-row">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-bottom-group">
                        <div class="copyright-text ms-0">
                            © <?= date("Y") ?> <a class="text-white fw-bolder" rel="dofollow" href="<?= base_url() ?>" title="<?= $settings->company_name ?>"><?= $settings->company_name ?></a>. <?= lang("allRightsReserved") ?>
                        </div>
                        <div class="footer-bottom-links ms-auto">
                            <ul>
                                <li><a rel="dofollow" href="https://github.com/adorratm" title="Powered By Adorra™">Powered By Adorra™</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php if (empty($_COOKIE["cookieSection"]) || $_COOKIE["cookieSection"] == false) : ?>
    <!-- cookie bar start -->
    <div class="cookie-bar">
        <div class="d-flex flex-column mb-3 mb-lg-0">
            <h4 class="text-white mb-2"><?= lang("cookieTitle") ?></h4>
            <p><?= lang("cookieDesc") ?></p>
        </div>

        <a href="<?= base_url() ?>" onclick="setCookie('cookieSection',true,365)" class="btn btn-primary text-white"><?= lang("closeButton") ?></a>
    </div>
    <!-- cookie bar end -->
<?php endif ?>


<!-- Jquery -->
<script defer src="<?= asset_url("public/js/jquery.min.js") ?>"></script>
<!-- #Jquery -->
<!--FOOTER END-->
<?php if (!empty($settings->address)) : ?>
    <a rel="dofollow" class="fixed-maps bg-primary map-address" href="<?= base_url() ?>" data-destination="<?= $settings->address ?>" title="<?= lang("getDirections") ?>" data-toggle="tooltip" data-title="<?= lang("getDirections") ?>" data-placement="left"><i class="fa fa-map-marker-alt"></i></a>
<?php endif ?>
<?php if (!empty($settings->phone_1)) : ?>
    <a rel="dofollow" class="fixed-phone bg-danger" href="tel:<?= $settings->phone_1 ?>" data-toggle="tooltip" data-title="<?= lang("phone_1") ?>" data-placement="left" title="<?= lang("phone_1") ?>"><i class="fa fa-phone"></i></a>
<?php endif ?>
<?php if (!empty($settings->phone_3)) : ?>
    <a rel="dofollow" class="fixed-whatsapp bg-success" href="https://api.whatsapp.com/send?phone=<?= $settings->phone_3 ?>&amp;text=<?= urlencode($settings->company_name) ?>." target="_blank" title="WhatsApp" data-toggle="tooltip" data-title="WhatsApp" data-placement="left"><i class="fab fa-whatsapp"></i></a>
<?php endif ?>

<!--layout end-->
<!-- SCRIPTS -->
<!-- Lazysizes -->
<script async defer src="<?= asset_url("public/js/lazysizes.min.js") ?>"></script>
<!-- #Lazysizes -->

<!-- iziToast -->
<script defer src="<?= asset_url("public/js/iziToast.min.js") ?>"></script>
<!-- #iziToast -->

<!-- iziModal -->
<script defer src="<?= asset_url("public/js/iziModal.min.js") ?>"></script>
<!-- #iziModal -->

<script defer src="<?= asset_url("public/js/maskedinput.min.js") ?>"></script>

<!-- Site Scripts -->


<script defer src="<?= asset_url("public/js/bootstrap.bundle.min.js") ?>"></script>
<script defer src="<?= asset_url("public/js/bootstrap-select.min.js") ?>"></script>
<script defer src="<?= asset_url("public/js/owl.carousel.js") ?>"></script>
<script async defer src="<?= asset_url("public/js/sweetalert.min.js") ?>"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/js/lightgallery-all.min.js"></script>
<script defer src="<?= asset_url("public/js/custom.js") ?>"></script>
<script defer src="<?= asset_url("public/js/offset_overlay.js") ?>"></script>
<script defer src="<?= asset_url("public/js/night-mode.js") ?>"></script>

<script defer src="<?= asset_url("public/js/app.js") ?>"></script>
<!-- #Site Scripts -->

<!-- SCRIPTS -->
<script>
    window.addEventListener('DOMContentLoaded', function() {
        $(document).on("click", ".map-address", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let dest = $(this).data("destination");
            if (navigator.geolocation) {
                if ((navigator.platform.indexOf("iPhone") != -1) ||
                    (navigator.platform.indexOf("iPad") != -1) ||
                    (navigator.platform.indexOf("iPod") != -1))
                    window.open("comgooglemapsurl://maps.google.com/maps/dir/?api=1&destination=" + dest + "&travelmode=driving");
                else {
                    window.open("https://www.google.com/maps/dir/?api=1&destination=" + dest + "&travelmode=driving");
                }
            } else {
                iziToast.show({
                    type: "error",
                    title: "<?= lang("error") ?>",
                    message: "<?= lang("allowGeoLocation") ?>",
                    position: "topCenter"
                });
            }
        });
        $(document).ready(function(data) {
            data.mask.definitions['~'] = '[+-]';
            $('input[type="tel"]').mask('0999 999 99 99');
        });
        let element = $(".offcanvas-add-cart .item-count");
        /**
         * Add To Cart
         */
        $(document).on("click", ".addToCart", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let $this = $(this);
            $this.attr("disabled", "disabled");
            let productId = $this.data("product-id");
            let quantity = $this.data("quantity");
            let pvgId = $this.data("pvgId");
            let partArray = $this.data("part-array");
            $.post('<?= base_url(lang("routes_add-to-cart")) ?>', {
                "id": productId,
                "quantity": quantity,
                "pvgId": pvgId,
                "partArray": partArray,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                if (response.success) {
                    iziToast.success({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                    $(".offcanvas-add-cart-wrapper").load('<?= asset_url("home/headerCart") ?>');
                    $(".myCart").load('<?= asset_url("home/cart") ?>');
                    element.load('<?= asset_url("home/headerCart/count") ?>');
                } else {
                    iziToast.error({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                }
                $this.removeAttr("disabled");
            }, 'JSON');
        });
        /**
         * Update Cart
         */
        $(document).on("change", ".updateItem", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let $this = $(this);
            $this.attr("disabled", "disabled");
            let rowId = $this.data("rowid");
            let qty = $this.val();
            $.post('<?= base_url(lang("routes_update-cart")) ?>', {
                "rowid": rowId,
                "qty": qty,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                if (response.success) {
                    iziToast.success({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                    $(".offcanvas-add-cart-wrapper").load('<?= asset_url("home/headerCart") ?>');
                    $(".myCart").load('<?= asset_url("home/cart") ?>');
                    element.load('<?= asset_url("home/headerCart/count") ?>');
                } else {
                    iziToast.error({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                }
                $this.removeAttr("disabled");
            }, 'JSON');
        });
        /**
         * Remove From Cart
         */
        $(document).on("click", ".removeItem", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let $this = $(this);
            $this.attr("disabled", "disabled");
            let rowId = $this.data("rowid");
            $.post('<?= base_url(lang("routes_remove-from-cart")) ?>', {
                "rowid": rowId,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                if (response.success) {
                    iziToast.success({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                    $(".offcanvas-add-cart-wrapper").load('<?= asset_url("home/headerCart") ?>');
                    $(".myCart").load('<?= asset_url("home/cart") ?>');
                    element.load('<?= asset_url("home/headerCart/count") ?>');
                } else {
                    iziToast.error({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                }
                $this.removeAttr("disabled");
            }, 'JSON');
        });
        /**
         * Empty Cart
         */
        $(document).on("click", ".emptyCart", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let $this = $(this);
            $this.attr("disabled", "disabled");
            $.post('<?= base_url(lang("routes_empty-cart")) ?>', {
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                if (response.success) {
                    iziToast.success({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                    $(".offcanvas-add-cart-wrapper").load('<?= asset_url("home/headerCart") ?>');
                    $(".myCart").load('<?= asset_url("home/cart") ?>');
                    element.load('<?= asset_url("home/headerCart/count") ?>');
                } else {
                    iziToast.error({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                }
                $this.removeAttr("disabled");
            }, 'JSON');
        });
        /**
         * Add To Wishlist
         */
        $(document).on("click", ".addToWishlist", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let $this = $(this);
            $this.attr("disabled", "disabled");
            let productId = $this.data("product-id");
            if ($this.hasClass("text-danger")) {
                $.post('<?= base_url(lang("routes_remove-from-wishlist")) ?>', {
                    "id": productId,
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                }, function(response) {
                    if (response.success) {
                        iziToast.success({
                            title: response.title,
                            message: response.msg,
                            position: "topCenter",
                        });
                        $(".offcanvas-wishlist-wrapper").load('<?= asset_url("home/headerWishlist") ?>');
                        $this.removeClass("text-danger");
                    } else {
                        iziToast.error({
                            title: response.title,
                            message: response.msg,
                            position: "topCenter",
                        });
                    }
                    $this.removeAttr("disabled");
                }, 'JSON');
            } else {
                $.post('<?= base_url(lang("routes_add-to-wishlist")) ?>', {
                    "id": productId,
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                }, function(response) {
                    if (response.success) {
                        iziToast.success({
                            title: response.title,
                            message: response.msg,
                            position: "topCenter",
                        });
                        $(".offcanvas-wishlist-wrapper").load('<?= asset_url("home/headerWishlist") ?>');
                        $this.addClass("text-danger");
                    } else {
                        iziToast.error({
                            title: response.title,
                            message: response.msg,
                            position: "topCenter",
                        });
                    }
                    $this.removeAttr("disabled");
                }, 'JSON');
            }

        });
        /**
         * Remove From Wishlist
         */
        $(document).on("click", ".removeWishlistItem", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let $this = $(this);
            $this.attr("disabled", "disabled");
            let id = $this.data("id");
            $.post('<?= base_url(lang("routes_remove-from-wishlist")) ?>', {
                "id": id,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                if (response.success) {
                    iziToast.success({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                    $(".offcanvas-wishlist-wrapper").load('<?= asset_url("home/headerWishlist") ?>');
                    $(".myWishlist").load('<?= asset_url("home/wishlist/render") ?>');;
                } else {
                    iziToast.error({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                }
                $this.removeAttr("disabled");
            }, 'JSON');
        });
        /**
         * Empty Wishlist
         */
        $(document).on("click", ".emptyWishlist", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let $this = $(this);
            $this.attr("disabled", "disabled");
            $.post('<?= base_url(lang("routes_empty-wishlist")) ?>', {
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                if (response.success) {
                    iziToast.success({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                    $(".offcanvas-wishlist-wrapper").load('<?= asset_url("home/headerWishlist") ?>');
                    $(".myWishlist").load('<?= asset_url("home/wishlist/render") ?>');
                } else {
                    iziToast.error({
                        title: response.title,
                        message: response.msg,
                        position: "topCenter",
                    });
                }
                $this.removeAttr("disabled");
            }, 'JSON');
        });

    });
</script>
<?php if ($this->uri->segment(2) === lang("routes_choose-address")) : ?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            $("input[name='address']").each(function() {
                if ($(this).is(":checked")) {
                    $(this).change();
                }
            });
        });

        function changeSelectedAddress($this) {
            let selected = $this.val();
            let url = "<?= asset_url("home/changeSelectedAddress/") ?>" + selected;
            let formData = new FormData();
            formData.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
            createAjax(url, formData, function() {});
        }
    </script>
<?php endif ?>
<?php if ($this->uri->segment(2) === lang("routes_cart")) : ?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            $(document).on("click", ".applyCoupon", function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let $this = $(this);
                $this.attr("disabled", "disabled");
                $.post('<?= asset_url("home/applycoupon") ?>', {
                    'coupon': $("input[name='coupon']").val(),
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                }, function(response) {
                    if (response.success) {
                        iziToast.success({
                            title: response.title,
                            message: response.msg,
                            position: "topCenter",
                        });
                        setTimeout(function() {
                            window.location.href = "<?= base_url(lang("routes_cart")) ?>"
                        }, 2000);
                    } else {
                        iziToast.error({
                            title: response.title,
                            message: response.msg,
                            position: "topCenter",
                        });
                    }
                    $this.removeAttr("disabled");
                }, 'JSON');
            });
        });
    </script>
<?php endif ?>
<?php if ($this->uri->segment(2) === lang("routes_account") || $this->uri->segment(2) === lang("routes_choose-address")) : ?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            $(document).on("click", ".createAddressBtn", function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let url = $(this).data("url");
                $('#addressModal').iziModal('destroy');
                createModal("#addressModal", "<?= lang("createNewAddress") ?>", "<?= lang("createNewAddress") ?>", 600, true, "20px", 0, "var(--main-theme-color)", "#fff", 1040, function() {
                    $.post(url, {
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                    }, function(response) {
                        $("#addressModal .iziModal-content").html(response);
                    });
                });
                openModal("#addressModal");
            });

            $(document).on("click", ".editAddressBtn", function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let url = $(this).data("url");
                $('#addressModal').iziModal('destroy');
                createModal("#addressModal", "<?= lang("editAddress") ?>", "<?= lang("editAddress") ?>", 600, true, "20px", 0, "var(--main-theme-color)", "#fff", 1040, function() {
                    $.post(url, {
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                    }, function(response) {
                        $("#addressModal .iziModal-content").html(response);
                    });
                });
                openModal("#addressModal");
            });

            $(document).on('click', '.deleteAddress', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let url = $(this).data("url");
                swal.fire({
                    title: '<?= lang("areYouSure") ?>',
                    text: "<?= lang("cannotGetBack") ?>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '<?= lang("yesDeleteIt") ?>',
                    cancelButtonText: "<?= lang("noCancelIt") ?>"
                }).then(function(result) {
                    if (result.value) {
                        let formData = new FormData();
                        formData.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
                        createAjax(url, formData, function() {
                            $("#addressPull").load("<?= asset_url("home/get_address") ?>");
                            $("#addressPull2").load("<?= asset_url("home/get_address/chooseable") ?>");
                        });
                    }
                });
            });
        });
    </script>
<?php endif ?>
<?php if ($this->uri->segment(2) === lang("routes_choose-payment-type")) : ?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            $(document).on("click", ".payWithBankTransfer", function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let merchant_oid = $(this).data("merchant-oid");
                let formData = new FormData();
                formData.append("merchant_oid", merchant_oid);
                formData.append("payWithBankTransfer", true);
                formData.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
                createAjax("<?= base_url(lang("routes_payment")) ?>", formData, function(response) {
                    window.location.href = response.url;
                });
            });
        });
    </script>
<?php endif ?>
<?php $this->load->view("includes/alert") ?>
</body>

</html>