<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= get_picture("settings_v", $settings->product_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", lang("choosePayment")) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", lang("choosePayment")) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ...:::: Start Cart Section:::... -->
<div class="all-product-grid">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-12">
                <ul class="nav nav-pills d-flex flex-wrap w-100 justify-content-center align-items-center align-middle text-center" id="myTab" role="tablist">
                    <?php if (!empty($banks)) : ?>
                        <li class="nav-item rounded-0 border-bottom flex-grow-1" role="presentation">
                            <a class="nav-link rounded-0 active" id="<?= seo(lang("payWithBankTransfer")) ?>-tab" data-bs-toggle="tab" href="#<?= seo(lang("payWithBankTransfer")) ?>" role="tab" aria-controls="<?= seo(lang("payWithBankTransfer")) ?>" aria-selected="true" title="<?= lang("payWithBankTransfer") ?>"><?= lang("payWithBankTransfer") ?></a>
                        </li>
                    <?php endif ?>
                    <li class="nav-item rounded-0 border-bottom flex-grow-1" role="presentation">
                        <a class="nav-link rounded-0 <?= (empty($banks) ? "active" : null) ?>" title="<?= lang("payWithCreditCart") ?>" id="<?= seo(lang("payWithCreditCart")) ?>-tab" data-bs-toggle="tab" href="#<?= seo(lang("payWithCreditCart")) ?>" role="tab" aria-controls="<?= seo(lang("payWithCreditCart")) ?>" aria-selected="false"><?= lang("payWithCreditCart") ?></a>
                    </li>
                </ul>
                <div class="tab-content p-3 border" id="myTabContent">
                    <?php if (!empty($banks)) : ?>
                        <div class="tab-pane fade show active" id="<?= seo(lang("payWithBankTransfer")) ?>" role="tabpanel" aria-labelledby="<?= seo(lang("payWithBankTransfer")) ?>-tab">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php foreach ($banks as $key => $value) : ?>
                                            <div class="row mb-3">
                                                <div class="col-lg-6">
                                                    <img loading="lazy" data-src="<?= get_picture("banks_v", $value->img_url) ?>" class="img-fluid lazyload mb-3" style="object-fit:scale-down" alt="<?= $value->title ?>" width="500" height="500">
                                                </div>
                                                <div class="col-lg-6 py-auto my-auto">
                                                    <h5 class="mt-0"><?= $value->title ?></h5>
                                                    <p><?= $value->content ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>

                                    <div class="col-lg-12">
                                        <a href="javascript:void(0)" class="show-more-btn hover-btn text-center w-100 justify-content-center rounded-0 mx-auto payWithBankTransfer" data-merchant-oid="<?= $merchant_oid ?>" title="<?= lang("payWithBankTransfer") ?>"><?= lang("payWithBankTransfer") ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                    <div class="tab-pane p-3 border fade <?= (empty($banks) ? "show active" : null) ?>" id="<?= seo(lang("payWithCreditCart")) ?>" role="tabpanel" aria-labelledby="<?= seo(lang("payWithCreditCart")) ?>-tab">
                        <iframe loading="lazy" class="lazyload" data-src="https://www.paytr.com/odeme/guvenli/<?= $token; ?>" frameborder="0" style="min-height: 700px; width: 100%;"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-12 text-center text-lg-end">
                <div class="checkout_btn d-flex flex-wrap justify-content-end mt-2">
                    <a rel="dofollow" title="<?= lang("continueShopping") ?>" href="<?= base_url(lang("routes_products")) ?>" class="show-more-btn hover-btn me-1"><?= lang("continueShopping") ?></a>
                    <a rel="dofollow" title="<?= lang("cart") ?>" href="<?= base_url(lang("routes_cart")) ?>" class="show-more-btn hover-btn me-1"><?= lang("cart") ?></a>
                    <a rel="dofollow" title="<?= lang("chooseAddress") ?>" href="<?= base_url(lang("routes_choose-address")) ?>" class="show-more-btn hover-btn"><?= lang("chooseAddress") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ...:::: End Cart Section:::... -->