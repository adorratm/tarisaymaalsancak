<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= !empty($products_category) ? get_picture("product_categories_v", $products_category->banner_url) : get_picture("settings_v", $settings->product_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <?php if (!empty($products_category)) : ?>
                                <a class="nav-item nav-link" href="<?= base_url(lang("routes_products")); ?>" rel="dofollow" title="<?= strto("lower|ucwords", lang("products")) ?>"><?= strto("lower|ucwords", lang("products")) ?></a>
                                <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", $products_category->title) ?></a>
                            <?php else : ?>
                                <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords",  lang("products")) ?></a>
                            <?php endif ?>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", !empty($products_category) ?  $products_category->title : lang("products")) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel">
    <div class="offcanvas-header bs-canvas-header side-cart-header p-3">
        <div class="d-inline-block main-cart-title" id="offcanvasFilterLabel"><?= lang("filter") ?></div>
        <button type="button" class="close-btn" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="uil uil-multiply"></i>
        </button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="filter-items">
            <div class="filtr-cate-title">
                <h4><?= lang("searchProduct") ?></h4>
            </div>
            <div class="filter-item-body h-auto scrollstyle_4">
                <form action="<?= !empty($this->uri->segment(2) && !is_numeric($this->uri->segment(2))) ? base_url(lang("routes_products") . "/" . $this->uri->segment(3)) : base_url(lang("routes_products")) ?>" method="GET" enctype="multipart/form-data">
                    <div class="input-group">
                        <input type="hidden" name="key" value="<?= (!empty($_GET["key"]) ? clean($_GET["key"]) : null) ?>">
                        <input type="hidden" name="orderBy" value="<?= (!empty($_GET["orderBy"]) ? $_GET["orderBy"] : "p.id DESC") ?>">
                        <input type="hidden" name="amount" value="<?= (!empty($_GET["amount"]) ? $_GET["amount"] : null) ?>">
                        <input name="search" class="form-control ms-0" type="text" placeholder="<?= lang("searchProduct") ?>..." required>
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                        <button aria-label="<?= $settings->company_name ?>" type="submit" class="btn btn-secondary"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <form id="priceForm" action="<?= !empty($this->uri->segment(3) && !is_numeric($this->uri->segment(3))) ? base_url(lang("routes_products") . "/" . $this->uri->segment(3)) : base_url(lang("routes_products")) ?>" method="GET" enctype="multipart/form-data">
            <?= show_product_categories() ?>
            <div class="filter-items">
                <div class="filtr-cate-title">
                    <h4><?= lang("filterByPrice") ?></h4>
                </div>
                <div class="price-pack-item-body scrollstyle_4">
                    <input type="hidden" name="key" value="<?= (!empty($_GET["key"]) ? clean($_GET["key"]) : null) ?>">
                    <input type="hidden" name="orderBy" value="<?= (!empty($_GET["orderBy"]) ? $_GET["orderBy"] : "p.id DESC") ?>">
                    <input type="hidden" name="search" value="<?= (!empty($_GET["search"]) ? $_GET["search"] : null) ?>">

                    <div class="input-group align-items-center align-self-center align-content-center mb-2">
                        <input type="text" class="form-control rounded-0" id="amountMin" name="amountMin" value="<?= str_replace(",", "", number_format(@floor($minPrice) <= 0 ? 0 : @floor($minPrice), 2)) ?>"><span class="mx-3"><?= $symbol ?></span>
                        <input type="text" class="form-control rounded-0" id="amountMax" name="amountMax" value="<?= str_replace(",", "", number_format(@ceil($maxPrice), 2)) ?>"><span class="mx-3"><?= $symbol ?></span>
                    </div>


                    <button aria-label="<?= $settings->company_name ?>" type="submit" class="btn btn-secondary w-100"><?= lang("applyFilter") ?></button>
                    <?php if (!empty($_GET)) : ?>
                        <a rel="dofollow" title="<?= lang("clearFilter") ?>" class="btn btn-danger text-white mt-2 text-center w-100" href="<?= base_url(lang("routes_products") . "/" . $this->uri->segment(3) . (!empty($_GET["key"]) ? "?key=" . clean($_GET["key"]) : null)) ?>"><?= lang("clearFilter") ?></a>
                    <?php endif ?>
                </div>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
            </div>
        </form>
    </div>
</div>

<!-- Shop Main Area -->
<div class="all-product-grid">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-top-dt">
                    <div class="product-left-title">
                        <h2><?= strto("lower|ucwords", !empty($products_category) ?  $products_category->title : lang("products")) ?> # <?= $offset == 0 ? (!empty($products) ? 1 : 0) : (empty($products) ? 0 : $offset) ?>–<?= $total_rows > $offset + $per_page ? (empty($products) ? 0 : $offset + $per_page) : (empty($products) ? 0 : $total_rows) ?> / <?= empty($products) ? 0 : $total_rows ?></h2>
                    </div>
                    <a href="#" class="filter-btn w-auto px-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter"><?= lang("filters") ?></a>
                    <div class="product-sort main-form">
                        <select class="selectpicker" data-width="25%" id="orderBy" onchange="$('input[name=orderBy]').val($(this).val());$('#priceForm').submit()">
                            <option <?= (!empty($_GET["orderBy"]) && $_GET["orderBy"] == "id DESC" ? "selected" : null) ?> value="id DESC"><?= lang("sortByNew") ?></option>
                            <option <?= (!empty($_GET["orderBy"]) && $_GET["orderBy"] == "id ASC" ? "selected" : null) ?> value="id ASC"><?= lang("sortByOld") ?></option>
                            <option <?= (!empty($_GET["orderBy"]) && $_GET["orderBy"] == "(CASE when isDiscount = 1 then discountedPrice else newPrice end) ASC" ? "selected" : null) ?> value='(CASE when isDiscount = 1 then discountedPrice else newPrice end) ASC'><?= lang("sortByPriceAsc") ?></option>
                            <option <?= (!empty($_GET["orderBy"]) && $_GET["orderBy"] == "(CASE when isDiscount = 1 then discountedPrice else newPrice end) DESC" ? "selected" : null) ?> value='(CASE when isDiscount = 1 then discountedPrice else newPrice end) DESC'><?= lang("sortByPriceDesc") ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-list-view">
            <div class="row">
                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $key => $value) : ?>
                        <?php if (strtotime($value->sharedAt) <= strtotime("now")) : ?>
                            <div class="col-lg-3 col-md-6">
                                <div class="product-item mb-30">
                                    <a href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$value->url}" . (!empty($_GET["key"]) ? "?key=" . clean($_GET["key"]) : null)) ?>" class="product-img">
                                        <img width="1000" height="1000" loading="lazy" data-src="<?= get_picture("products_v", $value->img_url) ?>" alt="<?= $value->title ?>" class="img-fluid lazyload">
                                        <div class="product-absolute-options">
                                            <?php if ($value->isDiscount && (int)$value->discount > 0) : ?>
                                                <span class="offer-badge-1">%<?= (int)$value->discount ?> <?= strto("lower|upper", lang("discount")) ?></span>
                                            <?php endif ?>
                                            <span class="like-icon addToWishlist <?= checkWishlist($userWishlists, $value->id) ?>" data-product-id="<?= $value->id ?>"></span>
                                        </div>
                                    </a>
                                    <div class="product-text-dt">
                                        <p>
                                            <?php $i = 1 ?>
                                            <?php $count = count(explode(",", $value->category_ids)) ?>
                                            <?php foreach (explode(",", $value->category_titles) as $k => $v) : ?>
                                                <?php $seo_url = explode(",", $value->category_seos)[$k]; ?>
                                                <?php if ($i < $count) : ?>
                                                    <a class="text-muted" rel="dofollow" href="<?= base_url(lang("routes_products") . "/{$seo_url}") ?>" title="<?= $v ?>"><?= $v ?></a>,
                                                <?php else : ?>
                                                    <a class="text-muted" rel="dofollow" href="<?= base_url(lang("routes_products") . "/{$seo_url}") ?>" title="<?= $v ?>"><?= $v ?></a>
                                                <?php endif ?>
                                                <?php $i++ ?>
                                            <?php endforeach ?>
                                        </p>
                                        <h4><a class="text-muted" rel="dofollow" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$value->url}" . (!empty($_GET["key"]) ? "?key=" . clean($_GET["key"]) : null)) ?>" title="<?= $value->title ?>"><?= $value->title ?></a></h4>
                                        <div class="product-price">
                                            <?php if ($value->isDiscount && (int)$value->discount > 0) : ?>
                                                <span><?= $formatter->formatCurrency((float)$value->price, $currency) ?></span>
                                                <?= $formatter->formatCurrency((((float)$value->price) - (((float)$value->price * (float)$value->discount) / 100)), $currency) ?>
                                            <?php else : ?>
                                                <?= $formatter->formatCurrency((float)$value->price, $currency) ?>
                                            <?php endif ?>
                                        </div>
                                        <div class="qty-cart text-center justify-content-center">
                                            <a class="text-muted" href="<?= base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$value->url}" . (!empty($_GET["key"]) ? "?key=" . clean($_GET["key"]) : null)) ?>" title="<?= lang("viewProduct") ?>" class="add-to-cart offcanvas-toggle">
                                                <?= lang("viewProduct") ?> <span class="cart-icon"><i class="uil uil-shopping-cart-alt"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                    <div class="col-lg-12">
                        <!-- pagination start -->
                        <?= $links ?>
                        <!-- pagination end -->
                    </div>
                <?php else : ?>
                    <div class="col-12">
                        <div class="alert alert-danger w-100" role="alert">
                            <h4 class="alert-heading"><?= lang("cannotFindProductTitle") ?></h4>
                            <p><?= lang("cannotFindProductDesc") ?></p>
                            <hr>
                            <p><?= lang("cannotFindProductDesc1") ?></p>
                            <p><?= lang("cannotFindProductDesc2") ?></p>
                            <p><?= lang("cannotFindProductDesc3") ?></p>
                            <p><?= lang("cannotFindProductDesc4") ?></p>
                            <p><?= lang("cannotFindProductDesc5") ?></p>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($mostlyViewedProducts)) : ?>
    <?php $this->load->view("includes/productSlider", ["data" => $mostlyViewedProducts, "title" => lang("mostlyViewedProducts"), "userWishlists" => $userWishlists, "formatter" => $formatter, "home_url" => null]) ?>
<?php endif ?>

<?php if (!empty($suggestedProducts)) : ?>
    <?php $this->load->view("includes/productSlider", ["data" => $suggestedProducts, "title" => lang("suggestedProducts"), "userWishlists" => $userWishlists, "formatter" => $formatter, "home_url" => null]) ?>
<?php endif ?>

<?php if (!empty($newProducts)) : ?>
    <?php $this->load->view("includes/productSlider", ["data" => $newProducts, "title" => lang("newProducts"), "userWishlists" => $userWishlists, "formatter" => $formatter, "home_url" => null]) ?>
<?php endif ?>

<?php if (!empty($discountProducts)) : ?>
    <?php $this->load->view("includes/productSlider", ["data" => $discountProducts, "title" => lang("discountProducts"), "userWishlists" => $userWishlists, "formatter" => $formatter, "home_url" => null]) ?>
<?php endif ?>