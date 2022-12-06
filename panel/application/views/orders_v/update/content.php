<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="updateOrder" onsubmit="return false" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <div class="accordion rounded-0" id="accordionExample">
            <div class="card rounded-0">
                <div class="card-header rounded-0" id="headingOne">
                    <h2 class="mb-3 rounded-0">
                        <button class="btn btn-block rounded-0 text-center btn-danger" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Sipariş İçeriğini Göster / Gizle
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" colspan="2"><strong>ÜYE BİLGİLERİ</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center align-middle">
                                            Ad Soyad:
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
                                            Telefon:
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
                                        <th colspan="2" class="text-center align-middle"><strong>TESLİMAT ADRESİ</strong></th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td class="text-center align-middle">
                                        Adres Başlığı:
                                    </td>
                                    <td class="text-center align-middle">
                                        <strong><?= !empty($item->address_title) ? $item->address_title : null; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center align-middle">
                                        Ülke:
                                    </td>
                                    <td class="text-center align-middle">
                                        <strong><?= !empty($item->country) ? $item->country : null; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center align-middle">
                                        Şehir:
                                    </td>
                                    <td class="text-center align-middle">
                                        <strong><?= !empty($item->city) ? $item->city : null; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center align-middle">
                                        İlçe:
                                    </td>
                                    <td class="text-center align-middle">
                                        <strong><?= !empty($item->district) ? $item->district : null; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center align-middle">
                                        Semt:
                                    </td>
                                    <td class="text-center align-middle">
                                        <strong><?= !empty($item->neighborhood) ? $item->neighborhood : null; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center align-middle">
                                        Mahalle:
                                    </td>
                                    <td class="text-center align-middle">
                                        <strong><?= !empty($item->quarter) ? $item->quarter : null; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center align-middle">
                                        Adres:
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
                                            <th class="font-weight-bold text-center align-middle" colspan="5">SİPARİŞ DETAYI</th>
                                        </tr>
                                        <tr>
                                            <th class='font-weight-bold text-center align-middle'>Görsel</th>
                                            <th class='font-weight-bold text-center align-middle'>Ürün Adı</th>
                                            <th class='font-weight-bold text-center align-middle'>Adet</th>
                                            <th class='font-weight-bold text-center align-middle'>Fiyat</th>
                                            <th class='font-weight-bold text-center align-middle'>Ara Toplam</th>
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
                                            if (!empty($cart_value["options"]) && !empty($cart_value["options"]["pvgId"])) :
                                                $wheres["pvg.id"] = $cart_value["options"]["pvgId"];
                                            else :
                                                if (array_key_exists("pvg.id", $wheres)) :
                                                    unset($wheres["pvg.id"]);
                                                endif;
                                            endif;
                                            $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
                                            $select = "p.id,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,p.vat vat,p.vatRate vatRate,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,CAST(IFNULL(pvg.price,p.price) AS FLOAT) AS newPrice";
                                            $distinct = true;
                                            $groupBy = ["pwc.product_id"];
                                            $product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
                                            if (!empty($cart_value["options"]["pvgId"])) :
                                                $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $cart_value["options"]["pvgId"]]);
                                                $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                                                $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                                                $product_variations = [];
                                                $product_variation_categories = [];
                                                if (!empty($product_variation_in_group)) :
                                                    foreach ($product_variation_in_group as $key => $value) :
                                                        $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value]);
                                                        $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key]]);
                                                        array_push($product_variation_categories, $product_variation_group->title);
                                                        array_push($product_variations, $product_variation->title);
                                                    endforeach;
                                                endif;
                                            endif;
                                            ?>
                                            <?php if (!empty($product)) : ?>
                                                <tr>
                                                    <td class='text-center align-middle'>
                                                        <img class='img-fluid' src='<?= get_picture("products_v", $product->img_url) ?>' style='max-width:125px;max-height:125px;object-fit:scale-down'>
                                                    </td>
                                                    <td class='text-center align-middle'><?= $product->title ?>
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
                                            <td colspan="5" class="text-right">Ara Toplam : <span class="float-right"> <?= $order_data["subTotal"] ?> <?= $item->symbol ?></span></td>
                                        </tr>
                                        <?php if ((float)$order_data["vat"] > 0) : ?>
                                            <tr>
                                                <td colspan="5" class="text-right">KDV : <span class="float-right"> <?= $order_data["vat"] ?> <?= $item->symbol ?></span></td>
                                            </tr>
                                        <?php endif ?>
                                        <?php if ((float)$order_data["shipping"] > 0) : ?>
                                            <tr>
                                                <td colspan="5" class="text-right">Kargo : <span class="float-right"> <?= $order_data["shipping"] ?> <?= $item->symbol ?></span></td>
                                            </tr>
                                        <?php endif ?>
                                        <tr>
                                            <td colspan="5" class="text-right"><b>Toplam</b> : <span class="float-right"> <?= $order_data["total"] ?> <?= $item->symbol ?></span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>SİPARİŞ DURUMU</label>
            <select class="form-control form-control-sm rounded-0" name="status" id="status">
                <option value="Ödeme Bekleniyor." <?= ($item->status == "Ödeme Bekleniyor." ? "selected" : null) ?>>Ödeme Bekleniyor.</option>
                <option value="Ödeme Alındı." <?= ($item->status == "Ödeme Alındı." ? "selected" : null) ?>>Ödeme Alındı.</option>
                <option value="Ödeme Onayı Bekleniyor." <?= ($item->status == "Ödeme Onayı Bekleniyor." ? "selected" : null) ?>>Ödeme Onayı Bekleniyor.</option>
                <option value="Ödeme Onaylandı." <?= ($item->status == "Ödeme Onaylandı." ? "selected" : null) ?>>Ödeme Onaylandı.</option>
                <option value="Hazırlanıyor." <?= ($item->status == "Hazırlanıyor." ? "selected" : null) ?>>Hazırlanıyor.</option>
                <option value="Kargoya Verildi." <?= ($item->status == "Kargoya Verildi." ? "selected" : null) ?>>Kargoya Verildi.</option>
                <option value="Tamamlandı." <?= ($item->status == "Tamamlandı." ? "selected" : null) ?>>Tamamlandı.</option>
                <option value="İptal Edildi." <?= ($item->status == "İptal Edildi." ? "selected" : null) ?>>İptal Edildi.</option>
            </select>
        </div>

        <button data-url="<?= base_url("orders/update/$item->id"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnUpdate">Güncelle</button>
        <a href="javascript:void(0)" onclick="closeModal('#ordersModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
    </div>
</form>