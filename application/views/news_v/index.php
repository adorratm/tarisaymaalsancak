<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!--=========================-->
<!--=        Breadcrumb         =-->
<!--=========================-->


<section class="page-banner bg_cover" style="background-image: url(<?= get_picture("settings_v", $settings->news_logo) ?>);">
    <div class="container">
        <div class="page-banner-content text-center">
            <h2 class="title"><?= strto("lower|upper", lang("news")); ?></h2>
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="<?= base_url(); ?>"><?= strto("lower|upper", lang("home")) ?></a></li>
                <li class="breadcrumb-item active"><?= strto("lower|upper", lang("news")); ?></li>
            </ol>
        </div>
    </div>
</section>

<!--=========================-->
<!--=   Product Filter  =-->
<!--=========================-->

<!--====== Blog Start ======-->

<section class="blog-page pt-20 pb-120">
    <div class="container">
        <div class="row justify-content-between flex-lg-row-reverse">
            <?php foreach ($news as $key => $value) : ?>
                <?php if (strtotime($value->sharedAt) <= strtotime("now")) : ?>
                    <div class="col-lg-8">
                        <div class="single-blog mt-80">
                            <div class="blog-image">
                                <a rel="dofollow" href="<?= base_url(lang("routes_news") . "/" . lang("routes_newss") . "/{$value->seo_url}") ?>" title="<?= $value->title ?>">
                                    <img width="1920" height="1280" loading="lazy" data-src="<?= get_picture("news_v", $value->img_url) ?>" alt="<?= $value->title ?>" class="lazyload img-fluid" width="400" height="400">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                            <div class="blog-content">
                                <ul class="blog-category">
                                    <?php foreach ($categories as $k => $v) : ?>
                                        <?php if ($v->id == $value->category_id) : ?>
                                            <li> <a rel="dofollow" href="<?= base_url(lang("routes_news") . "/{$v->seo_url}") ?>" title="<?= $v->title ?>"><?= $v->title ?></a></li>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </ul>
                                <h4 class="title"><a class="title" rel="dofollow" href="<?= base_url(lang("routes_news") . "/" . lang("routes_newss") . "/{$value->seo_url}") ?>" title="<?= $value->title ?>"><?= $value->title ?></a></h4>
                                <ul class="blog-meta">
                                    <li><a href="javascript:void(0)"><i class="fa fa-time"></i> <?= iconv("ISO-8859-9", "UTF-8", strftime("%d %B %Y, %A %X", strtotime($value->updatedAt))); ?></a></li>
                                </ul>
                                <p><?= mb_word_wrap($value->content, 500, "...") ?></p>
                                <a class="main-btn" rel="dofollow" href="<?= base_url(lang("routes_news") . "/" . lang("routes_newss") . "/{$value->seo_url}") ?>" title="<?= $value->title ?>"><?= lang("viewNews") ?></a>
                            </div>
                        </div>
                        <div class="d-flex px-auto mx-auto text-center justify-content-center">
                            <?= $links ?>
                        </div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>

            <div class="col-lg-3">
                <div class="blog-sidebar">
                    <div class="blog-sidebar-search mt-80">
                        <h4 class="widget-title-custom">
                            <?= lang("searchNews") ?>
                        </h4>
                        <hr>
                        <div class="sidebar-search-form">
                            <?php $csrf = array(
                                'name' => $this->security->get_csrf_token_name(),
                                'hash' => $this->security->get_csrf_hash()
                            ); ?>
                            <form class="d-flex" action="<?= !empty($this->uri->segment(3) && !is_numeric($this->uri->segment(3))) ? base_url(lang("routes_news") . "/" . $this->uri->segment(3)) : base_url(lang("routes_news")) ?>" method="GET" enctype="multipart/form-data">
                                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                <input class="form-control" name="search" type="search" placeholder="<?= lang("searchNews") ?>..." required>
                                <button aria-label="<?=$settings->company_name?>" class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>

                    <div class="blog-sidebar-category mt-50">
                        <h4 class="sidebar-title"><?= lang("newsCategories") ?></h4>
                        <hr>

                        <div class="sidebar-category">
                            <ul class="blog-category">
                                <?php foreach ($categories as $k => $v) : ?>
                                    <?php if ($v->id == $value->category_id) : ?>
                                        <li> <a rel="dofollow" href="<?= base_url(lang("routes_news") . "/{$v->seo_url}") ?>" title="<?= $v->title ?>"><?= $v->title ?></a></li>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>

                    <?php if (!empty($latestNews)) : ?>
                        <div class="blog-sidebar-post mt-50">
                            <h4 class="sidebar-title"><?= lang("latestNews") ?></h4>
                            <hr>
                            <?php foreach ($latestNews as $key => $value) : ?>
                                <?php if (strtotime($value->sharedAt) <= strtotime("now")) : ?>
                                    <div class="sidebar-post">
                                        <div class="single-post">
                                            <div class="post-thumb">
                                                <a href="<?= base_url(lang("routes_news") . "/" . lang("routes_newss") . "/{$value->seo_url}") ?>"> <img width="1920" height="1280" loading="lazy" data-src="<?= get_picture("news_v", $value->img_url) ?>" alt="<?= $value->title ?>" class="lazyload img-fluid" width="150" height="150">
                                                </a>
                                            </div>
                                            <div class="post-content">
                                                <h6 class="title"><a rel="dofollow" href="<?= base_url(lang("routes_news") . "/" . lang("routes_newss") . "/{$value->seo_url}") ?>" title="<?= $value->title ?>"><?= $value->title ?></a></h6>
                                                <span class="date"><a><?= iconv("ISO-8859-9", "UTF-8", strftime("%d %B %Y, %A %X", strtotime($value->updatedAt))); ?></a></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!--====== Blog Ends ======-->