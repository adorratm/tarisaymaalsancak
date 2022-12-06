<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= !empty($item->banner_url) ? get_picture("pages_v", $item->banner_url) : get_picture("settings_v", $settings->about_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", $item->title) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", $item->title) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="life-gambo">
    <div class="container">
        <div class="row">
            <div class="<?= empty($item->img_url) ? "col-lg-12" : "col-lg-6" ?>">
                <div class="default-title left-text">
                    <h2><?= $item->title ?></h2>
                    <p><?= $settings->company_name ?></p>
                </div>
                <div class="about-content">
                    <?= $item->content ?>
                </div>
            </div>
            <?php if (!empty($item->img_url)) : ?>
                <div class="col-lg-6">
                    <div class="about-img">
                        <img width="1000" height="1000" loading="lazy" data-src="<?= get_picture("pages_v", $item->img_url) ?>" alt="<?= $item->title ?>" class="img-fluid lazyload">
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>