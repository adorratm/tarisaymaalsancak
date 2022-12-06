<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="updateProductVariation" onsubmit="return false" action="" method="post">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Başlık </label>
                <input class="form-control form-control-sm rounded-0" placeholder="Başlık" name="title" value="<?= !empty($item->title) ? $item->title : null; ?>" required>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Değer </label>
                <input class="form-control form-control-sm rounded-0" placeholder="Değer" name="value" value="<?= !empty($item->value) ? $item->value : null; ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">Varyasyon Kategorisi Seçiniz.</option>
                    <?php if (!empty($categories)) : ?>
                        <?php foreach ($categories as $key => $value) : ?>
                            <option value="<?= $value->id ?>" <?= ($value->id == $item->category_id ? "selected" : null) ?>><?= $value->title ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Dil</label>
                <input type="text" class="form-control form-control-sm rounded-0" name="lang" disabled value="<?= !empty($item->lang) ? $item->lang : "tr" ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button role="button" data-url="<?= base_url("product_variations/update/{$item->id}"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnUpdate">Güncelle</button>
            <a href="javascript:void(0)" onclick="closeModal('#productVariationModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>