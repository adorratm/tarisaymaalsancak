<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section id="common_banner_one" style="background-image:url(<?= get_picture("settings_v", $settings->blog_logo) ?>);">
    <div class="container ">
        <div class="row">
            <div class="col-lg-12">
                <div class="common_banner_text">
                    <h2><?= strto("lower|ucwords", $blog->title); ?></h2>
                    <ul>
                        <li><a rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a></li>
                        <li><i class="fas fa-slash"></i></li>
                        <li><a href="<?= base_url(lang("routes_blog")); ?>" rel="dofollow" title="<?= strto("lower|upper", lang("blog")) ?>"><?= strto("lower|upper", lang("blog")) ?></a></li>
                        <li><i class="fas fa-slash"></i></li>
                        <?php if (!empty($category)) : ?>
                            <li><a href="<?= base_url(lang("routes_blog") . "/" . $category->seo_url); ?>" rel="dofollow" title="<?= strto("lower|upper", $category->title) ?>"><?= strto("lower|upper", $category->title) ?></a></li>
                            <li><i class="fas fa-slash"></i></li>
                        <?php endif ?>
                        <li class="active"><?= strto("lower|ucwords", $blog->title); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Blog Single Area -->
<section id="blog_single_area" class="pt-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="blog_single_content">
                    <div class="blog_single_img img-zoom-hover">
                        <img width="1920" height="1280" loading="lazy" data-src="<?= get_picture("blogs_v", $blog->img_url) ?>" alt="<?= $blog->title ?>" class="img-fluid w-100 lazyload">
                    </div>
                    <div class="blog_single_widget">
                        <div class="blog_single_date">
                            <ul>
                                <li><a rel="dofollow" title="<?= $settings->company_name ?>" href="<?= base_url() ?>"><?= $settings->company_name ?></a></li>
                                <li><?= iconv("ISO-8859-9", "UTF-8", @strftime("%d %B %Y, %A %X", strtotime($blog->updatedAt))); ?></li>
                            </ul>
                        </div>
                        <div class="single_categoris_bottom">
                            <ul>
                                <li><a rel="dofollow" href="<?= base_url(lang("routes_blog") . "/" . $category->seo_url) ?>" title="<?= $category->title ?>"><i class="fa fa-folder"></i> <?= $category->title ?></a></li>
                            </ul>
                        </div>
                        <div class="blog_single_first_Widget">
                            <h2><?= $blog->title ?></h2>
                            <?= $blog->content ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="left-sidebar shop-sidebar-wrap">
                    <!-- Sidebar single item -->
                    <div class="sidebar-widget">
                        <h3 class="sidebar-title mt-0"><?= lang("searchBlog") ?></h3>
                        <div class="search-widget">
                            <form class="search-form" action="<?= !empty($this->uri->segment(3) && !is_numeric($this->uri->segment(3))) ? base_url(lang("routes_blog") . "/" . $this->uri->segment(3)) : base_url(lang("routes_blog")) ?>" method="GET" enctype="multipart/form-data">
                                <div class="input-group">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                    <input class="form-control" name="search" type="search" placeholder="<?= lang("searchBlog") ?>..." required>
                                    <button type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Sidebar single item -->
                    <!-- Sidebar single item -->
                    <?php if (!empty($categories)) : ?>
                        <div class="sidebar-widget mt-40px">
                            <h3 class="sidebar-title"><?= lang("blogCategories") ?></h3>
                            <div class="category-post">
                                <ul>
                                    <?php foreach ($categories as $k => $v) : ?>
                                        <li> <a rel="dofollow" href="<?= base_url(lang("routes_blog") . "/{$v->seo_url}") ?>" title="<?= $v->title ?>"><?= $v->title ?></a></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                        <!-- Sidebar single item -->
                    <?php endif ?>
                    <?php if (!empty($latestBlog)) : ?>
                        <div class="sidebar-widget mt-40px">
                            <h3 class="sidebar-title"><?= lang("latestBlog") ?></h3>
                            <div class="recent-post-widget">
                                <?php foreach ($latestBlog as $key => $value) : ?>
                                    <?php if (strtotime($value->sharedAt) <= strtotime("now")) : ?>
                                        <div class="recent-single-post d-flex">
                                            <div class="thumb-side img-zoom-hover">
                                                <a class="thumb" href="<?= base_url(lang("routes_blog") . "/" . lang("routes_blog_detail") . "/{$value->seo_url}") ?>">
                                                    <img width="1920" height="1280" loading="lazy" data-src="<?= get_picture("blogs_v", $value->img_url) ?>" alt="<?= $value->title ?>" class="lazyload img-fluid full-image cover bg1" width="150" height="150">
                                                </a>
                                            </div>
                                            <div class="media-side">
                                                <h5>
                                                    <a rel="dofollow" href="<?= base_url(lang("routes_blog") . "/" . lang("routes_blog_detail") . "/{$value->seo_url}") ?>" title="<?= $value->title ?>">
                                                        <?= $value->title ?>
                                                    </a>
                                                </h5>
                                                <span class="date"><?= iconv("ISO-8859-9", "UTF-8", @strftime("%d %B %Y, %A %X", strtotime($value->updatedAt))); ?></span>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </div>
                        </div>
                        <!-- Sidebar single item -->
                    <?php endif ?>

                </div>
            </div>
        </div>
    </div>
</section>