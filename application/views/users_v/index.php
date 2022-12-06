<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="default-dt" style="background-image:url(<?= get_picture("settings_v", $settings->about_logo) ?>);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="default_tabs">
                    <nav>
                        <div class="nav nav-tabs tab_default  justify-content-center">
                            <a class="nav-item nav-link" rel="dofollow" href="<?= base_url(); ?>" title="<?= strto("lower|ucwords", lang("home")) ?>"><?= strto("lower|ucwords", lang("home")) ?></a>
                            <a class="nav-item nav-link active" href="<?= str_replace("index.php/" . $lang . "/", "", current_url()) ?>"><?= strto("lower|ucwords", lang("account")) ?></a>
                        </div>
                    </nav>
                </div>
                <div class="title129">
                    <h2><?= strto("lower|ucwords", lang("account")) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- My-account-area-Area -->
<div class="all-product-grid">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-3">
                <div class="left-side-tabs">
                    <!-- Nav tabs -->
                    <ul role="tablist" class="nav flex-column dashboard-list dashboard-left-links">
                        <li> <a href="<?= base_url(lang("routes_account")) ?>" data-bs-toggle="tab" data-bs-target="#orders" class="user-item active"><i class="fas fa-cart-arrow-down"></i><?= lang("orders") ?></a></li>
                        <li><a href="<?= base_url(lang("routes_account")) ?>" data-bs-toggle="tab" data-bs-target="#wishlist" class="user-item"><i class="fas fa-heart"></i><?= lang("wishlist") ?></a></li>
                        <li><a href="<?= base_url(lang("routes_account")) ?>" data-bs-toggle="tab" data-bs-target="#address" class="user-item"><i class="fas fa-map-marker-alt"></i><?= lang("addresses") ?></a></li>
                        <li><a href="<?= base_url(lang("routes_account")) ?>" data-bs-toggle="tab" data-bs-target="#account-details" class="user-item"><i class="fas fa-user"></i><?= lang("accountDetails") ?></a>
                        </li>
                        <li><a rel="dofollow" title="<?= lang("logout") ?>" href="<?= base_url(lang("routes_logout")) ?>" class="user-item"><i class="fas fa-sign-out-alt"></i><?= lang("logout") ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-9">
                <!-- Tab panes -->
                <div class="left-side-tabs p-3">
                    <div class="tab-content dashboard_content">
                        <div class="tab-pane fade show active" id="orders">
                            <div class="myaccount-content">
                                <h4 class="title"><?= lang("orders") ?></h4>
                                <div class="table_page table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th><?= lang("phone") ?></th>
                                                <th><?= lang("orderTotal") ?></th>
                                                <th><?= lang("orderStatus") ?></the>
                                                <th><?= lang("paymentType") ?></thtus>
                                                <th><?= lang("orderShippingStatus") ?></thal>
                                                <th><?= lang("orderDate") ?></th>
                                                <th><?= lang("actions") ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($orders)) : ?>
                                                <?php foreach ($orders as $key => $value) : ?>
                                                    <tr>
                                                        <td><?= $value->phone ?></td>
                                                        <td><?= number_format($value->total, 2) ?> <?= $value->symbol ?></td>
                                                        <td><?= $value->status ?></td>
                                                        <td><?= $value->paymentType ?></td>
                                                        <td><?= $value->shippingStatus ?></td>
                                                        <td><?= $value->createdAt ?></td>
                                                        <td><a rel="dofollow" href="javascript:void(0)" class="view getOrderBtn" data-url="<?= asset_url("home/order_detail/" . $value->order_code) ?>" data-toggle="tooltip" data-placement="top" data-title="<?= lang("orderDetail") ?>" title="<?= lang("orderDetail") ?>"><i class="fa fa-file"></i></a></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="wishlist">
                            <div class="myaccount-content">
                                <h4 class="title"><?= lang("wishlist") ?></h4>
                                <div class="table_page table-responsive myWishlist">
                                    <?php $this->load->view("includes/wishlist") ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="address">
                            <div class="myaccount-content">
                                <div class="d-flex flex-wrap title">
                                    <div class="flex-grow-1 my-auto text-center">
                                        <h4 class="my-auto"><?= lang("addresses") ?></h4>
                                    </div>
                                    <div class="flex-shrink-1 my-auto">
                                        <a rel="dofollow" class="show-more-btn hover-btn createAddressBtn" data-url="<?= asset_url("home/newAddressForm") ?>" href="javascript:void(0)" title="<?= lang("createNewAddress") ?>"><i class="fa fa-edit"></i> <?= lang("createNewAddress") ?></a>
                                    </div>
                                </div>
                                <div id="addressPull">
                                    <?php $this->load->view("includes/address") ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-details">
                            <div class="myaccount-content">
                                <h4 class="title"><?= lang("accountDetails") ?></h4>
                                <div class="login_form_container">
                                    <div class="account_details_form">
                                        <form action="<?= asset_url("home/accountUpdate") ?>" method="POST" enctype="multipart/form-data">
                                            <div class="form-group mb-3">
                                                <label><?= lang("fullName") ?></label>
                                                <input type="text" class="form-control" name="full_name" placeholder="<?= lang("fullName") ?>" maxlength="70" minlength="4" value="<?= !empty(set_value('full_name')) ? set_value('full_name') : $user->full_name ?>" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label><?= lang("email") ?></label>
                                                <input type="email" class="form-control" name="email" placeholder="<?= lang("email") ?>" minlength="3" value="<?= !empty(set_value('email')) ? set_value('email') : $user->email ?>" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label><?= lang("phone") ?></label>
                                                <input type="tel" class="form-control" name="phone" placeholder="<?= lang("phone") ?>" minlength="11" maxlength="20" value="<?= !empty(set_value('phone')) ? set_value('phone') : $user->phone ?>" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label><?= lang("password") ?></label>
                                                <input type="password" class="form-control" name="password" placeholder="<?= lang("password") ?>" minlength="6" value="<?= !empty(set_value('password')) ? set_value('password') : null ?>">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label><?= lang("passwordRepeat") ?></label>
                                                <input type="password" class="form-control" name="passwordRepeat" placeholder="<?= lang("passwordRepeat") ?>" minlength="6" <?= !empty(set_value('passwordRepeat')) ? set_value('passwordRepeat') : null ?>>
                                            </div>
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                            <div class="save_button mt-3">
                                                <button aria-label="<?= $settings->company_name ?>" class="show-more-btn hover-btn" type="submit"><?= lang("updateDetails") ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>


    <!-- /.cart-area -->

    <!-- Address Modal -->
    <div id="addressModal"></div>
    <div id="ordersModal"></div>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            $(document).on("click", ".createAddressBtn", function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let url = $(this).data("url");
                $('#addressModal').iziModal('destroy');
                createModal("#addressModal", "<?= lang("createNewAddress") ?>", "<?= lang("createNewAddress") ?>", 600, true, "20px", 0, "var(--main-theme-color)", "#fff", 1040, function() {
                    $.post(url, {
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                    }, function(response) {
                        $("#addressModal .iziModal-content").html(response);
                    });
                });
                openModal("#addressModal");
            });
            $(document).on("click", ".editAddressBtn", function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let url = $(this).data("url");
                $('#addressModal').iziModal('destroy');
                createModal("#addressModal", "<?= lang("editAddress") ?>", "<?= lang("editAddress") ?>", 600, true, "20px", 0, "var(--main-theme-color)", "#fff", 1040, function() {
                    $.post(url, {
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                    }, function(response) {
                        $("#addressModal .iziModal-content").html(response);

                    });
                });
                openModal("#addressModal");
            });
            $(document).on('click', '.deleteAddress', function(e) {
                let url = $(this).data("url");
                Swal.fire({
                    title: '<?= lang("areYouSure") ?>',
                    text: "<?= lang("cannotGetBack") ?>",
                    icon: 'warning',
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "<?= lang("yesDeleteIt") ?>",
                    cancelButtonText: "<?= lang("noCancelIt") ?>"
                }).then(function(result) {
                    if (result.value) {
                        let formData = new FormData();
                        formData.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
                        createAjax(url, formData, function() {
                            $("#addressPull").load("<?= asset_url("home/get_address") ?>");
                            $("#addressPull2").load("<?= asset_url("home/get_address/chooseable") ?>");
                        });
                    }
                })
            });
            $(document).on("click", ".getOrderBtn", function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $('#ordersModal').iziModal('destroy');
                let url = $(this).data("url");
                createModal("#ordersModal", "<?= lang("orderDetail") ?>", "<?= lang("orderDetail") ?>", 600, true, "20px", 0, "var(--main-theme-color)", "#fff", 1040, function() {
                    $.post(url, {
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                    }, function(response) {
                        $("#ordersModal .iziModal-content").html(response);
                    });
                });
                openModal("#ordersModal");
                $("#ordersModal").iziModal("setFullscreen", false);
            });
        });
    </script>