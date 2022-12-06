<!--====== Cart Start ======-->
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $vat = 0 ?>
<?php $totalVat = 0 ?>
<?php $coupon = null ?>
<?php $mainQuantity = 0 ?>
<?php if (!empty($_SESSION["couponName"])) : ?>
    <?php $coupon = $this->general_model->get("coupons", null, ["isActive" => 1], [], ["title" => @$_SESSION["couponName"]]); ?>
<?php endif ?>

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
                            <th class="product-price"><?= lang("price") ?></th>
                            <th class="product_quantity"><?= lang("quantity") ?></th>
                            <th class="product_total"><?= lang("subTotal") ?></th>
                        </tr>
                    </thead> <!-- End Cart Table Head -->
                    <tbody>
                        <?php foreach ($this->cart->contents() as $items) : ?>
                            <?php
                            /**
                             * Cart & Wishlist Products
                             */
                            $wheres["p.isActive"] = 1;
                            $wheres["pi.isCover"] = 1;
                            $wheres["p.id"] = $items["id"];
                            $wheres["p.lang"] = $lang;
                            if (!empty($items["options"]) && !empty($items["options"]["pvgId"])) :
                                $wheres["pvg.id"] = $items["options"]["pvgId"];
                            else :
                                if (array_key_exists("pvg.id", $wheres)) :
                                    unset($wheres["pvg.id"]);
                                endif;
                            endif;
                            $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
                            $select = "p.id,pvg.id pvgId,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,p.vat vat,p.vatRate vatRate,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,IFNULL(pvg.price,p.price) AS newPrice,CAST(IFNULL(pvg.price,p.price) AS FLOAT) - (CAST(IFNULL(pvg.price,p.price) AS FLOAT)*CAST(IFNULL(pvg.discount,p.discount) AS FLOAT) / 100) AS discountedPrice";
                            $distinct = true;
                            $groupBy = ["pwc.product_id", "pvg.id"];
                            $product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
                            if (!empty($items["options"]["pvgId"])) :
                                $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $items["options"]["pvgId"], "lang" => $lang]);
                                $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                                $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                                $product_variations = [];
                                $product_variation_categories = [];
                                if (!empty($product_variation_in_group)) :
                                    foreach ($product_variation_in_group as $key => $value) :
                                        $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value, "lang" => $lang]);
                                        $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key], "lang" => $lang]);
                                        array_push($product_variation_categories, $product_variation_group->title);
                                        array_push($product_variations, $product_variation->title);
                                    endforeach;
                                endif;
                            endif;
                            ?>
                            <?php if (!empty($product)) : ?>
                                <?php ((bool)$product->vat ? $vat =  (((float)$items['price'] * (float)$items["qty"]) - ((float)$items["qty"] * (float)$product->newPrice)) : 0) ?>
                                <?php ((bool)$product->vat ? $totalVat +=  (((float)$items['price'] * (float)$items["qty"]) - ((float)$items["qty"] * (float)$product->newPrice)) : 0) ?>
                                <tr>
                                    <td class="delete text-center align-middle product_remove">
                                        <a rel="dofollow" href="javascript:void(0)" class="removeItem product-delete" data-rowid="<?= $items['rowid'] ?>" title="<?= stripslashes($items["name"]) ?>"><i class="fa fa-trash"></i></a>
                                    </td>
                                    <td class="product_thumb text-center align-middle">
                                        <a rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$product->url}") ?>" title="<?= stripslashes($items["name"]) ?>">
                                            <img width="1920" height="1280" loading="lazy" data-src="<?= get_picture("products_v", $product->img_url) ?>" alt="<?= stripslashes($items['name']); ?>" class="img-fluid lazyload" style="width: 150px;">
                                        </a>
                                    </td>
                                    <td class="product_name text-center align-middle">
                                        <a rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$product->url}") ?>" title="<?= stripslashes($items["name"]) ?>"><?= stripslashes($items["name"]) ?>
                                            <?php if (!empty($items["options"]["pvgId"])) : ?>
                                                <?php if (!empty($product_variation_categories) && !empty($product_variations)) : ?>
                                                    <?php $count = count($product_variation_categories) ?>
                                                    <?php $i = 1; ?>
                                                    (
                                                    <?php foreach ($product_variation_categories as $key => $value) : ?>
                                                        <?php if ($i < $count) : ?>
                                                            <?= $value ?> : <?= $product_variations[$key] ?>,
                                                        <?php else : ?>
                                                            <?= $value ?> : <?= $product_variations[$key] ?>
                                                        <?php endif ?>
                                                        <?php $i++ ?>
                                                    <?php endforeach ?>
                                                    )
                                                <?php endif ?>
                                            <?php endif ?>
                                        </a>
                                    </td>
                                    <td class="product-price text-center align-middle">
                                        <?= $symbol . $this->cart->format_number((empty($items["options"]["mainQuantity"]) || $items["options"]["mainQuantity"] == FALSE ? $items["price"] - ($product->isDiscount ? (float)$product->discountedPrice : (float)$product->price) : $items['price'])); ?>
                                    </td>
                                    <td class="text-center align-middle product_quantity">
                                        <label for="quantity"><?= lang("quantity") ?></label>
                                        <input id="quantity" class="cart-plus-minus updateItem quantity" name="qty" min="1" data-rowid="<?= $items['rowid'] ?>" value="<?= $items["qty"] ?>" type="number" />
                                    </td>
                                    <td class="product_total text-center align-middle">
                                        <?= $symbol . $this->cart->format_number((empty($items["options"]["mainQuantity"]) || $items["options"]["mainQuantity"] == FALSE ? $items["subtotal"] - ($product->isDiscount ? (float)$product->discountedPrice : (float)$product->price) * $items["qty"] : $items['subtotal'])); ?>
                                    </td>
                                </tr>
                                <!-- /.single product  -->
                                <?php if ((empty($items["options"]["mainQuantity"]) || $items["options"]["mainQuantity"] == FALSE)) : ?>
                                    <?php $mainQuantity += ($product->isDiscount ? (float)$product->discountedPrice : (float)$product->price) * $items["qty"] ?>
                                <?php endif; ?>
                                <?php $vat = 0 ?>
                                <?php $product_variation_categories = [] ?>
                                <?php $product_variations = [] ?>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="cart_submit text-end mt-3">
                <button class="emptyCart show-more-btn hover-btn ms-auto" aria-label="<?= lang("emptyCart") ?>"><?= lang("emptyCart") ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="coupon_code left" data-aos="fade-up" data-aos-delay="200">
            <h3><?= lang("coupon") ?></h3>
            <div class="coupon_inner">
                <p><?= lang("couponDesc") ?></p>
                <?php if (!empty($_SESSION["couponName"])) : ?>
                    <a class="show-more-btn hover-btn" href="<?= base_url(lang("routes_cart") . "?removecoupon=true") ?>" title="<?= lang("couponCodeAppliedRemove") ?>"><?= $_SESSION["couponName"] ?> <?= lang("couponCodeAppliedRemove") ?></a>
                <?php else : ?>
                    <input class="mb-2" name="coupon" placeholder="<?= lang("couponCode") ?>" type="text">
                    <button type="button" class="show-more-btn hover-btn applyCoupon" aria-label="<?= lang("applyCoupon") ?>"><?= lang("applyCoupon") ?></button>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <?php
        $totalPrice = 0;
        $subTotalPrice = 0;

        $totalPrice = (float)$this->cart->total();
        $subTotalPrice = (float)$this->cart->total() - (float)$totalVat;
        /**
         * Calculate Shipping
         */
        $shipping = ($totalPrice >= (float)$settings->shippingMinPrice ? 0 : (float)$settings->shippingPrice);
        if ($totalPrice == 0) :
            $shipping = 0;
        endif;
        $totalPrice += (float)$shipping;
        $subTotalPrice -= $mainQuantity;
        $totalPrice -= $mainQuantity;
        ?>
        <div class="coupon_code right">
            <h3><?= lang("cartTotals") ?></h3>
            <div class="coupon_inner">
                <div class="cart_subtotal">
                    <p><?= lang("subTotal") ?></p>
                    <p class="cart_amount"><?= $symbol . $this->cart->format_number($subTotalPrice); ?></p>
                </div>
                <?php if ($totalVat > 0) : ?>
                    <div class="cart_subtotal ">
                        <p><?= lang("vat") ?></p>
                        <p class="cart_amount"><?= $symbol . $this->cart->format_number($totalVat); ?></p>
                    </div>
                <?php endif ?>
                <?php if ($shipping > 0) : ?>
                    <div class="cart_subtotal ">
                        <p><?= lang("shipping") ?></p>
                        <p class="cart_amount"><?= $symbol . $this->cart->format_number($shipping); ?></p>
                    </div>
                <?php endif ?>
                <div class="cart_subtotal">
                    <p><?= lang("total") ?></p>
                    <p class="cart_amount">
                        <?php
                        if (!empty($coupon)) :
                            $totalPrice = ($totalPrice - ((float)$totalPrice * (float)$coupon->discount) / 100);
                        endif;
                        ?>
                        <?php if (!empty($coupon)) : ?>
                            <small class="text-dark-green">(<?= $coupon->title ?> = <?= $coupon->discount ?>%)</small>
                        <?php endif ?>
                        <?= $symbol . $this->cart->format_number($totalPrice); ?>
                    </p>
                    <?php $checkoutData = [
                        "cart" => $this->cart->contents(),
                        "subTotal" => (float)$subTotalPrice,
                        "vat" => (float)$totalVat,
                        "shipping" => (float)$shipping,
                        "total" => (float)$totalPrice,
                        "symbol" => $symbol
                    ];
                    if (!empty($coupon)) :
                        $checkoutData["couponName"] = $coupon->title;
                        $checkoutData["couponDiscount"] = $coupon->discount;
                    endif;
                    $this->session->set_userdata("checkout", $checkoutData);
                    ?>
                </div>
                <div class="checkout_btn">
                    <a rel="dofollow" href="<?= base_url(lang("routes_products")) ?>" title="<?= lang("continueShopping") ?>" class="show-more-btn hover-btn"><?= lang("continueShopping") ?></a>
                    <a rel="dofollow" href="<?= base_url(lang("routes_choose-address")) ?>" title="<?= lang("choosingAddress") ?>" class="show-more-btn hover-btn ms-2"><?= lang("choosingAddress") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>