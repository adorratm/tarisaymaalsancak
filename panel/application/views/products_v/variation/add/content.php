<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="createProductVariationGroup" onsubmit="return false" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>SKU (Stok Kodu)</label>
                <input class="form-control form-control-sm rounded-0" placeholder="SKU (Stok Kodu)" name="title" required>
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
                <label>İndirim Oranı (%)</label>
                <input type="number" class="form-control form-control-sm rounded-0" placeholder="İndirim Oranı (%)" name="discount" required>
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
                <input type="number" class="form-control form-control-sm rounded-0" placeholder="Stok" name="stock" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <?php if (!empty($categories)) : ?>
                <div class="form-group">
                    <label>Varyasyon Kategorileri</label>
                    <select class="rounded-0 tagsInput changeCategory" multiple name="category_id[]" required>
                        <option value="">Varyasyon Kategorisi / Kategorileri Seçiniz.</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category->id; ?>"><?= $category->title; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <?php foreach ($categories as $category) : ?>
                    <div class="form-group variationCategory d-none" data-categoryid="<?= $category->id ?>">
                        <label><?= $category->title ?></label>
                        <select class="rounded-0 form-control variation" data-categoryid="<?= $category->id ?>" name="variation_id[]" required disabled>
                            <option value="">Varyasyon Seçiniz.</option>
                            <?php foreach ($variations as $key => $value) : ?>
                                <?php if ($value->category_id == $category->id) : ?>
                                    <option value="<?= $value->id ?>"><?= $value->title ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button role="button" data-url="<?= base_url("products/variation_group_save/{$product_id}"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnSave">Kaydet</button>
            <a href="javascript:void(0)" onclick="closeModal('#productVariationGroupModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>

<script>
    $(document).on("change", ".changeCategory", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        let category_ids = $(this).val();
        let newArray = [];
        category_ids.forEach(function(el, i) {
            newArray.push(parseInt(el))
        });
        $(".variationCategory").each(function() {
            let $this = $(this);
            if (!$this.hasClass("d-none")) {
                $this.addClass("d-none");
                $this.find(".variation").prop("disabled", true);
            }
            if (newArray.includes($this.data("categoryid"))) {
                if ($this.hasClass("d-none")) {
                    $this.removeClass("d-none");
                    $this.find(".variation").prop("disabled", false);
                } else {

                }
            } else {
                if (!$this.hasClass("d-none")) {
                    $this.addClass("d-none");
                    $this.find(".variation").prop("disabled", true);
                }
            }
        });

    });
</script>