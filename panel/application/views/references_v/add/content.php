<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="createReference" onsubmit="return false" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Başlık</label>
                <input class="form-control form-control-sm rounded-0" placeholder="Başlık" name="title" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Ülke</label>
                <?php if (!empty($countries)) : ?>
                    <select name="country" id="saveCountry" class="form-control">
                        <?php foreach ($countries as $key => $value) : ?>
                            <option value="<?= $value->name ?>" <?= ($value->name == "Turkey" ? "selected" : null) ?>><?= $value->name ?></option>
                        <?php endforeach ?>
                    </select>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label for="city">İl</label>
                <select name="city" id="saveCity" class="form-control">
                    <option value="">İl Seçiniz</option>
                    <?php foreach ($cities as $key => $value) : ?>
                        <option value="<?= $value->city ?>"><?= $value->city ?></option>
                    <?php endforeach ?>
                </select>
                <input name="city" id="saveCity2" class="form-control d-none" disabled>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label for="district">İlçe</label>
                <select name="district" id="saveDistrict" class="form-control">
                    <option value="">İlçe Seçiniz</option>
                </select>
                <input name="district" id="saveDistrict2" class="form-control d-none" disabled>
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
                <label>Görsel Seçiniz</label>
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control rounded-0 text-truncate" data-trigger="fileinput"><i class="fa fa-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                    <span class="input-group-append">
                        <span class=" btn btn-outline-primary rounded-0 btn-file"><span class="fileinput-new">Dosya Seç</span><span class="fileinput-exists">Değiştir</span>
                            <input type="hidden"><input type="file" name="img_url" required>
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
                <label>Paylaşım Tarihi</label>
                <input type="text" name="sharedAt" placeholder="Paylaşım Tarihi" class="form-control form-control-sm datetimepicker" data-flatpickr data-alt-input="true" data-enable-time="true" data-enable-seconds="true" value="<?= date("Y-m-d H:i:s") ?>" data-default-date="<?= date("Y-m-d H:i:s") ?>" data-date-format="Y-m-d H:i:S" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Bayi Kategorisi</label>
                <select class="form-control form-control-sm rounded-0" name="category_id" required>
                    <option value="">Bayi Kategorisi Seçiniz.</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category->id; ?>"><?= $category->title; ?></option>
                    <?php endforeach ?>
                </select>
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
            <button role="button" data-url="<?= base_url("references/save") ?>" class="btn btn-sm btn-outline-primary rounded-0 btnSave">Kaydet</button>
            <a href="javascript:void(0)" onclick="closeModal('#referencesModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>

<script>
    let city2 = $("#saveCity2"),
        city = $("#saveCity"),
        district2 = $("#saveDistrict2"),
        district = $("#saveDistrict");
    $(document).on("change", "#saveCountry", function() {
        let selected = $(this).val();
        if (selected !== "Turkey") {
            if (city2.hasClass("d-none")) {
                city2.removeClass("d-none");
                city2.prop("disabled", false);
            }
            if (!city.hasClass("d-none")) {
                city.addClass("d-none");
                city.prop("disabled", true);
            }
            if (district2.hasClass("d-none")) {
                district2.removeClass("d-none");
                district2.prop("disabled", false);
            }
            if (!district.hasClass("d-none")) {
                district.addClass("d-none");
                district.prop("disabled", true);
            }
        } else {
            if (!city2.hasClass("d-none")) {
                city2.addClass("d-none");
                city2.prop("disabled", true);
            }
            if (city.hasClass("d-none")) {
                city.removeClass("d-none");
                city.prop("disabled", false);
            }
            if (!district2.hasClass("d-none")) {
                district2.addClass("d-none");
                district2.prop("disabled", true);
            }
            if (district.hasClass("d-none")) {
                district.removeClass("d-none");
                district.prop("disabled", false);
            }
        }
    });
    $(document).on("change", "#saveCity", function() {
        let selected = $(this).val();
        $.post("<?= base_url("references/getDistricts") ?>", {
            "city": selected,
            "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
        }, function(response) {
            district.find("option[value!='']").remove();
            response.forEach(function(el, i) {
                let html = "<option value=' " + el.district + "'  " + (i == 0 ? "selected" : null) + "  >" + el.district + "</option>";
                district.append(html);
            });
        }, "JSON");
    });
</script>