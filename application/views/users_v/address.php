<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
); ?>
<form id="createAddress" onsubmit="return false" method="POST" enctype="multipart/form-data">
    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="title"><?= lang("addressTitle") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <input class="form-control" id="saveTitle" type="text" name="title" placeholder="<?= lang("addressTitle") ?>">
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="phone"><?= lang("addressPhone") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <input class="form-control" type="tel" name="phone" maxlength="20" minlength="11" placeholder="<?= lang("addressPhone") ?>">
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="country"><?= lang("addressCountry") ?> : </label>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <?php if (!empty($allCountries)) : ?>
                <select name="country" id="saveCountry" class="form-control">
                    <?php foreach ($allCountries as $key => $value) : ?>
                        <option value="<?= $value->name ?>" <?= ($value->name == "Turkey" ? "selected" : null) ?>><?= $value->name ?></option>
                    <?php endforeach ?>
                </select>
            <?php endif ?>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="city"><?= lang("addressCity") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <select name="city" id="saveCity" class="form-control">
                <option value=""><?= lang("chooseCity") ?></option>
                <?php foreach ($allCities as $key => $value) : ?>
                    <option value="<?= $value->city ?>"><?= $value->city ?></option>
                <?php endforeach ?>
            </select>
            <input name="city" id="saveCity2" class="form-control d-none" disabled>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="district"><?= lang("addressDistrict") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <select name="district" id="saveDistrict" class="form-control">
                <option value=""><?= lang("chooseDistrict") ?></option>
            </select>
            <input name="district" id="saveDistrict2" class="form-control d-none" disabled>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="neighborhood"><?= lang("addressNeighborhood") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <select name="neighborhood" id="saveNeighborhood" class="form-control">
                <option value=""><?= lang("chooseNeighborhood") ?></option>
            </select>
            <input name="neighborhood" id="saveNeighborhood2" class="form-control d-none" disabled>

        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="quarter"><?= lang("addressQuarter") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <select name="quarter" id="saveQuarter" class="form-control">
                <option value=""><?= lang("chooseQuarter") ?></option>
            </select>
            <input name="quarter" id="saveQuarter2" class="form-control d-none" disabled>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="title"><?= lang("addressPostalCode") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <input class="form-control" type="number" id="savePostalCode" name="postalCode" placeholder="<?= lang("addressPostalCode") ?>">
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="address"><?= lang("address") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <textarea class="form-control" name="address" id="saveAddress" placeholder="<?= lang("address") ?>" cols="57" rows="3"></textarea>
        </div>
    </div>
    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
    <div class="form-group mb-3">
        <button aria-label="<?= $settings->company_name ?>" role="button" class="show-more-btn hover-btn btnSave" data-url="<?= asset_url("home/new_address") ?>" style="color:white;"><?= lang("saveAddressInformation") ?></button>
    </div>


</form>
<script>
    $(document).ready(function(data) {
        data.mask.definitions['~'] = '[+-]';
        data.mask.definitions['h'] = "[0-9 ]";
        $('input[type="tel"]').mask('0999 999 99 99');
        let city2 = $("#saveCity2"),
            city = $("#saveCity"),
            district2 = $("#saveDistrict2"),
            district = $("#saveDistrict"),
            neighborhood2 = $("#saveNeighborhood2"),
            neighborhood = $("#saveNeighborhood"),
            quarter2 = $("#saveQuarter2"),
            quarter = $("#saveQuarter");
        $(document).on("change", "#saveCountry", function() {
            let selected = $(this).val();
            if (selected !== "Turkey") {

                if (city2.hasClass("d-none")) {
                    city2.removeClass("d-none");
                    city2.prop("disabled", false);
                }
                if (!city.hasClass("d-none")) {
                    city.addClass("d-none");
                    city.prop("disabled", true);
                }
                if (district2.hasClass("d-none")) {
                    district2.removeClass("d-none");
                    district2.prop("disabled", false);
                }
                if (!district.hasClass("d-none")) {
                    district.addClass("d-none");
                    district.prop("disabled", true);
                }
                if (neighborhood2.hasClass("d-none")) {
                    neighborhood2.removeClass("d-none");
                    neighborhood2.prop("disabled", false);
                }
                if (!neighborhood.hasClass("d-none")) {
                    neighborhood.addClass("d-none");
                    neighborhood.prop("disabled", true);
                }
                if (quarter2.hasClass("d-none")) {
                    quarter2.removeClass("d-none");
                    quarter2.prop("disabled", false);
                }
                if (!quarter.hasClass("d-none")) {
                    quarter.addClass("d-none");
                    quarter.prop("disabled", true);
                }

            } else {
                if (!city2.hasClass("d-none")) {
                    city2.addClass("d-none");
                    city2.prop("disabled", true);
                }
                if (city.hasClass("d-none")) {
                    city.removeClass("d-none");
                    city.prop("disabled", false);
                }
                if (!district2.hasClass("d-none")) {
                    district2.addClass("d-none");
                    district2.prop("disabled", true);
                }
                if (district.hasClass("d-none")) {
                    district.removeClass("d-none");
                    district.prop("disabled", false);
                }
                if (!neighborhood2.hasClass("d-none")) {
                    neighborhood2.addClass("d-none");
                    neighborhood2.prop("disabled", true);
                }
                if (neighborhood.hasClass("d-none")) {
                    neighborhood.removeClass("d-none");
                    neighborhood.prop("disabled", false);
                }
                if (!quarter2.hasClass("d-none")) {
                    quarter2.addClass("d-none");
                    quarter2.prop("disabled", true);
                }
                if (quarter.hasClass("d-none")) {
                    quarter.removeClass("d-none");
                    quarter.prop("disabled", false);
                }
            }
        });
        $(document).on("change", "#saveCity", function() {
            let selected = $(this).val();
            $.post("<?= asset_url("home/getDistricts") ?>", {
                "city": selected,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                district.find("option[value!='']").remove();
                response.forEach(function(el, i) {
                    let html = "<option value=' " + el.district + "'  " + (i == 0 ? "selected" : null) + "  >" + el.district + "</option>";
                    district.append(html);
                });
                $.post("<?= asset_url("home/getNeighborhoods") ?>", {
                    "district": response[0].district,
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                }, function(response2) {
                    neighborhood.find("option[value!='']").remove();
                    response2.forEach(function(el, i) {
                        let html = "<option value=' " + el.neighborhood + "'  " + (i == 0 ? "selected" : null) + "  >" + el.neighborhood + "</option>";
                        neighborhood.append(html);
                    });
                    $.post("<?= asset_url("home/getQuarters") ?>", {
                        "neighborhood": response2[0].neighborhood,
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                    }, function(response3) {
                        quarter.find("option[value!='']").remove();
                        response3.forEach(function(el, i) {
                            $("#savePostalCode").val(el.postal_code);
                            let html = "<option value=' " + el.quarter + "'  " + (i == 0 ? "selected" : null) + "  >" + el.quarter + "</option>";
                            quarter.append(html);
                        });
                    }, "JSON");
                }, "JSON");
            }, "JSON");
        });
        $(document).on("change", "#saveDistrict", function() {
            let selected = $(this).val();
            $.post("<?= asset_url("home/getNeighborhoods") ?>", {
                "district": selected,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                neighborhood.find("option[value!='']").remove();
                response.forEach(function(el, i) {
                    let html = "<option value=' " + el.neighborhood + "'  " + (i == 0 ? "selected" : null) + "  >" + el.neighborhood + "</option>";
                    neighborhood.append(html);
                });
                $.post("<?= asset_url("home/getQuarters") ?>", {
                    "neighborhood": response[0].neighborhood,
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                }, function(response2) {
                    quarter.find("option[value!='']").remove();
                    response2.forEach(function(el, i) {
                        $("#savePostalCode").val(el.postal_code);
                        let html = "<option value=' " + el.quarter + "'  " + (i == 0 ? "selected" : null) + "  >" + el.quarter + "</option>";
                        quarter.append(html);
                    });
                }, "JSON");
            }, "JSON");
        });
        $(document).on("change", "#saveNeighborhood", function() {
            let selected = $(this).val();
            $.post("<?= asset_url("home/getQuarters") ?>", {
                "neighborhood": selected,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                quarter.find("option[value!='']").remove();
                response.forEach(function(el, i) {
                    $("#savePostalCode").val(el.postal_code);
                    let html = "<option value=' " + el.quarter + "'  " + (i == 0 ? "selected" : null) + "  >" + el.quarter + "</option>";
                    quarter.append(html);
                });
            }, "JSON");
        });
        $(document).on("change", "#saveQuarter", function() {
            let selected = $(this).val();
            $.post("<?= asset_url("home/getQuarters/") ?>" + encodeURIComponent(selected), {
                "quarter": selected,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                $("#savePostalCode").val(response.postal_code);
            }, "JSON");
        });
        $(document).on("click", ".btnSave", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let url = $(this).data("url");
            let formData = new FormData(document.getElementById("createAddress"));
            formData.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
            createAjax(url, formData, function() {
                closeModal("#addressModal");
                $("#addressPull2").load("<?= asset_url("home/get_address/chooseable") ?>");
                $("#addressPull").load("<?= asset_url("home/get_address") ?>");
            });
        });
    });
</script>