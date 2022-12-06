<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="container-fluid mt-xl-50 mt-lg-30 mt-15 bg-white p-3">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <h4 class="mb-3">
                "<b><?= stripslashes($item->title); ?></b>" kaydına ait varyasyon grupları
                <a href="javascript:void(0)" data-url="<?= base_url("products/variation_group_new_form/$item->id"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btn-sm float-right createProductVariationGroupBtn"> <i class="fa fa-plus"></i> Yeni Ekle</a>
            </h4>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <form id="filter_form" onsubmit="return false">
                <div class="d-flex flex-wrap">
                    <label for="search" class="flex-fill mx-1">
                        <input class="form-control form-control-sm rounded-0" placeholder="Arama Yapmak İçin Metin Girin." type="text" onkeypress="return runScript(event,'productVariationGroupTable')" name="search">
                    </label>
                    <label for="clear_button" class="mx-1">
                        <button class="btn btn-sm btn-outline-danger rounded-0 " onclick="clearFilter('filter_form','productVariationGroupTable')" id="clear_button" data-toggle="tooltip" data-placement="top" data-title="Filtreyi Temizle" data-original-title="" title=""><i class="fa fa-eraser"></i></button>
                    </label>
                    <label for="search_button" class="mx-1">
                        <button class="btn btn-sm btn-outline-success rounded-0 " onclick="reloadTable('productVariationGroupTable')" id="search_button" data-toggle="tooltip" data-placement="top" data-title="Varyasyon Grubu Ara"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <table class="table table-hover table-striped table-bordered content-container productVariationGroupTable">
                <thead>
                    <th class="order"><i class="fa fa-reorder"></i></th>
                    <th class="order"><i class="fa fa-reorder"></i></th>
                    <th class="w50">#id</th>
                    <th>Kategoriler</th>
                    <th>Varyasyon</th>
                    <th>Fiyat</th>
                    <th>İndirim</th>
                    <th>Stok</th>
                    <th>Dil</th>
                    <th>Durumu</th>
                    <th>Oluşturulma Tarihi</th>
                    <th>Güncelleme Tarihi</th>
                    <th class="nosort">İşlem</th>
                </thead>
                <tbody>

                </tbody>
            </table>
            <script>
                function obj(d) {
                    let appendeddata = {};
                    $.each($("#filter_form").serializeArray(), function() {
                        d[this.name] = this.value;
                    });
                    return d;
                }
                $(document).ready(function() {
                    TableInitializerV2("productVariationGroupTable", obj, {}, "<?= base_url("products/variationGroupDatatable/$item->id") ?>", "<?= base_url("products/variationGroupRankSetter/$item->id") ?>", true);

                });
            </script>
        </div>
    </div>
</div>
</div>

<div id="productVariationGroupModal"></div>

<script>
    $(document).ready(function() {
        $(document).on("click", ".createProductVariationGroupBtn", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let url = $(this).data("url");
            $('#productVariationGroupModal').iziModal('destroy');
            createModal("#productVariationGroupModal", "Yeni Varyasyon Grubu Ekle", "Yeni Varyasyon Grubu Ekle", 600, true, "20px", 0, "#e20e17", "#fff", 1040, function() {
                $.post(url, {}, function(response) {
                    $("#productVariationGroupModal .iziModal-content").html(response);
                    TinyMCEInit();
                    flatPickrInit();
                    $(".tagsInput").select2({
                        placeholder: 'Varyasyon Kategorisi / Kategorileri Seçiniz.',
                        width: 'resolve',
                        theme: "classic",
                        tags: false,
                        tokenSeparators: [',', ' '],
                        multiple: true
                    });

                    $(".tagsInput2").select2({
                        placeholder: 'Varyasyon / Varyasyonları Seçiniz.',
                        width: 'resolve',
                        theme: "classic",
                        tags: false,
                        tokenSeparators: [',', ' '],
                        multiple: true
                    });
                });
            });
            openModal("#productVariationGroupModal");
            $("#productVariationGroupModal").iziModal("setFullscreen", false);
        });
        $(document).on("click", ".btnSave", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let url = $(this).data("url");
            let formData = new FormData(document.getElementById("createProductVariationGroup"));
            createAjax(url, formData, function() {
                closeModal("#productVariationGroupModal");
                $("#ğroductVariationGroupModal").iziModal("setFullscreen", false);
                reloadTable("productVariationGroupTable");
            });
        });
        $(document).on("click", ".updateProductVariationGroupBtn", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            $('#productVariationGroupModal').iziModal('destroy');
            let url = $(this).data("url");
            createModal("#productVariationGroupModal", "Varyasyon Grubu Düzenle", "Varyasyon Grubu Düzenle", 600, true, "20px", 0, "#e20e17", "#fff", 1040, function() {
                $.post(url, {}, function(response) {
                    $("#productVariationGroupModal .iziModal-content").html(response);
                    TinyMCEInit();
                    flatPickrInit();
                    $(".tagsInput").select2({
                        placeholder: 'Varyasyon Kategorisi / Kategorileri Seçiniz.',
                        width: 'resolve',
                        theme: "classic",
                        tags: false,
                        tokenSeparators: [',', ' '],
                        multiple: true
                    });

                    $(".tagsInput2").select2({
                        placeholder: 'Varyasyon / Varyasyonları Seçiniz.',
                        width: 'resolve',
                        theme: "classic",
                        tags: false,
                        tokenSeparators: [',', ' '],
                        multiple: true
                    });
                });
            });
            openModal("#productVariationGroupModal");
            $("#productVariationGroupModal").iziModal("setFullscreen", false);
        });
        $(document).on("click", ".btnUpdate", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let url = $(this).data("url");
            let formData = new FormData(document.getElementById("updateProductVariationGroup"));
            createAjax(url, formData, function() {
                closeModal("#productVariationGroupModal");
                $("#productVariationGroupModal").iziModal("setFullscreen", false);
                reloadTable("productVariationGroupTable");
            });
        });
    });
</script>