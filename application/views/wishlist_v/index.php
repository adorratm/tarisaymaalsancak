<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="default-dt" style="background-image:url(<?= get_picture("settings_v", $settings->product_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", lang("wishlist")) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", lang("wishlist")) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Cart-Area -->
<div class="section145">
    <div class="container myWishlist">
        <?php $this->load->view("includes/wishlist") ?>
    </div>
</div>