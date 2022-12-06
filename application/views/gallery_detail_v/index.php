<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!--=========================-->
<!--=       Breadcrumb      =-->
<!--=========================-->

<!--====== Page Banner Start ======-->

<section class="page-banner bg_cover" style="background-image: url(<?= get_picture("settings_v",$settings->gallery_logo) ?>);">
    <div class="container">
        <div class="page-banner-content text-center">
            <h2 class="title"><?= strto("lower|upper", $gallery->title) ?></h2>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|upper", lang("home")) ?>"><?= strto("lower|upper", lang("home")) ?></a></li>
                <li class="breadcrumb-item"><a rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|upper", lang("galleries")) ?>"><?= strto("lower|upper", lang("galleries")) ?></a></li>
                <li class="breadcrumb-item active"><?= strto("lower|upper", $gallery->title) ?></li>
            </ol>
        </div>
    </div>
</section>

<!--====== Page Banner Ends ======-->


<!--=========================-->
<!--=       Breadcrumb      =-->
<!--=========================-->

<!-- Start Blog Slider Section -->
<section class="shop-page pt-80 pb-80">
    <div class="container shop-container">
        <div class="collection-content">
            <div class="row <?= ($gallery->gallery_type != "files" ? "gallery-slider" : null) ?>" <?= ($gallery->gallery_type != "files" ? "itemscope" : null) ?>>
                <?php foreach ($gallery_items as $key => $value) : ?>
                    <?php if ($gallery->gallery_type == "files") : ?>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
                            <a rel="dofollow" href="<?= get_picture("galleries_v/{$gallery->gallery_type}/{$gallery->folder_name}", $value->url) ?>" alt="<?= $value->title ?>" download><i class="fa fa-cloud-download bx-2x"></i> <?= $value->url ?></a>
                        </div>
                    <?php else : ?>
                        <figure class="col-12 col-sm-12 col-md-12 <?=($gallery->gallery_type == "videos" || $gallery->gallery_type == "video_urls" ? "col-lg-12 col-xl-12" : "col-lg-4 col-xl-3") ?> d-flex justify-content-center text-center" itemprop="associatedMedia" itemscope>
                            <?php if ($gallery->gallery_type == "videos") : ?>
                                <video id="my-video<?= $key ?>" controls preload="auto" width="100%">
                                    <source src="<?= get_picture("galleries_v/{$gallery->gallery_type}/{$gallery->folder_name}", $value->url) ?>" />
                                </video>
                            <?php elseif ($gallery->gallery_type == "video_urls") : ?>
                                <?= htmlspecialchars_decode($value->url) ?>
                            <?php else : ?>
                                <?php list($width, $height) = getimagesize(get_picture("galleries_v/{$gallery->gallery_type}/{$gallery->folder_name}", $value->url)); ?>
                                <a rel="dofollow" href="<?= get_picture("galleries_v/{$gallery->gallery_type}/{$gallery->folder_name}", $value->url) ?>" title="<?= lang("viewItem") ?>" itemprop="contentUrl" data-size="<?= $width ?>x<?= $height ?>">
                                    <picture>
                                        <img width="1920" height="1280" loading="lazy" class="img-fluid lazyload w-100" src="<?= get_picture("galleries_v/{$gallery->gallery_type}/{$gallery->folder_name}", $value->url) ?>" data-src="<?= get_picture("galleries_v/{$gallery->gallery_type}/{$gallery->folder_name}", $value->url) ?>" alt="<?= $value->title ?>" itemprop="thumbnail" style="object-fit:scale-down;height:315px;" width="457" height="315">
                                    </picture>
                                    <figcaption itemprop="caption description">
                                        <small><?= $value->title ?></small>
                                        <?= $value->description ?>
                                    </figcaption>
                                </a>
                            <?php endif ?>
                        </figure>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>
<!-- End Blog Slider Section -->