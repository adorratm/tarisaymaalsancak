<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= get_picture("settings_v", $settings->contact_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", lang("contact")) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", lang("contact")) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="all-product-grid">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="contact-title">
                    <h2><?= lang("contactInformation") ?></h2>
                    <p><?= lang("contactFormDesc") ?></p>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="contact-form">
                    <form onsubmit="return false" enctype="multipart/form-data" method="POST" id="contact-form">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group mt-4">
                                    <label class="control-label"><?= lang("namesurname") ?></label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input type="text" name="full_name" id="full_name" class="prompt srch_explore" placeholder="<?= lang("namesurname") ?>" required minlength="2" maxlength="70">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group mt-4">
                                    <label class="control-label"><?= lang("emailaddress") ?></label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input type="email" name="email" id="email" class="prompt srch_explore" placeholder="<?= lang("emailaddress") ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group mt-4">
                                    <div class="ui search focus">
                                        <label class="control-label"><?= lang("phonenumber") ?></label>
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input type="text" name="phone" id="phone" placeholder="<?= lang("phonenumber") ?>" required minlength="11" maxlength="19" class="prompt srch_explore">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group mt-4">
                                    <label class="control-label"><?= lang("subject") ?></label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <input type="text" name="subject" id="subject" class="prompt srch_explore" placeholder="<?= lang("subject") ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mt-4">
                                    <label class="control-label"><?= lang("message") ?></label>
                                    <div class="ui search focus">
                                        <div class="ui left icon input swdh11 swdh19">
                                            <textarea name="comment" class="form-control" id="comment" cols="30" rows="8" placeholder="<?= lang("message") ?>" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="submit_bitton_contact_one">
                                    <button aria-label="<?= $settings->company_name ?>" type="submit" class="next-btn16 hover-btn mt-30 btnSubmitForm" data-url="<?= base_url(lang("routes_contact-form")) ?>">
                                        <?= lang("submit") ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="panel-group accordion" id="accordion0">
                    <div class="panel panel-default">
                        <div class="panel-heading" id="headingOne">
                            <div class="panel-title">
                                <a data-bs-toggle="collapse" data-bs-target="#collapseOne" href="#" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="uil uil-location-point chck_icon"></i><?= lang("address") ?>
                                </a>
                            </div>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapsed show" role="tabpanel" aria-labelledby="headingOne" data-bs-parent="#accordion0">
                            <div class="panel-body">
                                <a rel="dofollow" href="https://maps.google.com/maps?q=<?= @urlencode(clean($settings->address)) ?>" target="_blank" title="<?= lang("address") ?>"><?= clean($settings->address) ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" id="headingTwo">
                            <div class="panel-title">
                                <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" href="#" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="uil uil-phone chck_icon"></i><?= lang("phone") ?>
                                </a>
                            </div>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" data-bs-parent="#accordion0">
                            <div class="panel-body">
                                <div class="color-pink">
                                    <ul>
                                        <li><a rel="dofollow" title="<?= lang("phone") ?>" href="tel:<?= $settings->phone_1 ?>"><?= $settings->phone_1 ?></a></li>
                                        <?php if (!empty($settings->phone_2)) : ?>
                                            <li><a rel="dofollow" title="<?= lang("phone") ?>" href="tel:<?= $settings->phone_2 ?>"><?= $settings->phone_2 ?></a></li>
                                        <?php endif ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($settings->fax_1)) : ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" id="headingfour">
                                <div class="panel-title">
                                    <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapsefour" href="#" aria-expanded="false" aria-controls="collapsefour">
                                        <i class="fas fa-fax chck_icon"></i><?= lang("fax") ?>
                                    </a>
                                </div>
                            </div>
                            <div id="collapsefour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfour" data-bs-parent="#accordion0">
                                <div class="panel-body">
                                    <div class="color-pink">
                                        <ul>
                                            <li><a rel="dofollow" title="<?= lang("phone") ?>" href="tel:<?= $settings->fax_1 ?>"><?= $settings->fax_1 ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" id="headingThree">
                            <div class="panel-title">
                                <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree" href="#" aria-expanded="false" aria-controls="collapseThree">
                                    <i class="uil uil-whatsapp chck_icon"></i> Whatsapp
                                </a>
                            </div>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" data-bs-parent="#accordion0">
                            <div class="panel-body">
                                <div class="color-pink">
                                    <ul>
                                        <li>
                                            <a rel="dofollow" title="Whatsapp" href="https://api.whatsapp.com/send?phone=<?= $settings->phone_3 ?>&amp;text=<?= urlencode($settings->company_name) ?>."><?= $settings->phone_3 ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" id="headingfive">
                            <div class="panel-title">
                                <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapsefive" href="#" aria-expanded="false" aria-controls="collapsefive">
                                    <i class="uil uil-envelope chck_icon"></i> E-Mail
                                </a>
                            </div>
                        </div>
                        <div id="collapsefive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfive" data-bs-parent="#accordion0">
                            <div class="panel-body">
                                <div class="color-pink">
                                    <ul>
                                        <li>
                                            <a rel="dofollow" title="Email" href="mailto:<?= $settings->email ?>"><?= $settings->email ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" id="headingsix">
                            <div class="panel-title">
                                <a class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapsesix" href="#" aria-expanded="false" aria-controls="collapsesix">
                                    <i class="fas fa-comments chck_icon"></i><?= lang("social") ?>
                                </a>
                            </div>
                        </div>
                        <div id="collapsesix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingsix" data-bs-parent="#accordion0">
                            <div class="panel-body">
                                <div class="color-pink">
                                    <ul class="d-flex justify-content-center justify-content-lg-start">
                                        <?php if (!empty($settings->facebook)) : ?>
                                            <li>
                                                <a rel="dofollow" href="<?= $settings->facebook ?>" title="Facebook" target="_blank">
                                                    <i class='fab fa-facebook color fa-2x'></i>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                        <?php if (!empty($settings->twitter)) : ?>
                                            <li class="ms-2">
                                                <a rel="dofollow" href="<?= $settings->twitter ?>" title="Twitter" target="_blank">
                                                    <i class='fab fa-twitter color fa-2x'></i>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                        <?php if (!empty($settings->instagram)) : ?>
                                            <li class="ms-2">
                                                <a rel="dofollow" href="<?= $settings->instagram ?>" title="Instagram" target="_blank">
                                                    <i class='fab fa-instagram color fa-2x'></i>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                        <?php if (!empty($settings->linkedin)) : ?>
                                            <li class="ms-2">
                                                <a rel="dofollow" href="<?= $settings->linkedin ?>" title="Linkedin" target="_blank">
                                                    <i class='fab fa-linkedin color fa-2x'></i>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                        <?php if (!empty($settings->youtube)) : ?>
                                            <li class="ms-2">
                                                <a rel="dofollow" href="<?= $settings->youtube ?>" title="Youtube" target="_blank">
                                                    <i class='fab fa-youtube color fa-2x'></i>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                        <?php if (!empty($settings->medium)) : ?>
                                            <li class="ms-2">
                                                <a rel="dofollow" href="<?= $settings->medium ?>" title="Medium" target="_blank">
                                                    <i class='fab fa-medium color fa-2x'></i>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                        <?php if (!empty($settings->pinterest)) : ?>
                                            <li class="ms-2">
                                                <a rel="dofollow" href="<?= $settings->pinterest ?>" title="Pinterest" target="_blank">
                                                    <i class='fab fa-pinterest color fa-2x'></i>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="map_area">
                    <?= htmlspecialchars_decode($settings->map) ?>
                </div>
            </div>
        </div>
    </div>
</div>