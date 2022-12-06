<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="container-fluid mt-xl-50 mt-lg-30 mt-15 bg-white p-3">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <h4 class="mb-3">
                "<b><?= stripslashes($item->title); ?></b>" kategorisine ait parçalar
                <a href="javascript:void(0)" data-url="<?= base_url("product_categories/part_new_form/$item->id"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btn-sm float-right createCategoryPartBtn"> <i class="fa fa-plus"></i> Yeni Ekle</a>
            </h4>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <form id="filter_form" onsubmit="return false">
                <div class="d-flex flex-wrap">
                    <label for="search" class="flex-fill mx-1">
                        <input class="form-control form-control-sm rounded-0" placeholder="Arama Yapmak İçin Metin Girin." type="text" onkeypress="return runScript(event,'categoryPartsTable')" name="search">
                    </label>
                    <label for="clear_button" class="mx-1">
                        <button class="btn btn-sm btn-outline-danger rounded-0 " onclick="clearFilter('filter_form','categoryPartsTable')" id="clear_button" data-toggle="tooltip" data-placement="top" data-title="Filtreyi Temizle" data-original-title="" title=""><i class="fa fa-eraser"></i></button>
                    </label>
                    <label for="search_button" class="mx-1">
                        <button class="btn btn-sm btn-outline-success rounded-0 " onclick="reloadTable('categoryPartsTable')" id="search_button" data-toggle="tooltip" data-placement="top" data-title="Varyasyon Grubu Ara"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <table class="table table-hover table-striped table-bordered content-container categoryPartsTable">
                <thead>
                    <th class="order"><i class="fa fa-reorder"></i></th>
                    <th class="order"><i class="fa fa-reorder"></i></th>
                    <th class="w50">#id</th>
                    <th>Başlık</th>
                    <th>Fiyat</th>
                    <th>Adet</th>
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
                    TableInitializerV2("categoryPartsTable", obj, {}, "<?= base_url("product_categories/partDatatable/$item->id") ?>", "<?= base_url("product_categories/partRankSetter/$item->id") ?>", true);

                });
            </script>
        </div>
    </div>
</div>
</div>

<div id="categoryPartModal"></div>

<script>
    $(document).ready(function() {
        $(document).on("click", ".createCategoryPartBtn", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let url = $(this).data("url");
            $('#categoryPartModal').iziModal('destroy');
            createModal("#categoryPartModal", "Yeni Parça Ekle", "Yeni Parça Ekle", 600, true, "20px", 0, "#e20e17", "#fff", 1040, function() {
                $.post(url, {}, function(response) {
                    $("#categoryPartModal .iziModal-content").html(response);
                    TinyMCEInit();
                    flatPickrInit();
                    $(".tagsInput").select2({
                        placeholder: 'Parça Kategorisi / Kategorileri Seçiniz.',
                        width: 'resolve',
                        theme: "classic",
                        tags: false,
                        tokenSeparators: [',', ' '],
                        multiple: true
                    });

                    $(".tagsInput2").select2({
                        placeholder: 'Parça / Parçaları Seçiniz.',
                        width: 'resolve',
                        theme: "classic",
                        tags: false,
                        tokenSeparators: [',', ' '],
                        multiple: true
                    });
                });
            });
            openModal("#categoryPartModal");
            $("#categoryPartModal").iziModal("setFullscreen", false);
        });
        $(document).on("click", ".btnSave", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let url = $(this).data("url");
            let formData = new FormData(document.getElementById("createCategoryPart"));
            createAjax(url, formData, function() {
                closeModal("#categoryPartModal");
                $("#categoryPartModal").iziModal("setFullscreen", false);
                reloadTable("categoryPartsTable");
            });
        });
        $(document).on("click", ".updateCategoryPartBtn", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            $('#categoryPartModal').iziModal('destroy');
            let url = $(this).data("url");
            createModal("#categoryPartModal", "Parça Düzenle", "Parça Düzenle", 600, true, "20px", 0, "#e20e17", "#fff", 1040, function() {
                $.post(url, {}, function(response) {
                    $("#categoryPartModal .iziModal-content").html(response);
                    TinyMCEInit();
                    flatPickrInit();
                    $(".tagsInput").select2({
                        placeholder: 'Parça Kategorisi / Kategorileri Seçiniz.',
                        width: 'resolve',
                        theme: "classic",
                        tags: false,
                        tokenSeparators: [',', ' '],
                        multiple: true
                    });

                    $(".tagsInput2").select2({
                        placeholder: 'Parça / Parça Seçiniz.',
                        width: 'resolve',
                        theme: "classic",
                        tags: false,
                        tokenSeparators: [',', ' '],
                        multiple: true
                    });
                });
            });
            openModal("#categoryPartModal");
            $("#categoryPartModal").iziModal("setFullscreen", false);
        });
        $(document).on("click", ".btnUpdate", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let url = $(this).data("url");
            let formData = new FormData(document.getElementById("updateCategoryPart"));
            createAjax(url, formData, function() {
                closeModal("#categoryPartModal");
                $("#categoryPartModal").iziModal("setFullscreen", false);
                reloadTable("categoryPartsTable");
            });
        });
    });
</script>