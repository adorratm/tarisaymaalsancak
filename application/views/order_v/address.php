<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= get_picture("settings_v", $settings->product_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", lang("chooseAddress")) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", lang("chooseAddress")) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Cart-Area -->
<div class="all-product-grid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="about-content">
                    <div class="d-flex flex-wrap align-items-center align-self-center">
                        <div class="flex-grow-1">
                            <h3 class="billing-address text-dark-green my-auto"><?= lang("deliveryAndInvoiceAddress") ?></h3>
                        </div>
                        <div class="flex-shrink-1">
                            <a class="float-end ms-auto createAddressBtn show-more-btn hover-btn" data-url="<?= asset_url("home/newAddressForm") ?>" href="javascript:void(0)" title="<?= lang("createNewAddress") ?>"><i class="fa fa-edit"></i> <?= lang("createNewAddress") ?></a>
                        </div>
                    </div>
                    <div id="addressPull2" class="mt-3">
                        <?php $this->load->view("includes/addressChooseable") ?>
                    </div>
                    <div class="text-end justify-content-end d-flex flex-wrap">
                        <a rel="dofollow" href="<?= base_url(lang("routes_products")) ?>" title="<?= lang("continueShopping") ?>" class="show-more-btn hover-btn me-1"><?= lang("continueShopping") ?></a>
                        <a rel="dofollow" href="<?= base_url(lang("routes_cart")) ?>" title="<?= lang("cart") ?>" class="show-more-btn hover-btn me-1"><?= lang("cart") ?></a>
                        <a rel="dofollow" href="<?= base_url(lang("routes_choose-payment-type")) ?>" title="<?= lang("choosingPayment") ?>" class="show-more-btn hover-btn"><?= lang("choosingPayment") ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Address Modal -->
<div id="addressModal"></div>