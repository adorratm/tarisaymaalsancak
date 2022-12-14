<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $vat = 0 ?>
<?php $totalVat = 0 ?>
<?php $mainQuantity = 0 ?>
<div class="offcanvas-header bs-canvas-header side-cart-header p-3">
    <div class="d-inline-block main-cart-title" id="offcanvasRightLabel"><?= lang("myCart") ?> <span>(<?= count($this->cart->contents()) ?>)</span></div>
    <button type="button" class="close-btn" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="uil uil-multiply"></i>
    </button>
</div>
<div class="offcanvas-body p-0">
    <div class="side-cart-items">
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
            $select = "p.id,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,p.vat vat,p.vatRate vatRate,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,IFNULL(pvg.price,p.price) AS newPrice,CAST(IFNULL(pvg.price,p.price) AS FLOAT) - (CAST(IFNULL(pvg.price,p.price) AS FLOAT)*CAST(IFNULL(pvg.discount,p.discount) AS FLOAT) / 100) AS discountedPrice";
            $distinct = null;
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
                <?php ((bool)$product->vat ? $vat =  ((float)$items['price'] * (float)$items["qty"]) - ((float)$items["qty"] * ($product->isDiscount ? (float)$product->discountedPrice : (float)$product->price)) : 0) ?>
                <?php ($product->vat ? $totalVat +=  ((float)$items['price'] * (float)$items["qty"]) - ((float)$items["qty"] * ($product->isDiscount ? (float)$product->discountedPrice : (float)$product->price)) : 0) ?>
                <div class="cart-item">
                    <div class="cart-product-img">
                        <a rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$product->url}") ?>" title="<?= stripslashes($items["name"]) ?>"><img width="1000" height="1000" loading="lazy" data-src="<?= get_picture("products_v", $product->img_url) ?>" alt="<?= $items['name']; ?>" class="img-fluid lazyload"></a>
                        <?php if ($product->discount > 0) : ?>
                            <div class="offer-badge"><?= $product->discount ?>% <?= lang("discount") ?></div>
                        <?php endif ?>
                    </div>
                    <div class="cart-text">
                        <h4>
                            <a class="text-secondary" rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$product->url}") ?>" title="<?= stripslashes($items["name"]) ?>"><?= stripslashes($items["name"]) ?>
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
                        </h4>
                        <div class="qty-group">
                            <div>
                                <?= $items['qty'] ?> x <?= $symbol . $this->cart->format_number((empty($items["options"]["mainQuantity"]) || (bool)$items["options"]["mainQuantity"] == FALSE ? $items["price"] - ($product->isDiscount ? (float)$product->discountedPrice : (float)$product->price) : $items['price'])); ?> <?= ((bool)$product->vat ? ("+" . $symbol . $this->cart->format_number($vat) . " (KDV)") : null) ?> =
                            </div>
                            <div class="cart-item-price"> <?= $symbol . $this->cart->format_number((empty($items["options"]["mainQuantity"]) || (bool)$items["options"]["mainQuantity"] == FALSE ? $items["subtotal"] - ($product->isDiscount ? (float)$product->discountedPrice : (float)$product->price) * $items["qty"] : $items['subtotal'])); ?></div>
                        </div>
                        <button type="button" class="cart-close-btn removeItem" data-rowid="<?= $items['rowid'] ?>"><i class="uil uil-multiply"></i></button>
                    </div>
                </div>
                <?php if ((empty($items["options"]["mainQuantity"]) || (bool)$items["options"]["mainQuantity"] == FALSE)) : ?>
                    <?php $mainQuantity += ($product->isDiscount ? (float)$product->discountedPrice : (float)$product->price) * $items["qty"] ?>
                <?php endif; ?>
                <?php $vat = 0 ?>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>
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
if ((empty($items["options"]["mainQuantity"]) || (bool)$items["options"]["mainQuantity"] == FALSE)) :
    $subTotalPrice -= $mainQuantity;
    $totalPrice -= $mainQuantity;
endif;
?>
<div class="offcanvas-footer">
    <div class="cart-total-dil saving-total ">
        <h4><?= lang("subTotal") ?></h4>
        <span><?= $symbol . $this->cart->format_number($subTotalPrice); ?></span>
    </div>
    <?php if ($totalVat > 0) : ?>
        <div class="cart-total-dil saving-total ">
            <h4><?= lang("vat") ?></h4>
            <span><?= $symbol . $this->cart->format_number($totalVat); ?></span>
        </div>
    <?php endif ?>
    <?php if ($shipping > 0) : ?>
        <div class="cart-total-dil saving-total ">
            <h4><?= lang("shipping") ?></h4>
            <span><?= $symbol . $this->cart->format_number($shipping); ?></span>
        </div>
    <?php endif ?>
    <div class="main-total-cart">
        <h2><?= lang("total") ?></h2>
        <span><?= $symbol . $this->cart->format_number($totalPrice); ?></span>
    </div>
    <div class="checkout-cart">
        <a rel="dofollow" href="<?= base_url(lang("routes_cart")) ?>" title="<?= lang("emptyCart") ?>" class="promo-code emptyCart"><i class="fa fa-trash"></i> <?= lang("emptyCart") ?></a>
        <a rel="dofollow" href="<?= base_url(lang("routes_cart")) ?>" title="<?= lang("cart") ?>" class="cart-checkout-btn hover-btn"><?= lang("cart") ?></a>
    </div>
</div>