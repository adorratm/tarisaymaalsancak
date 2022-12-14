<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="createProduct" onsubmit="return false" action="" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Başlık</label>
                <input class="form-control form-control-sm rounded-0" placeholder="Başlık" name="title" required>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 d-none">
            <div class="form-group">
                <label>Dış URL</label>
                <input class="form-control form-control-sm rounded-0" disabled placeholder="Dış URL" name="external_url" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
            <div class="form-group">
                <label>Fiyat</label>
                <input class="form-control form-control-sm rounded-0" placeholder="Fiyat" name="price" required min="0">
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
            <div class="form-group">
                <label>İndirim Oranı (%)</label>
                <input class="form-control form-control-sm rounded-0" type="number" placeholder="İndirim Oranı (%)" name="discount" required min="0" max="100">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>KDV Dahil / Hariç</label>
                <select name="vat" id="vat" class="form-control form-control-sm rounded-0">
                    <option value="0" selected>KDV DAHİL</option>
                    <option value="1">KDV HARİÇ</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>KDV Oranı (%)</label>
                <input type="number" class="form-control form-control-sm rounded-0" min="0" placeholder="KDV Oranı (%)" name="vatRate" value="18" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Stok Durumu</label>
                <select name="stockStatus" id="stockStatus" class="form-control form-control-sm rounded-0">
                    <option value="0">Stokta Yok</option>
                    <option value="1" selected>Stokta Var</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" min="0" value="0" class="form-control form-control-sm rounded-0" placeholder="Stok">
            </div>
        </div>
    </div>
    <div class="row d-none">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Firma Kodu</label>
                <input disabled type="text" name="company_code" class="form-control form-control-sm rounded-0" placeholder="Firma Kodu">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Kısa Açıklama</label>
                <textarea name="description" class="m-0 tinymce" required></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Açıklama</label>
                <textarea name="content" class="m-0 tinymce" required></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Özellikler</label>
                <textarea name="features" class="m-0 tinymce" required></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Banner Görseli Seçiniz</label>
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control rounded-0 text-truncate" data-trigger="fileinput"><i class="fa fa-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                    <span class="input-group-append">
                        <span class=" btn btn-outline-primary rounded-0 btn-file"><span class="fileinput-new">Dosya Seç</span><span class="fileinput-exists">Değiştir</span>
                            <input type="hidden"><input type="file" name="banner_url" required>
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
                <select class="rounded-0 tagsInput" multiple name="category_id[]" required>
                    <option value="">Ürün Kategorisi Seçiniz.</option>
                    <?php if (!empty($categories)) : ?>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category->id; ?>">
                                <?php if (!empty($category->top_id) && $category->top_id !== 0) : ?>
                                    <?php foreach ($categories as $k => $v) : ?>
                                        <?php if ($v->id == $category->top_id) : ?>
                                            <?= $v->title ?> >
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php endif ?>
                                <?= $category->title; ?>
                            </option>
                        <?php endforeach ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Paylaşım Tarihi</label>
                <input type="text" name="sharedAt" placeholder="Paylaşım Tarihi" class="form-control form-control-sm datetimepicker" data-flatpickr data-alt-input="true" data-enable-time="true" data-enable-seconds="true" value="<?= date("Y-m-d H:i:s") ?>" data-default-date="<?= date("Y-m-d H:i:s") ?>" data-date-format="Y-m-d H:i:S" required>
            </div>
        </div>
    </div>
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
            <button role="button" data-url="<?= base_url("products/save"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnSave">Kaydet</button>
            <a href="javascript:void(0)" onclick="closeModal('#productModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>