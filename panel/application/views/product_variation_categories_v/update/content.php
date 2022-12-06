<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="updateProductVariationCategory" onsubmit="return false" action="" method="post">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Başlık </label>
                <input class="form-control form-control-sm rounded-0" placeholder="Başlık" name="title" value="<?= !empty($item->title) ? $item->title : null; ?>" required>
            </div>
        </div>
    </div>
    <?php if (!empty($categories)) : ?>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                    <label>Üst Kategorisi</label>
                    <select name="top_id" id="top_id" class="form-control">
                        <option value="">Üst Kategori Seçiniz.</option>
                        <?php foreach ($categories as $key => $value) : ?>
                            <option value="<?= $value->id ?>" <?= ($value->id == $item->top_id ? "selected" : null) ?>><?= $value->title ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>
    <?php endif ?>
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
            <button role="button" data-url="<?= base_url("product_variation_categories/update/{$item->id}"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnUpdate">Güncelle</button>
            <a href="javascript:void(0)" onclick="closeModal('#productVariationCategoryModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>