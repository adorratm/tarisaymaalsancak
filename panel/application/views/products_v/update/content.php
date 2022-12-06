<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="updateProduct" onsubmit="return false" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Başlık</label>
                <input class="form-control form-control-sm rounded-0" placeholder="Başlık" name="title" value="<?= !empty($item->title) ? $item->title : null; ?>" required>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 d-none">
            <div class="form-group">
                <label>Dış URL</label>
                <input disabled class="form-control form-control-sm rounded-0" placeholder="Dış URL" name="external_url" value="<?= !empty($item->external_url) ? $item->external_url : null; ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Fiyat</label>
                <input class="form-control form-control-sm rounded-0" placeholder="Fiyat" name="price" value="<?= !empty($item->price) ? $item->price : null; ?>" required min="0">
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>İndirim Oranı (%)</label>
                <input class="form-control form-control-sm rounded-0" placeholder="İndirim Oranı (%)" name="discount" value="<?= !empty($item->discount) ? $item->discount : null; ?>" required min="0" max="100" type="number">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>KDV Dahil / Hariç</label>
                <select name="vat" id="vat" class="form-control form-control-sm rounded-0">
                    <option value="0" <?= (!empty($item->vat) && $item->vat == 0 ? "selected" : null) ?>>KDV DAHİL</option>
                    <option value="1" <?= (!empty($item->vat) && $item->vat == 1 ? "selected" : null) ?>>KDV HARİÇ</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>KDV Oranı (%)</label>
                <input type="number" class="form-control form-control-sm rounded-0" min="0" placeholder="KDV Oranı (%)" name="vatRate" value="<?= !empty($item->vatRate) ? $item->vatRate : null; ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Stok Durumu</label>
                <select name="stockStatus" id="stockStatus" class="form-control form-control-sm rounded-0">
                    <option value="0" <?= !empty($item->stockStatus) && $item->stockStatus == 0 ? "selected" : null; ?>>Stokta Yok</option>
                    <option value="1" <?= !empty($item->stockStatus) && $item->stockStatus == 1 ? "selected" : null; ?>>Stokta Var</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" min="0" class="form-control form-control-sm rounded-0" placeholder="Stok" value="<?= !empty($item->stock) ? $item->stock : 0; ?>">
            </div>
        </div>
    </div>
    <div class="row d-none">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Firma Kodu</label>
                <input disabled type="text" name="company_code" class="form-control form-control-sm rounded-0" placeholder="Firma Kodu" value="<?= !empty($item->company_code) ? $item->company_code : null ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Kısa Açıklama</label>
                <textarea name="description" class="m-0 tinymce" required><?= !empty($item->description) ? $item->description : null; ?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Açıklama</label>
                <textarea name="content" class="m-0 tinymce" required><?= !empty($item->content) ? $item->content : null; ?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Özellikler</label>
                <textarea name="features" class="m-0 tinymce" required><?= !empty($item->features) ? $item->features : null; ?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 my-auto py-auto">
            <picture>
                <img src="<?= get_picture($viewFolder, (!empty($item->banner_url) ? $item->banner_url : null)); ?>" class="img-fluid">
            </picture>
        </div>
        <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 my-auto py-auto">
            <div class="form-group">
                <label>Banner Görseli Seçiniz</label>
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control rounded-0 text-truncate" data-trigger="fileinput"><i class="fa fa-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                    <span class="input-group-append">
                        <span class=" btn btn-outline-primary rounded-0 btn-file"><span class="fileinput-new">Dosya Seç</span><span class="fileinput-exists">Değiştir</span>
                            <input type="hidden"><input type="file" name="banner_url">
                        </span>
                        <a href="#" class="btn btn-outline-danger rounded-0 fileinput-exists" data-dismiss="fileinput">Kaldır</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Ürün Kategorisi</label>
                <?php $selecteds = [] ?>
                <?php foreach ($selectedCategories as $key => $value) : ?>
                    <?php if (!in_array($value->category_id, $selecteds)) : ?>
                        <?php array_push($selecteds, $value->category_id) ?>
                    <?php endif ?>
                <?php endforeach ?>
                <select class="rounded-0 tagsInput" multiple name="category_id[]" required>
                    <?php foreach ($categories as $category) : ?>
                        <option <?= (in_array($category->id, $selecteds) ? "selected" : null) ?> value="<?= $category->id; ?>">
                            <?php if (!empty($category->top_id) && $category->top_id !== 0) : ?>
                                <?php foreach ($categories as $k => $v) : ?>
                                    <?php if ($v->id == $category->top_id) : ?>
                                        <?= $v->id ?> - <?= $v->title ?> >
                                    <?php endif ?>
                                <?php endforeach ?>
                            <?php endif ?>
                            <?= $category->id ?> - <?= $category->title; ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Paylaşım Tarihi</label>
                <input type="text" name="sharedAt" placeholder="Paylaşım Tarihi" class="form-control form-control-sm datetimepicker" data-flatpickr data-alt-input="true" data-enable-time="true" data-enable-seconds="true" value="<?= (!empty($item->sharedAt) ? $item->sharedAt : date("Y-m-d H:i:s")) ?>" data-default-date="<?= (!empty($item->sharedAt) ? $item->sharedAt : date("Y-m-d H:i:s")) ?>" data-date-format="Y-m-d H:i:S" required>
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
            <button role="button" data-url="<?= base_url("products/update/$item->id"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnUpdate">Güncelle</button>
            <a href="javascript:void(0)" onclick="closeModal('#productModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>