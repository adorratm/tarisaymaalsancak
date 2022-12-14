<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="updateProductPart" onsubmit="return false" action="" method="post">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Başlık</label>
                <input class="form-control form-control-sm rounded-0" placeholder="Başlık" name="title" value="<?= !empty($item->title) ? $item->title : null; ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Fiyat </label>
                <input type="number" class="form-control form-control-sm rounded-0" placeholder="Fiyat" name="price" value="<?= !empty($item->price) ? $item->price : null; ?>" required>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Adet </label>
                <input type="number" class="form-control form-control-sm rounded-0" placeholder="Adet" name="quantity" value="<?= !empty($item->quantity) ? $item->quantity : null; ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <?php if (!empty($product_variation_groups)) : ?>
                <div class="form-group">
                    <label>Parça Varyasyon Grubu Seçimi</label>
                    <select class="rounded-0 tagsInput" name="pvgId" required>
                        <option value="">Varyasyon Grubu Seçiniz.</option>
                        <?php foreach ($product_variation_groups as $key => $value) : ?>
                            <option value="<?= $value->id ?>" <?= $item->pvgId == $value->id ? "selected" : null ?>><?= $value->title ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            <?php endif ?>
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
            <button role="button" data-url="<?= base_url("products/partUpdate/{$item->id}"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnUpdate">Güncelle</button>
            <a href="javascript:void(0)" onclick="closeModal('#productPartModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>