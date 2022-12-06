<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (!empty($data)) : ?>
    <div class="section145">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-title-tt justify-content-center align-items-center align-self-center mx-auto">
                        <div class="main-title-left text-center justify-content-center align-items-center align-self-center mx-auto">
                            <span><?= $settings->company_name ?></span>
                            <?php if (!empty($home_url)) : ?>
                                <img width="581" height="71" data-src="<?= get_picture("product_categories_v", $home_url) ?>" alt="<?= $title ?>" class="lazyload d-block img-fluid">
                            <?php else : ?>
                                <h2><?= $title ?></h2>
                            <?php endif ?>
                        </div>
                        <a rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . seo($title)) ?>" title="<?= lang("viewCategory") ?>" class="see-more-btn"><?= lang("viewCategory") ?></a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="owl-carousel featured-slider owl-theme">
                        <?php foreach ($data as $key => $value) : ?>
                            <?php if (strtotime($value->sharedAt) <= strtotime("now")) : ?>
                                <div class="item">
                                    <div class="product-item">
                                        <a href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$value->url}" . (!empty($_GET["key"]) ? "?key=" . clean($_GET["key"]) : null)) ?>" class="product-img">
                                            <img width="1000" height="1000" loading="lazy" data-src="<?= get_picture("products_v", $value->img_url) ?>" alt="<?= $value->title ?>" class="img-fluid lazyload owl-lazy">
                                            <div class="product-absolute-options">
                                                <?php if ($value->isDiscount && (int)$value->discount > 0) : ?>
                                                    <span class="offer-badge-1"><?= $value->discount ?>% <?= lang("discount") ?></span>
                                                <?php endif ?>
                                                <span class="like-icon addToWishlist" data-product-id="<?= $value->id ?>"></span>
                                            </div>
                                        </a>
                                        <div class="product-text-dt">
                                            <p>
                                                <?php if (!empty($value->category_titles)) : ?>
                                                    <?php $i = 1 ?>
                                                    <?php $count = count(explode(",", $value->category_ids)) ?>
                                                    <?php foreach (explode(",", $value->category_titles) as $k => $v) : ?>
                                                        <?php $seo_url = explode(",", $value->category_seos)[$k]; ?>
                                                        <?php if ($i < $count) : ?>
                                                            <a class="text-muted" rel="dofollow" href="<?= base_url(lang("routes_products") . "/{$seo_url}") ?>" title="<?= $v ?>"><?= $v ?></a>,
                                                        <?php else : ?>
                                                            <a class="text-muted" rel="dofollow" href="<?= base_url(lang("routes_products") . "/{$seo_url}") ?>" title="<?= $v ?>"><?= $v ?></a>
                                                        <?php endif ?>
                                                        <?php $i++ ?>
                                                    <?php endforeach ?>
                                                <?php endif ?>
                                            </p>
                                            <h4>
                                                <a class="text-secondary" rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$value->url}" . (!empty($_GET["key"]) ? "?key=" . clean($_GET["key"]) : null)) ?>" title="<?= $value->title ?>"><?= $value->title ?></a>
                                            </h4>

                                            <?php if ($value->isDiscount && (int)$value->discount > 0) : ?>
                                                <div class="product-price"><?= $formatter->formatCurrency((((float)$value->price) - (((float)$value->price * (float)$value->discount) / 100)), $currency) ?> <span><?= $formatter->formatCurrency((float)$value->price, $currency) ?></span></div>
                                            <?php else : ?>
                                                <div class="product-price"><?= $formatter->formatCurrency((((float)$value->price) - (((float)$value->price * (float)$value->discount) / 100)), $currency) ?></div>
                                            <?php endif ?>

                                            <div class="qty-cart">
                                                <span class="cart-icon mx-auto">
                                                    <a class="text-dark fs-5" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$value->url}" . (!empty($_GET["key"]) ? "?key=" . clean($_GET["key"]) : null)) ?>" title="<?= lang("viewProduct") ?>">
                                                        <?= lang("viewProduct") ?> <i class="uil uil-shopping-cart-alt"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>