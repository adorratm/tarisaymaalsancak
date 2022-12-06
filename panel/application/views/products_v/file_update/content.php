<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="updateSku" onsubmit="return false" action="" method="post">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <?php if (!empty($groups)) : ?>
                <div class="form-group">
                    <label>Ürün Varyasyon Grubu Seçimi (SKU'ya Göre)</label>
                    <select class="rounded-0 tagsInput" multiple name="variation_group_id[]" required>
                        <option value="">Varyasyon Grubu / Grupları Seçiniz.</option>
                        <?php foreach ($groups as $key => $value) : ?>
                            <?php $variationGroupIds = !empty($item->variation_group_id) ? explode(",",$item->variation_group_id) : []?>
                            <option value="<?= $value->id ?>" <?= (in_array($value->id, $variationGroupIds) ? "selected" : null) ?>><?= $value->title ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button role="button" data-url="<?= base_url("products/file_update/{$item->id}/{$product_id}"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnUpdate">Güncelle</button>
            <a href="javascript:void(0)" onclick="closeModal('#productSkuModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>