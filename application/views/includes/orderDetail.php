<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mb-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th class="text-center align-middle" colspan="2"><strong><?= strto("lower|ucwords", lang("userInformation")) ?></strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center align-middle">
                            <?= lang("nameSurname") ?>:
                        </td>
                        <td class="text-center align-middle">
                            <strong><?= !empty($item->full_name) ? $item->full_name : null; ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle">
                            Email:
                        </td>
                        <td class="text-center align-middle">
                            <strong><?= !empty($item->email) ? $item->email : null; ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle">
                            <?= lang("phone") ?>:
                        </td>
                        <td class="text-center align-middle">
                            <strong><?= !empty($item->phone) ? $item->phone : null; ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <table class="table table-hover table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center align-middle"><strong><?= strto("lower|ucwords", lang("deliveryAndBillingAddress")) ?></strong></th>
                    </tr>
                </thead>
                <tr>
                    <td class="text-center align-middle">
                        <?= lang("addressTitle") ?>:
                    </td>
                    <td class="text-center align-middle">
                        <strong><?= !empty($item->address_title) ? $item->address_title : null; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center align-middle">
                        <?= lang("addressCountry") ?>:
                    </td>
                    <td class="text-center align-middle">
                        <strong><?= !empty($item->country) ? $item->country : null; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center align-middle">
                        <?= lang("addressCity") ?>:
                    </td>
                    <td class="text-center align-middle">
                        <strong><?= !empty($item->city) ? $item->city : null; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center align-middle">
                        <?= lang("addressDistrict") ?>:
                    </td>
                    <td class="text-center align-middle">
                        <strong><?= !empty($item->district) ? $item->district : null; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center align-middle">
                        <?= lang("addressNeighborhood") ?>:
                    </td>
                    <td class="text-center align-middle">
                        <strong><?= !empty($item->neighborhood) ? $item->neighborhood : null; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center align-middle">
                        <?= lang("addressQuarter") ?>:
                    </td>
                    <td class="text-center align-middle">
                        <strong><?= !empty($item->quarter) ? $item->quarter : null; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td class="text-center align-middle">
                        <?= lang("address") ?>:
                    </td>
                    <td class="text-center align-middle">
                        <strong><?= !empty($item->address) ? $item->address : null; ?></strong>
                    </td>
                </tr>
                </tbody>
            </table>
            <hr>
            <?php if (!empty($order_data)) : ?>
                <table class="table table-bordered table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th class="font-weight-bold text-center align-middle" colspan="5"><?= strto("lower|ucwords", lang("orderDetail")) ?></th>
                        </tr>
                        <tr>
                            <th class='font-weight-bold text-center align-middle'><?= lang("image") ?></th>
                            <th class='font-weight-bold text-center align-middle'><?= lang("productName") ?></th>
                            <th class='font-weight-bold text-center align-middle'><?= lang("quantity") ?></th>
                            <th class='font-weight-bold text-center align-middle'><?= lang("price") ?></th>
                            <th class='font-weight-bold text-center align-middle'><?= lang("subTotal") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_data["cart"] as $cart_key => $cart_value) : ?>
                            <?php
                            /**
                             * Cart & Wishlist Products
                             */
                            $wheres["p.isActive"] = 1;
                            $wheres["pi.isCover"] = 1;
                            $wheres["p.id"] = $cart_value["id"];
                            $wheres["p.lang"] = $lang;
                            if (!empty($cart_value["options"]) && !empty($cart_value["options"]["pvgId"])) :
                                $wheres["pvg.id"] = $cart_value["options"]["pvgId"];
                            else :
                                if (array_key_exists("pvg.id", $wheres)) :
                                    unset($wheres["pvg.id"]);
                                endif;
                            endif;
                            $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
                            $select = "p.id,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,p.vat vat,p.vatRate vatRate,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,IFNULL(pvg.price,p.price) AS newPrice";
                            $distinct = true;
                            $groupBy = ["pwc.product_id", "pvg.id"];
                            $product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
                            if (!empty($cart_value["options"]["pvgId"])) :
                                $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $cart_value["options"]["pvgId"], "lang" => $lang]);
                                if (!empty($product_variation_group)) :
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
                            endif;
                            ?>
                            <?php if (!empty($product)) : ?>
                                <tr>
                                    <td class='text-center align-middle'>
                                        <img loading="lazy" class='img-fluid lazyload' data-src='<?= get_picture("products_v", $product->img_url) ?>' style='max-width:125px;max-height:125px;object-fit:scale-down' width="125" height="125">
                                    </td>
                                    <td class='text-center align-middle'><?= stripslashes($cart_value["name"]) ?>
                                        <?php if (!empty($cart_value["options"]["pvgId"])) : ?>
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
                                    </td>
                                    <td class='text-center align-middle'><?= $cart_value["qty"] ?> x</td>
                                    <td class='text-center align-middle'>
                                        <?php if (!empty($cart_value["options"]["partArray"]) && (empty($cart_value["options"]["mainQuantity"]) || $cart_value["options"]["mainQuantity"] == FALSE)) : ?>
                                            <?php $partTotalPrice = 0 ?>
                                            <?php foreach ($cart_value["options"]["partArray"] as $partKey => $partValue) : ?>
                                                <?php $partTotalPrice += $partValue["price"] ?>
                                            <?php endforeach ?>
                                            <?= $partTotalPrice ?>
                                        <?php else : ?>
                                            <?= $cart_value["price"] ?>
                                        <?php endif ?>
                                        <?= $item->symbol ?>
                                    </td>
                                    <td class='text-center align-middle'>
                                        <?php if (!empty($cart_value["options"]["partArray"]) && (empty($cart_value["options"]["mainQuantity"]) || $cart_value["options"]["mainQuantity"] == FALSE)) : ?>
                                            <?php $partTotalPrice = 0 ?>
                                            <?php foreach ($cart_value["options"]["partArray"] as $partKey => $partValue) : ?>
                                                <?php $partTotalPrice += $partValue["price"] ?>
                                            <?php endforeach ?>
                                            <?= $cart_value["qty"] * $partTotalPrice ?>
                                        <?php else : ?>
                                            <?= $cart_value["subtotal"] ?>
                                        <?php endif ?>
                                        <?= $item->symbol ?>
                                    </td>
                                </tr>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><?= lang("subTotal") ?> : <span class="float-right"> <?= $order_data["subTotal"] ?> <?= $item->symbol ?></span></td>
                        </tr>
                        <?php if ((float)$order_data["vat"] > 0) : ?>
                            <tr>
                                <td colspan="5" class="text-right"><?= lang("vat") ?> : <span class="float-right"> <?= $order_data["vat"] ?> <?= $item->symbol ?></span></td>
                            </tr>
                        <?php endif ?>
                        <?php if ((float)$order_data["shipping"] > 0) : ?>
                            <tr>
                                <td colspan="5" class="text-right"><?= lang("shipping") ?> : <span class="float-right"> <?= $order_data["shipping"] ?> <?= $item->symbol ?></span></td>
                            </tr>
                        <?php endif ?>
                        <tr>
                            <td colspan="5" class="text-right"><b><?= lang("total") ?></b> : <span class="float-right"> <?= $order_data["total"] ?> <?= $item->symbol ?></span></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif ?>
            <?php if (!empty($item->shippingCode)) : ?>
                <table class="table table-bordered table-striped  w-100">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center align-middle"><?= lang("shippingInformation") ?></th>
                            <th class="text-center align-middle"><?= lang("actions") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= lang("shippingCompany") ?></td>
                            <td>
                                <select name="shippingCompany" id="shippingCompany" class="form-control" disabled required>
                                    <option value="MNG" <?= ($item->shippingCompany == "MNG" ? "selected" : "") ?>>MNG <?= lang("cargo") ?></option>
                                    <option value="ARAS" <?= ($item->shippingCompany == "ARAS" ? "selected" : "") ?>>ARAS <?= lang("cargo") ?></option>
                                    <option value="SÜRAT" <?= ($item->shippingCompany == "SÜRAT" ? "selected" : "") ?>>SÜRAT <?= lang("cargo") ?></option>
                                    <option value="YURTİÇİ" <?= ($item->shippingCompany == "YURTİÇİ" ? "selected" : "") ?>>YURTİÇİ <?= lang("cargo") ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= lang("shippingCode") ?>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="<?= (!empty($item->shippingCode) ? $item->shippingCode : "") ?>" name="shippingCode" disabled required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= lang("shippingLink") ?>
                            </td>
                            <td>
                                <?php switch ($item->shippingCompany):
                                    case "ARAS": ?>
                                        <a class="theme-btn-one btn-black-overlay btn_sm d-flex text-center justify-content-center" title="<?= lang("shippingLink") ?>" target="_blank" href="https://kargotakip.araskargo.com.tr/mainpage.aspx?code=<?= (!empty($item->shippingCode) ? $item->shippingCode : null) ?>"><?= lang("shippingLink") ?></a>
                                        <?php break ?>
                                    <?php
                                    case "MNG": ?>
                                        <a class="theme-btn-one btn-black-overlay btn_sm d-flex text-center justify-content-center" title="<?= lang("shippingLink") ?>" target="_blank" href="https://service.mngkargo.com.tr/iactive/popup/KargoTakip/link1.asp?k=<?= (!empty($item->shippingCode) ? $item->shippingCode : null) ?>"><?= lang("shippingLink") ?></a>
                                        <?php break ?>
                                    <?php
                                    case "YURTİÇİ": ?>
                                        <a class="theme-btn-one btn-black-overlay btn_sm d-flex text-center justify-content-center" title="<?= lang("shippingLink") ?>" target="_blank" href="https://selfservis.yurticikargo.com/reports/SSWDocumentDetail.aspx?DocId=<?= (!empty($item->shippingCode) ? $item->shippingCode : null) ?>"><?= lang("shippingLink") ?></a>
                                        <?php break ?>
                                    <?php
                                    case "SÜRAT": ?>
                                        <a class="theme-btn-one btn-black-overlay btn_sm d-flex text-center justify-content-center" title="<?= lang("shippingLink") ?>" target="_blank" href="https://suratkargo.com.tr/KargoTakip/?kargotakipno=<?= (!empty($item->shippingCode) ? $item->shippingCode : null) ?>"><?= lang("shippingLink") ?></a>
                                        <?php break ?>
                                    <?php
                                    default: ?>
                                        <a class="theme-btn-one btn-black-overlay btn_sm d-flex text-center justify-content-center" title="<?= lang("shippingLink") ?>" target="_blank" href="https://service.mngkargo.com.tr/iactive/popup/KargoTakip/link1.asp?k=<?= (!empty($item->shippingCode) ? $item->shippingCode : null) ?>"><?= lang("shippingLink") ?></a>
                                        <?php break ?>
                                <?php endswitch ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endif ?>
        </div>
    </div>
    <div class="form-group mb-3">
        <label><?= lang("orderStatus") ?></label>
        <select class="form-control form-control-sm rounded-0" name="status" id="status" disabled>
            <option value="Ödeme Bekleniyor." <?= ($item->status == "Ödeme Bekleniyor." ? "selected" : null) ?>><?= lang("Ödeme Bekleniyor.") ?></option>
            <option value="Ödeme Alındı." <?= ($item->status == "Ödeme Alındı." ? "selected" : null) ?>><?= lang("Ödeme Alındı.") ?></option>
            <option value="Ödeme Onayı Bekleniyor." <?= ($item->status == "Ödeme Onayı Bekleniyor." ? "selected" : null) ?>><?= lang("Ödeme Onayı Bekleniyor.") ?></option>
            <option value="Ödeme Onaylandı." <?= ($item->status == "Ödeme Onaylandı." ? "selected" : null) ?>><?= lang("Ödeme Onaylandı.") ?></option>
            <option value="Hazırlanıyor." <?= ($item->status == "Hazırlanıyor." ? "selected" : null) ?>><?= lang("Hazırlanıyor.") ?></option>
            <option value="Kargoya Verildi." <?= ($item->status == "Kargoya Verildi." ? "selected" : null) ?>><?= lang("Kargoya Verildi.") ?></option>
            <option value="Tamamlandı." <?= ($item->status == "Tamamlandı." ? "selected" : null) ?>><?= lang("Tamamlandı.") ?></option>
            <option value="İptal Edildi." <?= ($item->status == "İptal Edildi." ? "selected" : null) ?>><?= lang("İptal Edildi.") ?></option>
        </select>
    </div>
    <a href="javascript:void(0)" onclick="closeModal('#ordersModal')" title="<?= lang("close") ?>" class="theme-btn-one btn-black-overlay btn_sm"><?= lang("close") ?></a>
</div>