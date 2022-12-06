<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= get_picture("settings_v", $settings->product_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", lang("payment")) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", lang("payment")) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ...:::: Start Cart Section:::... -->
<div class="all-product-grid bg-white">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">
                <?php if (!empty($_GET["payment"]) && $_GET["payment"] == "success") : ?>
                    <div class="mx-auto text-center">
                        <img loading="lazy" data-src="<?= asset_url("public/images/okok.gif") ?>" alt="<?= $settings->company_name ?>" class="img-fluid lazyload" width="500" height="500">
                        <p class="text-center h2"><?= lang("paymentSuccessMessage") ?></p>
                        <a class="show-more-btn hover-btn my-3" href="<?= base_url() ?>" rel="dofollow" title="<?= $settings->company_name ?>" data-toggle="tooltip" data-placement="bottom" data-title="<?= $settings->company_name ?>"><?= lang("goToHome") ?></a>
                    </div>
                <?php else : ?>
                    <div class="mx-auto text-center">
                        <img loading="lazy" data-src="<?= asset_url("public/images/Error_96px.webp") ?>" alt="<?= $settings->company_name ?>" class="img-fluid lazyload" width="500" height="500">
                        <p class="text-center h2"><?= lang("paymentErrorMessage") ?> <a rel="dofollow" title="<?= lang("paymentErrorMessageEnd") ?>" href="<?= base_url(lang("routes_choose-payment-type")) ?>"><?= lang("paymentErrorMessageEnd") ?></a></p>
                        <a class="show-more-btn hover-btn my-3" href="<?= base_url() ?>" rel="dofollow" title="<?= $settings->company_name ?>" data-toggle="tooltip" data-placement="bottom" data-title="<?= $settings->company_name ?>"><?= lang("goToHome") ?></a>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>