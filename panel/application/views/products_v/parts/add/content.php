<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="createProductPart" onsubmit="return false" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Başlık</label>
                <input class="form-control form-control-sm rounded-0" placeholder="Başlık" name="title" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Fiyat</label>
                <input type="number" class="form-control form-control-sm rounded-0" placeholder="Fiyat" name="price" required>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Adet</label>
                <input type="number" class="form-control form-control-sm rounded-0" placeholder="Adet" name="quantity" required>
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
                            <option value="<?= $value->id ?>"><?= $value->title ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            <?php endif ?>
        </div>
    </div>
    <input type="hidden" name="product_id" value="<?= $product_id ?>">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Dil</label>
                <select name="lang" class="form-control form-control-sm rounded-0" required>
                    <?php if (!empty($settings)) : ?>
                        <?php foreach ($settings as $key => $value) : ?>
                            <option value="<?= $value->lang ?>"><?= $value->lang ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button role="button" data-url="<?= base_url("products/partSave/{$product_id}"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnSave">Kaydet</button>
            <a href="javascript:void(0)" onclick="closeModal('#productPartModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>