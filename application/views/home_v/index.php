<?php if (!empty($slides)) : ?>
    <div class="main-banner-slider">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="owl-carousel offers-banner owl-theme">
                        <?php $i = 0 ?>
                        <?php foreach ($slides as $key => $value) : ?>
                            <?php if (strtotime($value->sharedAt) <= strtotime("now")) : ?>
                                <?php if ($value->allowButton) : ?>
                                    <?php $sUrl = null; ?>
                                    <?php if (!empty($value->button_url)) : ?>
                                        <?php $sUrl = $value->button_url ?>
                                    <?php endif ?>
                                    <?php if (!empty($value->category_id) && $value->category_id > 0) : ?>
                                        <?php $sCategory = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $value->category_id]); ?>
                                        <?php if (!empty($sCategory)) : ?>
                                            <?php $sUrl = base_url(lang("routes_products") . "/" . $sCategory->seo_url) ?>
                                        <?php endif ?>
                                    <?php endif ?>
                                    <?php if (!empty($value->product_id) && $value->product_id > 0) : ?>
                                        <?php $sProduct = $this->general_model->get("products", null, ["isActive" => 1, "id" => $value->product_id]); ?>
                                        <?php if (!empty($sProduct)) : ?>
                                            <?php $sUrl = base_url(lang("routes_products") . "/" . lang("routes_product") . "/" . $sProduct->url) ?>
                                        <?php endif ?>
                                    <?php endif ?>
                                    <?php if (!empty($value->page_id) && $value->page_id > 0) : ?>
                                        <?php $sPage = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $value->page_id]); ?>
                                        <?php if (!empty($sPage)) : ?>
                                            <?php $sUrl = base_url(lang("routes_products") . "/" . $sPage->seo_url) ?>
                                        <?php endif ?>
                                    <?php endif ?>
                                <?php endif ?>
                                <div class="item">
                                    <div class="offer-item">
                                        <div class="offer-item-img">
                                            <div class="gambo-overlay"></div>
                                            <img loading="lazy" data-src="<?= get_picture("slides_v", $value->img_url) ?>" alt="<?= $value->title ?>" class="lazyload img-fluid w-100">
                                        </div>
                                        <div class="offer-text-dt">
                                            <div class="offer-top-text-banner">
                                                <div class="top-text-1"><?= $value->title ?></div>
                                                <span><?= $value->description ?></span>
                                            </div>
                                            <?php if ($value->allowButton) : ?>
                                                <a rel="dofollow" title="<?= $value->button_caption ?>" href="<?= $sUrl ?>" target="<?= $value->target ?>" class="Offer-shop-btn hover-btn"><?= $value->button_caption ?></a>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++ ?>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if (!empty($menuCategories)) : ?>
    <div class="section145">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-title-tt">
                        <div class="main-title-left justify-content-center mx-auto text-center align-items-center align-self-center align-content-center">
                            <span><?= $settings->company_name ?></span>
                            <h2><?= lang("productCategories") ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="owl-carousel cate-slider owl-theme">
                        <?php $i = 0 ?>
                        <?php foreach ($menuCategories as $key => $value) : ?>
                            <div class="item">
                                <a rel="dofollow" title="<?= $value->title ?>" href="<?= base_url(lang("routes_products") . "/" . $value->seo_url) ?>" class="category-item">
                                    <div class="cate-img">
                                        <img loading="lazy" width="100" height="100" data-src="<?= get_picture("product_categories_v", $value->icon) ?>" class="img-fluid w-100 lazyload" alt="<?= $value->title ?>">
                                    </div>
                                    <h4><?= $value->title ?></h4>
                                </a>
                            </div>
                            <?php $i++ ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if (!empty($menuCategories)) : ?>
    <?php
    $wheres["p.isActive"] = 1;
    $wheres["pi.isCover"] = 1;
    $wheres["p.lang"] = $this->viewData->lang;
    //$wheres["p.isWeddingProduct"] = 0;
    $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_categories pc" => ["pwc.category_id = pc.id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
    $select = "GROUP_CONCAT(pc.seo_url) category_seos,GROUP_CONCAT(pc.title) category_titles,GROUP_CONCAT(pc.id) category_ids,p.id,p.title,p.url,pi.url img_url,p.price price,p.discount discount,p.stock stock,p.stockStatus stockStatus,p.isDiscount isDiscount,p.sharedAt";
    $distinct = true;
    $groupBy = ["p.id", "pwc.product_id"];
    ?>
    <?php foreach ($menuCategories as $mkey => $mvalue) : ?>
        <?php if ($mvalue->top_id == 0) : ?>
            <?php
            $wheres["pc.id"] = $mvalue->id;

            /**
             * Get Home Products
             */
            $homeProducts = $this->general_model->get_all("products p", $select, "RAND()", $wheres, [], $joins, [12], [], $distinct, $groupBy);
            ?>
            <?php if (!empty($homeProducts)) : ?>
                <?php $this->load->view("includes/productSlider", ["data" => $homeProducts, "title" => $mvalue->title, "userWishlists" => $userWishlists, "formatter" => $formatter, "home_url" => $mvalue->home_url]) ?>
            <?php endif ?>
        <?php endif ?>
    <?php endforeach ?>
<?php endif ?>

<?php if (!empty($homeitems[0])) : ?>
    <?php if (strtotime($homeitems[0]->sharedAt) <= strtotime("now")) : ?>
        <?php if ($homeitems[0]->allowButton) : ?>
            <?php $sUrl = null; ?>
            <?php if (!empty($homeitems[0]->button_url)) : ?>
                <?php $sUrl = $homeitems[0]->button_url ?>
            <?php endif ?>
            <?php if (!empty($homeitems[0]->category_id) && $homeitems[0]->category_id > 0) : ?>
                <?php $sCategory = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[0]->category_id]); ?>
                <?php if (!empty($sCategory)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sCategory->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[0]->product_id) && $homeitems[0]->product_id > 0) : ?>
                <?php $sProduct = $this->general_model->get("products", null, ["isActive" => 1, "id" => $homeitems[0]->product_id]); ?>
                <?php if (!empty($sProduct)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . lang("routes_product") . "/" . $sProduct->url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[0]->page_id) && $homeitems[0]->page_id > 0) : ?>
                <?php $sPage = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[0]->page_id]); ?>
                <?php if (!empty($sPage)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sPage->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>
        <div class="section145">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-title-tt justify-content-center mx-auto text-center">
                            <div class="main-title-left justify-content-center mx-auto text-center">
                                <span><?= $settings->company_name ?></span>
                                <h2><?= $homeitems[0]->title ?></h2>
                                <p>
                                    <?= $homeitems[0]->content ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php if ($homeitems[0]->allowButton) : ?>
                            <a rel="dofollow" href="<?= $sUrl ?>" target="<?= $homeitems[0]->target ?>" class="code-offer-item" title="<?= $homeitems[0]->button_caption ?>">
                                <img loading="lazy" class="img-fluid w-100 lazyload" data-src="<?= get_picture("homeitems_v", $homeitems[0]->img_url) ?>" alt="<?= $homeitems[0]->title ?>">
                            </a>
                        <?php else : ?>
                            <img loading="lazy" class="img-fluid w-100 lazyload" data-src="<?= get_picture("homeitems_v", $homeitems[0]->img_url) ?>" alt="<?= $homeitems[0]->title ?>">
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>

<?php if (!empty($mostlyViewedProducts)) : ?>
    <?php $this->load->view("includes/productSlider", ["data" => $mostlyViewedProducts, "title" => lang("mostlyViewedProducts"), "userWishlists" => $userWishlists, "formatter" => $formatter, "home_url" => null]) ?>
<?php endif ?>

<?php if (!empty($homeitems[1])) : ?>
    <?php if (strtotime($homeitems[1]->sharedAt) <= strtotime("now")) : ?>
        <?php if ($homeitems[1]->allowButton) : ?>
            <?php $sUrl = null; ?>
            <?php if (!empty($homeitems[1]->button_url)) : ?>
                <?php $sUrl = $homeitems[1]->button_url ?>
            <?php endif ?>
            <?php if (!empty($homeitems[1]->category_id) && $homeitems[1]->category_id > 0) : ?>
                <?php $sCategory = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[1]->category_id]); ?>
                <?php if (!empty($sCategory)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sCategory->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[1]->product_id) && $homeitems[1]->product_id > 0) : ?>
                <?php $sProduct = $this->general_model->get("products", null, ["isActive" => 1, "id" => $homeitems[1]->product_id]); ?>
                <?php if (!empty($sProduct)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . lang("routes_product") . "/" . $sProduct->url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[1]->page_id) && $homeitems[1]->page_id > 0) : ?>
                <?php $sPage = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[1]->page_id]); ?>
                <?php if (!empty($sPage)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sPage->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>
        <div class="section145">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-title-tt justify-content-center mx-auto text-center">
                            <div class="main-title-left justify-content-center mx-auto text-center">
                                <span><?= $settings->company_name ?></span>
                                <h2><?= $homeitems[1]->title ?></h2>
                                <p>
                                    <?= $homeitems[1]->content ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php if ($homeitems[1]->allowButton) : ?>
                            <a rel="dofollow" href="<?= $sUrl ?>" target="<?= $homeitems[1]->target ?>" class="code-offer-item" title="<?= $homeitems[1]->button_caption ?>">
                                <img loading="lazy" class="img-fluid w-100 lazyload" data-src="<?= get_picture("homeitems_v", $homeitems[1]->img_url) ?>" alt="<?= $homeitems[1]->title ?>">
                            </a>
                        <?php else : ?>
                            <img loading="lazy" class="img-fluid w-100 lazyload" data-src="<?= get_picture("homeitems_v", $homeitems[1]->img_url) ?>" alt="<?= $homeitems[1]->title ?>">
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>

<?php if (!empty($suggestedProducts)) : ?>
    <?php $this->load->view("includes/productSlider", ["data" => $suggestedProducts, "title" => lang("suggestedProducts"), "userWishlists" => $userWishlists, "formatter" => $formatter, "home_url" => null]) ?>
<?php endif ?>

<?php if (!empty($homeitems[2])) : ?>
    <?php if (strtotime($homeitems[2]->sharedAt) <= strtotime("now")) : ?>
        <?php if ($homeitems[2]->allowButton) : ?>
            <?php $sUrl = null; ?>
            <?php if (!empty($homeitems[2]->button_url)) : ?>
                <?php $sUrl = $homeitems[2]->button_url ?>
            <?php endif ?>
            <?php if (!empty($homeitems[2]->category_id) && $homeitems[2]->category_id > 0) : ?>
                <?php $sCategory = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[2]->category_id]); ?>
                <?php if (!empty($sCategory)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sCategory->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[2]->product_id) && $homeitems[2]->product_id > 0) : ?>
                <?php $sProduct = $this->general_model->get("products", null, ["isActive" => 1, "id" => $homeitems[2]->product_id]); ?>
                <?php if (!empty($sProduct)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . lang("routes_product") . "/" . $sProduct->url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[2]->page_id) && $homeitems[2]->page_id > 0) : ?>
                <?php $sPage = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[2]->page_id]); ?>
                <?php if (!empty($sPage)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sPage->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>
        <div class="section145">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-title-tt justify-content-center mx-auto text-center">
                            <div class="main-title-left justify-content-center mx-auto text-center">
                                <span><?= $settings->company_name ?></span>
                                <h2><?= $homeitems[2]->title ?></h2>
                                <p>
                                    <?= $homeitems[2]->content ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php if ($homeitems[2]->allowButton) : ?>
                            <a rel="dofollow" href="<?= $sUrl ?>" target="<?= $homeitems[2]->target ?>" class="code-offer-item" title="<?= $homeitems[2]->button_caption ?>">
                            <?php endif ?>
                            <img loading="lazy" class="img-fluid w-100 lazyload" data-src="<?= get_picture("homeitems_v", $homeitems[2]->img_url) ?>" alt="<?= $homeitems[2]->title ?>">
                            <?php if ($homeitems[2]->allowButton) : ?>
                            </a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>

<?php if (!empty($newProducts)) : ?>
    <?php $this->load->view("includes/productSlider", ["data" => $newProducts, "title" => lang("newProducts"), "userWishlists" => $userWishlists, "formatter" => $formatter, "home_url" => null]) ?>
<?php endif ?>

<?php if (!empty($homeitems[3])) : ?>
    <?php if (strtotime($homeitems[3]->sharedAt) <= strtotime("now")) : ?>
        <?php if ($homeitems[3]->allowButton) : ?>
            <?php $sUrl = null; ?>
            <?php if (!empty($homeitems[3]->button_url)) : ?>
                <?php $sUrl = $homeitems[3]->button_url ?>
            <?php endif ?>
            <?php if (!empty($homeitems[3]->category_id) && $homeitems[3]->category_id > 0) : ?>
                <?php $sCategory = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[3]->category_id]); ?>
                <?php if (!empty($sCategory)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sCategory->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[3]->product_id) && $homeitems[3]->product_id > 0) : ?>
                <?php $sProduct = $this->general_model->get("products", null, ["isActive" => 1, "id" => $homeitems[3]->product_id]); ?>
                <?php if (!empty($sProduct)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . lang("routes_product") . "/" . $sProduct->url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[3]->page_id) && $homeitems[3]->page_id > 0) : ?>
                <?php $sPage = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[3]->page_id]); ?>
                <?php if (!empty($sPage)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sPage->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>
        <div class="section145">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-title-tt justify-content-center mx-auto text-center">
                            <div class="main-title-left justify-content-center mx-auto text-center">
                                <span><?= $settings->company_name ?></span>
                                <h2><?= $homeitems[3]->title ?></h2>
                                <p>
                                    <?= $homeitems[3]->content ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php if ($homeitems[3]->allowButton) : ?>
                            <a rel="dofollow" href="<?= $sUrl ?>" target="<?= $homeitems[3]->target ?>" class="code-offer-item" title="<?= $homeitems[3]->button_caption ?>">
                                <img loading="lazy" class="img-fluid w-100 lazyload" data-src="<?= get_picture("homeitems_v", $homeitems[3]->img_url) ?>" alt="<?= $homeitems[3]->title ?>">
                            </a>
                        <?php else : ?>
                            <img loading="lazy" class="img-fluid w-100 lazyload" data-src="<?= get_picture("homeitems_v", $homeitems[3]->img_url) ?>" alt="<?= $homeitems[3]->title ?>">
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>

<?php if (!empty($discountProducts)) : ?>
    <?php $this->load->view("includes/productSlider", ["data" => $discountProducts, "title" => lang("discountProducts"), "userWishlists" => $userWishlists, "formatter" => $formatter, "home_url" => null]) ?>
<?php endif ?>

<?php if (!empty($homeitems[4])) : ?>
    <?php if (strtotime($homeitems[4]->sharedAt) <= strtotime("now")) : ?>
        <?php if ($homeitems[4]->allowButton) : ?>
            <?php $sUrl = null; ?>
            <?php if (!empty($homeitems[4]->button_url)) : ?>
                <?php $sUrl = $homeitems[4]->button_url ?>
            <?php endif ?>
            <?php if (!empty($homeitems[4]->category_id) && $homeitems[4]->category_id > 0) : ?>
                <?php $sCategory = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[4]->category_id]); ?>
                <?php if (!empty($sCategory)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sCategory->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[4]->product_id) && $homeitems[4]->product_id > 0) : ?>
                <?php $sProduct = $this->general_model->get("products", null, ["isActive" => 1, "id" => $homeitems[4]->product_id]); ?>
                <?php if (!empty($sProduct)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . lang("routes_product") . "/" . $sProduct->url) ?>
                <?php endif ?>
            <?php endif ?>
            <?php if (!empty($homeitems[4]->page_id) && $homeitems[4]->page_id > 0) : ?>
                <?php $sPage = $this->general_model->get("product_categories", null, ["isActive" => 1, "id" => $homeitems[4]->page_id]); ?>
                <?php if (!empty($sPage)) : ?>
                    <?php $sUrl = base_url(lang("routes_products") . "/" . $sPage->seo_url) ?>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>
        <div class="section145">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-title-tt justify-content-center mx-auto text-center">
                            <div class="main-title-left justify-content-center mx-auto text-center">
                                <span><?= $settings->company_name ?></span>
                                <h2><?= $homeitems[4]->title ?></h2>
                                <p>
                                    <?= $homeitems[4]->content ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php if ($homeitems[4]->allowButton) : ?>
                            <a rel="dofollow" href="<?= $sUrl ?>" target="<?= $homeitems[4]->target ?>" class="code-offer-item" title="<?= $homeitems[4]->button_caption ?>">
                                <img loading="lazy" class="img-fluid w-100 lazyload" data-src="<?= get_picture("homeitems_v", $homeitems[4]->img_url) ?>" alt="<?= $homeitems[4]->title ?>">
                            </a>
                        <?php else : ?>
                            <img loading="lazy" class="img-fluid w-100 lazyload" data-src="<?= get_picture("homeitems_v", $homeitems[4]->img_url) ?>" alt="<?= $homeitems[4]->title ?>">
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>