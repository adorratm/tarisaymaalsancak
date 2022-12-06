<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!--=========================-->
<!--=       Breadcrumb      =-->
<!--=========================-->

<section class="page-banner bg_cover" style="background-image: url(<?= get_picture("settings_v",$settings->gallery_logo) ?>);">
    <div class="container">
        <div class="page-banner-content text-center">
            <h2 class="title"><?= strto("lower|upper", lang("galleries")) ?></h2>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= strto("lower|upper", lang("home")) ?></a></li>
                <li class="breadcrumb-item active"><?= strto("lower|upper", lang("galleries")) ?></li>
            </ol>
        </div>
    </div>
</section>

<!--=========================-->
<!--=       Breadcrumb      =-->
<!--=========================-->

<!--=========================-->
<!--=        Collection area          =-->
<!--=========================-->

<section class="shop-page pt-80 pb-80">
    <div class="container shop-container">
        <div class="collection-content">
            <div class="row">
                <?php foreach ($galleries as $key => $value) : ?>
                    <?php if (strtotime($value->sharedAt) <= strtotime("now")) : ?>
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <div class="single-collection">
                                <a class="image-link" rel="dofollow" href="<?= base_url(lang("routes_galleries") . "/" . lang("routes_gallery") . "/{$value->url}") ?>" title="<?= $value->title ?>">
                                    <picture>
                                        <img width="1920" height="1280" loading="lazy" data-src="<?= get_picture("galleries_v/{$value->gallery_type}/{$value->folder_name}", $value->img_url) ?>" alt="<?= $value->title ?>" class="img-fluid w-100 lazyload swiper-lazy" width="328" height="492">
                                    </picture>
                                </a>
                                <h5 class="text-center mt-3"><a rel="dofollow" href="<?= base_url(lang("routes_galleries") . "/" . lang("routes_gallery") . "/{$value->url}") ?>" title="<?= $value->title ?>"><?= $value->title ?></a></h5>
                                <a class="read-more-btn icon-space-left text-center" rel="dofollow" href="<?= base_url(lang("routes_galleries") . "/" . lang("routes_gallery") . "/{$value->url}") ?>" title="<?= $value->title ?>"><?= lang("viewGallery") ?> <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
            <div class="load-more-wrapper">
                <?= $links ?>
            </div>
            <!-- /.load-more-wrapper -->
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.shop-area -->