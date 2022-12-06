<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if ($_POST) : ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form id="priceRateForm" onsubmit="return false">
                    <div class="form-group">
                        <label for="rate">Ürünün Fiyatını Artırmak/Azaltmak İstediğiniz Oran:</label>
                        <input type="number" name="rate" class="form-control form-control-sm rounded-0" placeholder="Ürünün Fiyatını Artırmak veya Azaltmak İstediğiniz Oran">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary rounded-0" id="btnUpdateRateAsc" data-url="<?= base_url("products/priceUpdateByRate") ?>">Seçili Ürünlerin Fiyatını Artır</button>
                        <button class="btn btn-danger rounded-0" id="btnUpdateRateDesc" data-url="<?= base_url("products/priceUpdateByRate") ?>">Seçili Ürünlerin Fiyatını Azalt</button>
                    </div>
                    <?php sort($_POST["product_ids"])?>
                    <input type="hidden" name="product_ids" value="<?=implode(",",$_POST["product_ids"])?>">
                </form>
                <div class="alert alert-info" role="alert">
                    Aşağıdaki Formdan Seçtiğiniz Ürünlerin Fiyatını Kendiniz Güncelleyebilirsiniz veya Yukarıdaki Formdan Seçtiğiniz Ürünlerin Fiyatını Oran Girerek Güncelleyebilirsiniz.
                </div>
            </div>
            <div class="col-12">
                <form id="updateProduct" onsubmit="return false" action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3 nav-tabs-horizontal">
                        <ul class="nav nav-tabs mb-3 d-none" id="myTab" role="tablist">
                            <?php foreach ($settings as $skey => $svalue) : ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link <?= ($skey == 0 ? 'active' : null) ?>" id="lang-<?= $svalue->lang ?>-tab" data-toggle="tab" href="#lang-<?= $svalue->lang ?>" role="tab" aria-controls="lang-<?= $svalue->lang ?>" aria-selected="true">Dil : <?= $svalue->lang ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <?php foreach ($settings as $skey => $svalue) : ?>
                                <div class="tab-pane fade <?= ($skey == 0 ? 'show active' : null) ?>" id="lang-<?= $svalue->lang ?>" role="tabpanel" aria-labelledby="lang-<?= $svalue->lang ?>-tab">
                                    <?php foreach ($products as $pkey => $product) : ?>
                                        <?php if ($product->lang == $svalue->lang) : ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Görseli</th>
                                                            <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Adı</th>
                                                            <th class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Ana Fiyatı</th>
                                                            <th class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Ana Stoğu</th>
                                                            <th class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Ana İndirim Oranı</th>
                                                            <?php
                                                            /**
                                                             * Product Variation Groups
                                                             */
                                                            $productVariationGroups = $this->general_model->get_all("product_variation_groups pvg", null, "pvg.id ASC", ["pvg.isActive" => 1, "pvg.product_id" => $product->id, "lang" => $svalue->lang]);
                                                            ?>
                                                            <?php if (!empty($productVariationGroups)) : ?>
                                                                <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Varyasyon Fiyatları</th>
                                                                <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Varyasyon Stokları</th>
                                                                <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Varyasyon İndirim Oranları</th>
                                                            <?php endif ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center justify-content-center align-items-center align-items-center align-middle my-auto py-auto text-break" style="width: 150px;max-width:150px"><img src="<?= get_picture("products_v", $product->img_url) ?>" width="100" height="100"></td>
                                                            <td class="text-center justify-content-center align-items-center align-items-center align-middle my-auto py-auto text-break" style="max-width: 250px;width:250px;white-space:normal"><?= $product->title ?></td>
                                                            <td class="mainColumn text-center justify-content-center align-items-center align-items-center align-middle my-auto py-auto text-break" style="width: 150px;max-width:150px;">
                                                                <input class="form-control text-center rounded-0" type="text" min="1" name="pricemain[<?= $product->id ?>]" value="<?= $product->price ?>">
                                                            </td>
                                                            <td class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto" style="width: 150px;max-width:150px;">
                                                                <input class="form-control text-center rounded-0" type="text" min="1" name="stockmain[<?= $product->id ?>]" value="<?= $product->stock ?>">
                                                            </td>
                                                            <td class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto" style="width: 150px;max-width:150px;">
                                                                <input class="form-control text-center rounded-0" type="text" min="1" name="discountmain[<?= $product->id ?>]" value="<?= $product->discount ?>">
                                                            </td>
                                                            <?php if (!empty($productVariationGroups)) : ?>
                                                                <td class="text-center justify-content-center  align-items-center align-middle my-auto py-auto text-break">
                                                                    <?php foreach ($productVariationGroups as $key => $value) : ?>
                                                                        <?php if (!empty($value->variation_id)) : ?>
                                                                            <?php foreach (explode(",", $value->variation_id) as $k => $v) : ?>
                                                                                <?php $variation = $this->general_model->get("product_variations", null, ["id" => $v,"lang" => $this->session->userdata("activeLang")]); ?>
                                                                                <label><?= $variation->title ?></label>
                                                                            <?php endforeach ?>
                                                                        <?php endif ?>
                                                                        <input class="form-control text-center mb-2 rounded-0" type="text" min="1" name="price[<?= $product->id ?>][<?= $value->id ?>]" value="<?= $value->price ?>">
                                                                    <?php endforeach ?>
                                                                </td>
                                                            <?php endif ?>
                                                            <?php if (!empty($productVariationGroups)) : ?>
                                                                <td class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">
                                                                    <?php foreach ($productVariationGroups as $key => $value) : ?>
                                                                        <?php if (!empty($value->variation_id)) : ?>
                                                                            <?php foreach (explode(",", $value->variation_id) as $k => $v) : ?>
                                                                                <?php $variation = $this->general_model->get("product_variations", null, ["id" => $v,"lang" => $this->session->userdata("activeLang")]); ?>
                                                                                <label><?= $variation->title ?></label>
                                                                            <?php endforeach ?>
                                                                        <?php endif ?>
                                                                        <input class="form-control text-center mb-2 rounded-0" type="text" min="1" name="stock[<?= $product->id ?>][<?= $value->id ?>]" value="<?= $value->stock ?>">
                                                                    <?php endforeach ?>
                                                                </td>
                                                            <?php endif ?>
                                                            <?php if (!empty($productVariationGroups)) : ?>
                                                                <td class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">
                                                                    <?php foreach ($productVariationGroups as $key => $value) : ?>
                                                                        <?php if (!empty($value->variation_id)) : ?>
                                                                            <?php foreach (explode(",", $value->variation_id) as $k => $v) : ?>
                                                                                <?php $variation = $this->general_model->get("product_variations", null, ["id" => $v,"lang" => $this->session->userdata("activeLang")]); ?>
                                                                                <label><?= $variation->title ?></label>
                                                                            <?php endforeach ?>
                                                                        <?php endif ?>
                                                                        <input class="form-control text-center mb-2 rounded-0" type="text" min="1" name="discount[<?= $product->id ?>][<?= $value->id ?>]" value="<?= $value->discount ?>">
                                                                    <?php endforeach ?>
                                                                </td>
                                                            <?php endif ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="my-3">
                            <?= $links ?>
                        </div>
                        <button role="button" data-url="<?= base_url("products/bulkupdate"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnUpdate">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php else : ?>
    <div class="container-fluid mt-xl-50 mt-lg-30 mt-15 bg-white p-3">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <h4 class="mb-3">
                    Toplu Fiyat - Stok - İndirim Güncelleme
                    <a href="<?= base_url("products") ?>" class="btn btn-sm btn-outline-primary rounded-0 float-right"> <i class="fa fa-dropbox"></i> Ürün Listesine Geri Dön</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-info float-right rounded-0 toggleMainColumn"><i class="fa fa-money"></i> Ana Fiyatları Göster / Gizle</a>
                </h4>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <form id="updateProduct" onsubmit="return false" action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3 nav-tabs-horizontal">
                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                            <?php foreach ($settings as $skey => $svalue) : ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link <?= ($skey == 0 ? 'active' : null) ?>" id="lang-<?= $svalue->lang ?>-tab" data-toggle="tab" href="#lang-<?= $svalue->lang ?>" role="tab" aria-controls="lang-<?= $svalue->lang ?>" aria-selected="true">Dil : <?= $svalue->lang ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <?php foreach ($settings as $skey => $svalue) : ?>
                                <div class="tab-pane fade <?= ($skey == 0 ? 'show active' : null) ?>" id="lang-<?= $svalue->lang ?>" role="tabpanel" aria-labelledby="lang-<?= $svalue->lang ?>-tab">
                                    <?php foreach ($products as $pkey => $product) : ?>
                                        <?php if ($product->lang == $svalue->lang) : ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Görseli</th>
                                                            <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Adı</th>
                                                            <th class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Ana Fiyatı</th>
                                                            <th class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Ana Stoğu</th>
                                                            <th class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Ürün Ana İndirim Oranı</th>
                                                            <?php
                                                            /**
                                                             * Product Variation Groups
                                                             */
                                                            $productVariationGroups = $this->general_model->get_all("product_variation_groups pvg", null, "pvg.id ASC", ["pvg.isActive" => 1, "pvg.product_id" => $product->id, "lang" => $svalue->lang]);
                                                            ?>
                                                            <?php if (!empty($productVariationGroups)) : ?>
                                                                <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Varyasyon Fiyatları</th>
                                                                <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Varyasyon Stokları</th>
                                                                <th class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">Varyasyon İndirim Oranları</th>
                                                            <?php endif ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center justify-content-center align-items-center align-items-center align-middle my-auto py-auto text-break" style="width: 150px;max-width:150px"><img src="<?= get_picture("products_v", $product->img_url) ?>" width="100" height="100"></td>
                                                            <td class="text-center justify-content-center align-items-center align-items-center align-middle my-auto py-auto text-break" style="max-width: 250px;width:250px;white-space:normal"><?= $product->title ?></td>
                                                            <td class="mainColumn text-center justify-content-center align-items-center align-items-center align-middle my-auto py-auto text-break" style="width: 150px;max-width:150px;">
                                                                <input class="form-control text-center rounded-0" type="text" min="1" name="pricemain[<?= $product->id ?>]" value="<?= $product->price ?>">
                                                            </td>
                                                            <td class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto" style="width: 150px;max-width:150px;">
                                                                <input class="form-control text-center rounded-0" type="text" min="1" name="stockmain[<?= $product->id ?>]" value="<?= $product->stock ?>">
                                                            </td>
                                                            <td class="mainColumn text-center justify-content-center align-items-center align-middle my-auto py-auto" style="width: 150px;max-width:150px;">
                                                                <input class="form-control text-center rounded-0" type="text" min="1" name="discountmain[<?= $product->id ?>]" value="<?= $product->discount ?>">
                                                            </td>
                                                            <?php if (!empty($productVariationGroups)) : ?>
                                                                <td class="text-center justify-content-center  align-items-center align-middle my-auto py-auto text-break">
                                                                    <?php foreach ($productVariationGroups as $key => $value) : ?>
                                                                        <?php if (!empty($value->variation_id)) : ?>
                                                                            <?php foreach (explode(",", $value->variation_id) as $k => $v) : ?>
                                                                                <?php $variation = $this->general_model->get("product_variations", null, ["id" => $v,"lang" => $this->session->userdata("activeLang")]); ?>
                                                                                <label><?= $variation->title ?></label>
                                                                            <?php endforeach ?>
                                                                        <?php endif ?>
                                                                        <input class="form-control text-center mb-2 rounded-0" type="text" min="1" name="price[<?= $product->id ?>][<?= $value->id ?>]" value="<?= $value->price ?>">
                                                                    <?php endforeach ?>
                                                                </td>
                                                            <?php endif ?>
                                                            <?php if (!empty($productVariationGroups)) : ?>
                                                                <td class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">
                                                                    <?php foreach ($productVariationGroups as $key => $value) : ?>
                                                                        <?php if (!empty($value->variation_id)) : ?>
                                                                            <?php foreach (explode(",", $value->variation_id) as $k => $v) : ?>
                                                                                <?php $variation = $this->general_model->get("product_variations", null, ["id" => $v,"lang" => $this->session->userdata("activeLang")]); ?>
                                                                                <label><?= $variation->title ?></label>
                                                                            <?php endforeach ?>
                                                                        <?php endif ?>
                                                                        <input class="form-control text-center mb-2 rounded-0" type="text" min="1" name="stock[<?= $product->id ?>][<?= $value->id ?>]" value="<?= $value->stock ?>">
                                                                    <?php endforeach ?>
                                                                </td>
                                                            <?php endif ?>
                                                            <?php if (!empty($productVariationGroups)) : ?>
                                                                <td class="text-center justify-content-center align-items-center align-middle my-auto py-auto text-break">
                                                                    <?php foreach ($productVariationGroups as $key => $value) : ?>
                                                                        <?php if (!empty($value->variation_id)) : ?>
                                                                            <?php foreach (explode(",", $value->variation_id) as $k => $v) : ?>
                                                                                <?php $variation = $this->general_model->get("product_variations", null, ["id" => $v,"lang" => $this->session->userdata("activeLang")]); ?>
                                                                                <label><?= $variation->title ?></label>
                                                                            <?php endforeach ?>
                                                                        <?php endif ?>
                                                                        <input class="form-control text-center mb-2 rounded-0" type="text" min="1" name="discount[<?= $product->id ?>][<?= $value->id ?>]" value="<?= $value->discount ?>">
                                                                    <?php endforeach ?>
                                                                </td>
                                                            <?php endif ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="my-3">
                            <?= $links ?>
                        </div>
                        <button role="button" data-url="<?= base_url("products/bulkupdate"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnUpdate">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif ?>
<script>
    $(document).ready(function() {
        $(document).on("click", ".toggleMainColumn", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if ($(".mainColumn").css("display") == "none") {
                $(".mainColumn").css("display", "table-cell");
            } else {
                $(".mainColumn").css("display", "none");
            }
        });
        $(document).on("click", ".btnUpdate", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let url = $(this).data("url");
            let formData = new FormData(document.getElementById("updateProduct"));
            createAjax(url, formData, function() {});
        });
    });
</script>