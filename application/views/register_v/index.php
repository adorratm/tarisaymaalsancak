<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= get_picture("settings_v", $settings->about_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", lang("register")) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", lang("register")) ?></h2>
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
                                <form action="<?= base_url(lang("routes_make-register")) ?>" method="POST" enctype="multipart/form-data">
                                    <div class="form-title">
                                        <h6><?= strto("lower|ucwords", lang("register")) ?></h6>
                                    </div>
                                    <div class="form-group pos_rel mb-3">
                                        <input id="full_name" name="full_name" type="text" placeholder="<?= lang("fullName") ?>" class="form-control lgn_input" required>
                                        <i class="uil uil-user lgn_icon"></i>
                                    </div>
                                    <div class="form-group pos_rel mb-3">
                                        <input id="email" name="email" type="email" placeholder="<?= lang("email") ?>" class="form-control lgn_input" required>
                                        <i class="uil uil-envelope-open lgn_icon"></i>
                                    </div>
                                    <div class="form-group pos_rel mb-3">
                                        <input id="phone" name="phone" type="tel" placeholder="<?= lang("phone") ?>" class="form-control lgn_input" required minlength="11" maxlength="20">
                                        <i class="uil uil-mobile-android-alt lgn_icon"></i>
                                    </div>
                                    <div class="form-group pos_rel mb-3">
                                        <input id="password" name="password" type="password" placeholder="<?= lang("password") ?>" class="form-control lgn_input" minlength="6" required>
                                        <i class="uil uil-padlock lgn_icon"></i>
                                    </div>
                                    <div class="form-group pos_rel mb-3">
                                        <input id="passwordRepeat" name="passwordRepeat" type="password" placeholder="<?= lang("passwordRepeat") ?>" class="form-control lgn_input" minlength="6" required>
                                        <i class="uil uil-padlock lgn_icon"></i>
                                    </div>
                                    <div class="form-group pos_rel mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="termsPolicy" name="termsPolicy" required>
                                            <label class="form-check-label" for="termsPolicy">
                                                <a class="text-muted" rel="dofollow" title="<?= lang("termsPolicy") ?>" target="_blank" onclick="$('#termsPolicy').prop('checked',true)" href="<?= lang("termsPolicyLink") ?>"><?= lang("termsPolicy") ?></a>
                                            </label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                    <button class="login-btn hover-btn h_50" type="submit"><?= strto("lower|ucwords", lang("register")); ?></button>
                                </form>
                            </div>
                            <div class="password-forgor">
                                <a rel="dofollow" title="<?= lang("forgotPassword") ?>" href="<?= base_url(lang("routes_forgot-password")) ?>"><?= lang("forgotPassword") ?></a>
                            </div>
                            <div class="signup-link">
                                <p><?= lang("pleaseLogin") ?> <a rel="dofollow" title="<?= lang("login") ?>" href="<?= base_url(lang("routes_login")) ?>"><?= lang("login") ?></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>