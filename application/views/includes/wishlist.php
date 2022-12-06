<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="table_desc">
            <div class="table_page table-responsive">
                <table>
                    <!-- Start Cart Table Head -->
                    <thead>
                        <tr>
                            <th class="product_remove"><?= lang("delete") ?></th>
                            <th class="product_thumb"><?= lang("image") ?></th>
                            <th class="product_name"><?= lang("productName") ?></th>
                        </tr>
                    </thead> <!-- End Cart Table Head -->
                    <tbody>
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
                                <!-- Start Cart Single Item-->
                                <tr>
                                    <td class="delete product_remove align-middle text-center"><a title="<?= $product->title ?>" href="javascript:void(0)" class="product-delete removeWishlistItem" data-id="<?= $items ?>"><i class="fa fa-trash"></i></a>
                                    </td>
                                    <td class="product_thumb align-middle text-center">
                                        <a rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$product->url}") ?>" title="<?= $product->title ?>">
                                            <picture>
                                                <img loading="lazy" data-src="<?= get_picture("products_v", $product->img_url) ?>" alt="<?= $product->title ?>" class="offcanvas-cart-image img-fluid lazyload" style="width:150px">
                                            </picture>
                                        </a>
                                    </td>
                                    <td class="product_name align-middle text-center"><a rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$product->url}") ?>" title="<?= $product->title ?>"><?= $product->title ?></a></td>
                                </tr>
                                <!-- End Cart Single Item-->
                            <?php endif ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="cart_submit text-end mt-3">
                <button class="emptyWishlist show-more-btn hover-btn ms-auto" aria-label="<?= lang("emptyWishlist") ?>"><?= lang("emptyWishlist") ?></button>
            </div>
        </div>
    </div>
</div>