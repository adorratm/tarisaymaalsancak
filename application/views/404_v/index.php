<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= get_picture("settings_v", $settings->about_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", lang("pageNotFound")) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", lang("pageNotFound")) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Error-Area -->
<div class="section145">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="erorr_wrapper">
                    <img loading="lazy" data-src="<?= asset_url("public/images/404.webp") ?>" alt="<?= lang("pageNotFound") ?>" class="img-fluid lazyload" width="550" height="515">
                    <h1 class="my-3"><?= lang("pageNotFound") ?></h1>
                    <h3 class="my-3"><?= lang("404Desc") ?></h3>
                    <div class="more-product-btn">
                        <a rel="dofollow" href="<?= base_url() ?>" title="<?= lang("404Home") ?>" class="show-more-btn hover-btn"><?= lang("404Home") ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>