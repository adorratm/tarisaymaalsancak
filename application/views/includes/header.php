<div class="header-cate-model main-gambo-model modal fade" id="category_model" tabindex="-1" role="dialog" aria-modal="false">
    <div class="modal-dialog category-area" role="document">
        <div class="category-area-inner">
            <div class="modal-header">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="uil uil-multiply"></i>
                </button>
            </div>
            <div class="category-model-content modal-content">
                <div class="cate-header">
                    <h4><?= lang("chooseCategory") ?></h4>
                </div>
                <?php if (!empty($menuCategories)) : ?>
                    <?= show_header_categories($lang) ?>
                <?php endif ?>

                <a rel="dofollow" title="<?= lang("viewProducts") ?>" href="<?= base_url(lang("routes_products")) ?>" class="morecate-btn"><i class="uil uil-apps"></i><?= lang("viewProducts") ?></a>
            </div>
        </div>
    </div>
</div>


<div class="offcanvas offcanvas-end offcanvas-add-cart-wrapper" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <?php $this->load->view("includes/headerCart") ?>
</div>


<header class="header clearfix">
    <div class="top-header-group">
        <div class="top-header">
            <div class="main_logo" id="logo">
                <a rel="dofollow" href="<?= base_url() ?>" title="<?= $settings->company_name ?>"><img loading="lazy" width="150" height="100" data-src="<?= get_picture("settings_v", $settings->logo) ?>" alt="<?= $settings->company_name ?>" class="lazyload img-fluid"></a>
                <a rel="dofollow" href="<?= base_url() ?>" title="<?= $settings->company_name ?>"><img loading="lazy" width="150" height="100" data-src="<?= get_picture("settings_v", $settings->logo) ?>" alt="<?= $settings->company_name ?>" class="lazyload img-fluid logo-inverse"></a>
            </div>
            <div class="search120">
                <div class="header_search position-relative">
                    <form id="searchForm" action="<?= !empty($this->uri->segment(2) && !is_numeric($this->uri->segment(2))) ? base_url(lang("routes_products")) : base_url(lang("routes_products")) ?>" method="GET" enctype="multipart/form-data">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                        <div class="input-group">
                            <select class="form-control" onchange="$('#searchForm,#searchFormMobile').attr('action',$(this).val())">
                                <option value="<?= base_url(lang("routes_products")) ?>"><?= lang("searchAllCategories") ?></option>
                                <?php if (!empty($menuCategories)) : ?>
                                    <?php foreach ($menuCategories as $key => $value) : ?>
                                        <?php if ($value->top_id == 0) : ?>
                                            <option value="<?= base_url(lang("routes_products") . "/" . $value->seo_url) ?>"><?= $value->title ?></option>
                                        <?php endif ?>
                                    <?php endforeach; ?>
                                <?php endif ?>
                            </select>
                            <input type="hidden" name="orderBy" value="<?= (!empty($_GET["orderBy"]) ? clean($_GET["orderBy"]) : null) ?>">
                            <input type="hidden" name="amount" value="<?= (!empty($_GET["amount"]) ? clean($_GET["amount"]) : null) ?>">
                            <input type="hidden" name="key" value="<?= (!empty($_GET["key"]) ? clean($_GET["key"]) : null) ?>">
                            <input name="search" class="prompt srch10" type="text" placeholder="<?= lang("searchProduct") ?>..." required>
                            <button type="submit" class="btn btn-primary bg-main-dark" aria-label="<?= $settings->company_name ?>"><i class="uil uil-search s-icon"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="header_right">
                <ul>
                    <li>
                        <a class="offer-link" rel="dofollow" href="tel:<?= $settings->phone_1 ?>" title="<?= lang("phone") ?>"><i class="uil uil-phone-alt"></i> <?= $settings->phone_1 ?></a>
                    </li>
                    <li>
                        <a class="offer-link" rel="dofollow" href="mailto:<?= $settings->email ?>" title="Email"><i class="fa fa-envelope-open"></i> <?= $settings->email ?></a>
                    </li>
                    <?php if (!empty($settings->facebook)) : ?>
                        <li>
                            <a class="option_links" rel="dofollow" href="<?= $settings->facebook ?>" title="Facebook" target="_blank">
                                <i class='fab fa-facebook color me-2'></i>
                            </a>
                        </li>
                    <?php endif ?>
                    <?php if (!empty($settings->twitter)) : ?>
                        <li>
                            <a class="option_links" rel="dofollow" href="<?= $settings->twitter ?>" title="Twitter" target="_blank">
                                <i class='fab fa-twitter color me-2'></i>
                            </a>
                        </li>
                    <?php endif ?>
                    <?php if (!empty($settings->instagram)) : ?>
                        <li>
                            <a class="option_links" rel="dofollow" href="<?= $settings->instagram ?>" title="Instagram" target="_blank">
                                <i class='fab fa-instagram color me-2'></i>
                            </a>
                        </li>
                    <?php endif ?>
                    <?php if (!empty($settings->linkedin)) : ?>
                        <li>
                            <a class="option_links" rel="dofollow" href="<?= $settings->linkedin ?>" title="Linkedin" target="_blank">
                                <i class='fab fa-linkedin color me-2'></i>
                            </a>
                        </li>
                    <?php endif ?>
                    <?php if (!empty($settings->youtube)) : ?>
                        <li>
                            <a class="option_links" rel="dofollow" href="<?= $settings->youtube ?>" title="Youtube" target="_blank">
                                <i class='fab fa-youtube color me-2'></i>
                            </a>
                        </li>
                    <?php endif ?>
                    <?php if (!empty($settings->medium)) : ?>
                        <li>
                            <a class="option_links" rel="dofollow" href="<?= $settings->medium ?>" title="Medium" target="_blank">
                                <i class='fab fa-medium color me-2'></i>
                            </a>
                        </li>
                    <?php endif ?>
                    <?php if (!empty($settings->pinterest)) : ?>
                        <li>
                            <a class="option_links" rel="dofollow" href="<?= $settings->pinterest ?>" title="Pinterest" target="_blank">
                                <i class='fab fa-pinterest color me-2'></i>
                            </a>
                        </li>
                    <?php endif ?>
                    <?php if (!empty($settings->phone_3)) : ?>
                        <li>
                            <a rel="dofollow" class="option_links" href="https://api.whatsapp.com/send?phone=<?= $settings->phone_3 ?>&amp;text=<?= urlencode($settings->company_name) ?>." target="_blank" title="WhatsApp"><i class="fab fa-whatsapp color"></i></a>
                        </li>
                    <?php endif ?>
                    <li>
                        <a title="<?= lang("wishlist") ?>" href="<?= base_url(lang("routes_wishlist")) ?>" class="option_links offcanvas-wishlish"><i class='uil uil-heart icon_wishlist'></i></a>
                    </li>
                    <?php if (get_active_user()) : ?>
                        <li class="dropdown account-dropdown">
                            <a href="<?= base_url(lang("routes_account")) ?>" class="opts_account" role="button" id="accountClick" data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false">
                                <img loading="lazy" width="150" height="100" data-src="<?= get_picture("settings_v", $settings->logo) ?>" alt="<?= $settings->company_name ?>" class="lazyload img-fluid">
                                <span class="user__name"><?= get_active_user()->full_name ?></span>
                                <i class="uil uil-angle-down"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-account dropdown-menu-end" aria-labelledby="accountClick" data-bs-popper="none">
                                <div class="night_mode_switch__btn">
                                    <a href="<?= base_url() ?>" id="night-mode" class="btn-night-mode">
                                        <i class="uil uil-moon"></i> <?= lang("nightMode") ?>
                                        <span class="btn-night-mode-switch">
                                            <span class="uk-switch-button"></span>
                                        </span>
                                    </a>
                                </div>
                                <a rel="dofollow" title="<?= lang("account") ?>" class="channel_item" href="<?= base_url(lang("routes_account")); ?>"><i class="uil uil-apps icon__1"></i><?= lang("account") ?></a>
                                <a rel="dofollow" title="<?= lang("logout") ?>" class="channel_item" href="<?= base_url(lang("routes_logout")); ?>"><i class="uil uil-lock-alt icon__1"></i><?= lang("logout") ?></a>
                            </div>
                        </li>
                    <?php else : ?>
                        <li class="dropdown account-dropdown">
                            <a href="<?= base_url(lang("routes_account")) ?>" class="opts_account" role="button" id="accountClick" data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false">
                                <img loading="lazy" width="150" height="100" data-src="<?= get_picture("settings_v", $settings->logo) ?>" alt="<?= $settings->company_name ?>" class="lazyload img-fluid">
                                <span class="user__name"><?= lang("login") ?> / <?= lang("register") ?></span>
                                <i class="uil uil-angle-down"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-account dropdown-menu-end" aria-labelledby="accountClick" data-bs-popper="none">
                                <div class="night_mode_switch__btn">
                                    <a href="<?= base_url() ?>" id="night-mode" class="btn-night-mode">
                                        <i class="uil uil-moon"></i> <?= lang("nightMode") ?>
                                        <span class="btn-night-mode-switch">
                                            <span class="uk-switch-button"></span>
                                        </span>
                                    </a>
                                </div>
                                <a rel="dofollow" title="<?= lang("login") ?>" class="channel_item" href="<?= base_url(lang("routes_login")); ?>"><i class="uil uil-unlock icon__1"></i><?= lang("login") ?></a>
                                <a rel="dofollow" title="<?= lang("register") ?>" class="channel_item" href="<?= base_url(lang("routes_register")); ?>"><i class="uil uil-lock-alt icon__1"></i><?= lang("register") ?></a>
                            </div>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="sub-header-group">
        <div class="sub-header">
            <nav class="navbar navbar-expand-lg bg-gambo gambo-head navbar justify-content-lg-start pt-0 pb-0">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon">
                        <i class="uil uil-bars"></i>
                    </span>
                </button>
                <a rel="dofollow" href="<?= base_url(lang("routes_products")) ?>" class="category_drop hover-btn" data-bs-toggle="modal" data-bs-target="#category_model" title="<?= lang("chooseCategory") ?>"><i class="uil uil-apps"></i><span class="cate__icon"><?= lang("chooseCategory") ?></span></a>
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <div class="offcanvas-logo" id="offcanvasNavbarLabel">
                            <a rel="dofollow" href="<?= base_url() ?>" title="<?= $settings->company_name ?>"><img loading="lazy" width="150" height="100" data-src="<?= get_picture("settings_v", $settings->logo) ?>" alt="<?= $settings->company_name ?>" class="lazyload img-fluid"></a>
                        </div>
                        <button type="button" class="close-btn" data-bs-dismiss="offcanvas" aria-label="Close">
                            <i class="uil uil-multiply"></i>
                        </button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="offcanvas-category mb-4 d-block d-lg-none">
                            <div class="offcanvas-search position-relative">
                                <form id="searchFormMobile" action="<?= !empty($this->uri->segment(2) && !is_numeric($this->uri->segment(2))) ? base_url(lang("routes_products")) : base_url(lang("routes_products")) ?>" method="GET" enctype="multipart/form-data">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                    <div class="input-group">
                                        <select class="form-control w-100 d-block rounded-0 mb-3" onchange="$('#searchForm,#searchFormMobile').attr('action',$(this).val())">
                                            <option value="<?= base_url(lang("routes_products")) ?>"><?= lang("searchAllCategories") ?></option>
                                            <?php if (!empty($menuCategories)) : ?>
                                                <?php foreach ($menuCategories as $key => $value) : ?>
                                                    <?php if ($value->top_id == 0) : ?>
                                                        <option value="<?= base_url(lang("routes_products") . "/" . $value->seo_url) ?>"><?= $value->title ?></option>
                                                    <?php endif ?>
                                                <?php endforeach; ?>
                                            <?php endif ?>
                                        </select>
                                        <input type="hidden" name="orderBy" value="<?= (!empty($_GET["orderBy"]) ? clean($_GET["orderBy"]) : null) ?>">
                                        <input type="hidden" name="amount" value="<?= (!empty($_GET["amount"]) ? clean($_GET["amount"]) : null) ?>">
                                        <input type="hidden" name="key" value="<?= (!empty($_GET["key"]) ? clean($_GET["key"]) : null) ?>">
                                        <input name="search" class="form-control canvas_search" type="text" placeholder="<?= lang("searchProduct") ?>..." required>
                                        <button type="submit" class="btn btn-primary canvas-icon" aria-label="<?= $settings->company_name ?>"><i class="uil uil-search"></i></button>
                                    </div>
                                </form>
                            </div>
                            <button class="category_drop_canvas hover-btn mt-4" data-bs-toggle="modal" data-bs-target="#category_model" title="<?= lang("chooseCategory") ?>"><i class="uil uil-apps"></i><span class="cate__icon"><?= lang("chooseCategory") ?></span></button>
                        </div>
                        <?= $menus ?>
                        <div class="d-block d-lg-none">
                            <ul class="offcanvas-help-links">
                                <li>
                                    <a class="offer-link" rel="dofollow" href="tel:<?= $settings->phone_1 ?>" title="<?= lang("phone") ?>"><i class="uil uil-phone-alt"></i> <?= $settings->phone_1 ?></a>
                                </li>
                                <li>
                                    <a class="offer-link" rel="dofollow" href="mailto:<?= $settings->email ?>" title="Email"><i class="fa fa-envelope-open"></i> <?= $settings->email ?></a>
                                </li>
                            </ul>
                            <div class="offcanvas-copyright text-center">
                                © <?= date("Y") ?> <a class="fw-bolder" rel="dofollow" href="<?= base_url() ?>" title="<?= $settings->company_name ?>"><?= $settings->company_name ?></a>.<br><?= lang("allRightsReserved") ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sub_header_right">
                    <div class="header_cart offcanvas-add-cart">
                        <a rel="dofollow" href="<?= base_url(lang("routes_cart")) ?>" class="cart__btn hover-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" title="<?= lang("cart") ?>"><i class="uil uil-shopping-cart-alt"></i><span><?= lang("cart") ?></span><ins class="item-count"><?= count($this->cart->contents()) ?></ins><i class="uil uil-angle-down"></i></a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

<div class="wrapper">