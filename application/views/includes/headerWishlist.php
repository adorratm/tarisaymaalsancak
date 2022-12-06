<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h3><?= lang("wishlist") ?> (<?= count($userWishlists) ?>)</h3>
<div class="products-cart-content">
    <?php if (!empty($userWishlists)) : ?>
        <?php foreach ($userWishlists as $items) : ?>
            <?php
            /**
             * Cart & Wishlist Products
             */
            $wheres["p.isActive"] = 1;
            $wheres["pi.isCover"] = 1;
            $wheres["p.id"] = $items;
            $wheres["p.lang"] = $lang;
            $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
            $select = "p.id,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,p.vat vat,p.vatRate vatRate,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,IFNULL(pvg.price,p.price) AS newPrice";
            $distinct = null;
            $groupBy = ["pwc.product_id"];
            $product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
            ?>
            <?php if (!empty($product)) : ?>
                <div class="products-cart d-flex align-items-center">
                    <div class="products-image">
                        <a rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$product->url}") ?>" title="<?= $product->title ?>"><img width="1920" height="1280" loading="lazy" data-src="<?= get_picture("products_v", $product->img_url) ?>" alt="<?= $product->title ?>" class="lazyload img-fluid"></a>
                    </div>
                    <div class="products-content">
                        <h3><a rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$product->url}") ?>" title="<?= $product->title ?>"><?= $product->title ?></a></h3>
                        <div class="products-price">
                            <span class="price"><?= @number_format($product->price, 2) ?> <?= $symbol ?></span>
                        </div>
                    </div>
                    <a href="<?= base_url() ?>" class="remove-btn offcanvas-cart-item-delete removeWishlistItem" data-id="<?= $items ?>" title="<?= $product->title ?>"><i class="fas fa-trash-alt"></i></a>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>
</div>

<div class="products-cart-btn mb-3">
    <a rel="dofollow" class="w-100 theme-btn-one btn-black-overlay btn_md emptyWishlist" href="<?= base_url(lang("routes_wishlist")) ?>" title="<?= lang("emptyWishlist") ?>"><i class="fa fa-trash"></i> <?= lang("emptyWishlist") ?></a>
</div>

<div class="products-cart-btn">
    <a rel="dofollow" class="w-100 theme-btn-one btn-black-overlay btn_md" href="<?= base_url(lang("routes_wishlist")) ?>" title="<?= lang("wishlist") ?>"><i class="fa fa-heart"></i> <?= lang("wishlist") ?></a>
</div>