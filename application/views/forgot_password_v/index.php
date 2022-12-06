<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= get_picture("settings_v", $settings->about_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", lang("forgotPassword")) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", lang("forgotPassword")) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sign-inup">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="sign-form">
                    <div class="sign-inner">
                        <div class="sign-logo" id="logo">
                            <a rel="dofollow" title="<?= $settings->company_name ?>" href="<?= base_url() ?>"><img data-src="<?= get_picture("settings_v", $settings->logo) ?>" alt="<?= $settings->company_name ?>" class="img-fluid lazyload"></a>
                        </div>
                        <div class="form-dt">
                            <div class="form-inpts checout-address-step">
                                <form action="<?= base_url(lang("routes_forgot-password-reset")) ?>" method="POST" enctype="multipart/form-data">
                                    <div class="form-title">
                                        <h6><?= strto("lower|ucwords", lang("forgotPassword")) ?></h6>
                                    </div>
                                    <div class="form-group pos_rel mb-3">
                                        <input id="email" name="email" type="text" placeholder="<?= lang("email") ?>" class="form-control lgn_input" required>
                                        <i class="uil uil-envelope-open lgn_icon"></i>
                                    </div>
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                    <button class="login-btn hover-btn h_50" type="submit"><?= strto("lower|ucwords", lang("submitForgotPassword")); ?></button>
                                </form>
                            </div>
                            <div class="password-forgor">
                                <a rel="dofollow" title="<?= lang("login") ?>" href="<?= base_url(lang("routes_login")) ?>"><?= lang("login") ?></a>
                            </div>
                            <div class="signup-link">
                                <p><?= lang("pleaseRegister") ?> <a rel="dofollow" title="<?= lang("register") ?>" href="<?= base_url(lang("routes_register")) ?>"><?= lang("register") ?></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>