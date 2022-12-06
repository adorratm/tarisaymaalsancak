<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
); ?>
<form id="updateAddress" onsubmit="return false" method="POST" enctype="multipart/form-data">

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="title"><?= lang("addressTitle") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <input class="form-control" value="<?= !empty($item->title) ? $item->title : null ?>" id="updateTitle" type="text" name="title" placeholder="<?= lang("addressTitle") ?>">
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="phone"><?= lang("addressPhone") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <input class="form-control" type="tel" value="<?= !empty($item->phone) ? $item->phone : null ?>" name="phone" maxlength="20" minlength="11" placeholder="<?= lang("addressPhone") ?>">
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="country"><?= lang("addressCountry") ?> : </label>
        </div>

        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <?php if (!empty($allCountries)) : ?>
                <select name="country" id="updateCountry" class="form-control">
                    <?php foreach ($allCountries as $key => $value) : ?>
                        <option value="<?= $value->name ?>" <?= ($value->name == $item->country ? "selected" : null) ?>><?= $value->name ?></option>
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
            <select name="city" id="updateCity" class="form-control <?= $item->country != "Turkey" ? 'd-none' : null ?>" <?= $item->country != "Turkey" ? 'disabled' : null ?>>
                <option value=""><?= lang("chooseCity") ?></option>
                <?php $itemCityId = null ?>
                <?php foreach ($allCities as $key => $value) : ?>
                    <option value="<?= $value->city ?>" <?= ($value->city == $item->city ? "selected" : null) ?>><?= $value->city ?></option>
                    <?php if ($value->city == $item->city) : ?>
                        <?php $itemCityId = $value->city_id ?>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
            <input name="city" value="<?= !empty($item->city) && $item->country != "Turkey" ? $item->city : null ?>" id="updateCity2" class="form-control <?= $item->country != "Turkey" ? null : 'd-none' ?>" <?= $item->country != "Turkey" ? null : 'disabled' ?>>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="district"><?= lang("addressDistrict") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <select name="district" id="updateDistrict" class="form-control <?= $item->country != "Turkey" ? 'd-none' : null ?>" <?= $item->country != "Turkey" ? 'disabled' : null ?>>
                <option value=""><?= lang("chooseDistrict") ?></option>
                <?php $itemDistrictId = null ?>
                <?php foreach ($allDistricts as $key => $value) : ?>
                    <?php if ($value->cities_id == $itemCityId) : ?>
                        <option value="<?= $value->district ?>" <?= ($value->district == $item->district ? "selected" : null) ?>><?= $value->district ?></option>
                    <?php endif ?>
                    <?php if ($value->district == $item->district) : ?>
                        <?php $itemDistrictId = $value->district_id ?>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
            <input name="district" value="<?= !empty($item->district) && $item->country != "Turkey" ? $item->district : null ?>" id="updateDistrict2" class="form-control <?= $item->country != "Turkey" ? null : 'd-none' ?>" <?= $item->country != "Turkey" ? null : 'disabled' ?>>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="neighborhood"><?= lang("addressNeighborhood") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <select name="neighborhood" id="updateNeighborhood" class="form-control <?= $item->country != "Turkey" ? 'd-none' : null ?>" <?= $item->country != "Turkey" ? 'disabled' : null ?>>
                <option value=""><?= lang("chooseNeighborhood") ?></option>
                <?php $itemNeighborhoodId = null ?>
                <?php foreach ($allNeighborhoods as $key => $value) : ?>
                    <?php if ($value->districts_id == $itemDistrictId) : ?>
                        <option value="<?= $value->neighborhood ?>" <?= ($value->neighborhood == $item->neighborhood ? "selected" : null) ?>><?= $value->neighborhood ?></option>
                    <?php endif ?>
                    <?php if ($value->neighborhood == $item->neighborhood) : ?>
                        <?php $itemNeighborhoodId = $value->neighborhood_id ?>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
            <input name="neighborhood" value="<?= !empty($item->neighborhood) && $item->country != "Turkey" ? $item->neighborhood : null ?>" id="updateNeighborhood2" class="form-control <?= $item->country != "Turkey" ? null : 'd-none' ?>" <?= $item->country != "Turkey" ? null : 'disabled' ?>>

        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="quarter"><?= lang("addressQuarter") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <select name="quarter" id="updateQuarter" class="form-control <?= $item->country != "Turkey" ? 'd-none' : null ?>" <?= $item->country != "Turkey" ? 'disabled' : null ?>>
                <option value=""><?= lang("chooseQuarter") ?></option>
                <?php foreach ($allQuarters as $key => $value) : ?>
                    <?php if ($value->neighborhoods_id == $itemNeighborhoodId) : ?>
                        <option value="<?= $value->quarter ?>" <?= ($value->quarter == $item->quarter ? "selected" : null) ?>><?= $value->quarter ?></option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
            <input name="quarter" id="updateQuarter2" value="<?= !empty($item->quarter) && $item->country != "Turkey" ? $item->quarter : null ?>" class="form-control <?= $item->country != "Turkey" ? null : 'd-none' ?>" <?= $item->country != "Turkey" ? null : 'disabled' ?>>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="title"><?= lang("addressPostalCode") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <input class="form-control" type="number" value="<?= !empty($item->postalCode) ? $item->postalCode : null ?>" id="updatePostalCode" name="postalCode" placeholder="<?= lang("addressPostalCode") ?>">
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 my-auto py-auto">
            <label for="address"><?= lang("address") ?> : </label>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
            <textarea class="form-control" name="address" id="updateAddress" placeholder="<?= lang("address") ?>" cols="57" rows="3"><?= !empty($item->address) ? $item->address : null ?></textarea>
        </div>
    </div>
    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
    <div class="form-group mb-3">
        <button aria-label="<?= $settings->company_name ?>" role="button" class="show-more-btn hover-btn btnUpdate" data-url="<?= asset_url("home/update_address/{$item->id}") ?>"><?= lang("updateAddressInformation") ?>
        </button>
    </div>
</form>
<script>
    $(document).ready(function(data) {
        data.mask.definitions['~'] = '[+-]';
        data.mask.definitions['h'] = "[0-9 ]";
        $('input[type="tel"]').mask('0999 999 99 99');
        let city2 = $("#updateCity2"),
            city = $("#updateCity"),
            district2 = $("#updateDistrict2"),
            district = $("#updateDistrict"),
            neighborhood2 = $("#updateNeighborhood2"),
            neighborhood = $("#updateNeighborhood"),
            quarter2 = $("#updateQuarter2"),
            quarter = $("#updateQuarter");
        $(document).on("change", "#updateCountry", function() {
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
        $(document).on("change", "#updateCity", function() {
            let selected = $(this).val();
            $.post("<?= asset_url("home/getDistricts") ?>", {
                "city": selected,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                district.find("option[value!='']").remove();
                response.forEach(function(el, i) {
                    let html = "<option value=' " + el.district + "'  " + (el.district == "<?= $item->district ?>" ? "selected" : null) + "  >" + el.district + "</option>";
                    district.append(html);
                });
                $.post("<?= asset_url("home/getNeighborhoods") ?>", {
                    "district": response[0].district,
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                }, function(response2) {
                    neighborhood.find("option[value!='']").remove();
                    response2.forEach(function(el, i) {
                        let html = "<option value=' " + el.neighborhood + "'  " + (el.neighborhood == "<?= $item->neighborhood ?>" ? "selected" : null) + "  >" + el.neighborhood + "</option>";
                        neighborhood.append(html);
                    });
                    $.post("<?= asset_url("home/getQuarters") ?>", {
                        "neighborhood": response2[0].neighborhood,
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                    }, function(response3) {
                        quarter.find("option[value!='']").remove();
                        response3.forEach(function(el, i) {
                            $("#updatePostalCode").val(el.postal_code);
                            let html = "<option value=' " + el.quarter + "'  " + (el.quarter == "<?= $item->quarter ?>" ? "selected" : null) + "  >" + el.quarter + "</option>";
                            quarter.append(html);
                        });
                    }, "JSON");
                }, "JSON");
            }, "JSON");
        });
        $(document).on("change", "#updateDistrict", function() {
            let selected = $(this).val();
            $.post("<?= asset_url("home/getNeighborhoods") ?>", {
                "district": selected,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                neighborhood.find("option[value!='']").remove();
                response.forEach(function(el, i) {
                    let html = "<option value=' " + el.neighborhood + "'  " + (el.neighborhood == "<?= $item->neighborhood ?>" ? "selected" : null) + "  >" + el.neighborhood + "</option>";
                    neighborhood.append(html);
                });
                $.post("<?= asset_url("home/getQuarters") ?>", {
                    "neighborhood": response[0].neighborhood,
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                }, function(response2) {
                    quarter.find("option[value!='']").remove();
                    response2.forEach(function(el, i) {
                        $("#updatePostalCode").val(el.postal_code);
                        let html = "<option value=' " + el.quarter + "'  " + (el.quarter == "<?= $item->quarter ?>" ? "selected" : null) + "  >" + el.quarter + "</option>";
                        quarter.append(html);
                    });
                }, "JSON");
            }, "JSON");
        });
        $(document).on("change", "#updateNeighborhood", function() {
            let selected = $(this).val();
            $.post("<?= asset_url("home/getQuarters") ?>", {
                "neighborhood": selected,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                quarter.find("option[value!='']").remove();
                response.forEach(function(el, i) {
                    $("#updatePostalCode").val(el.postal_code);
                    let html = "<option value=' " + el.quarter + "'  " + (el.quarter == "<?= $item->quarter ?>" ? "selected" : null) + "  >" + el.quarter + "</option>";
                    quarter.append(html);
                });
            }, "JSON");
        });
        $(document).on("change", "#updateQuarter", function() {
            let selected = $(this).val();
            $.post("<?= asset_url("home/getQuarters/") ?>" + encodeURIComponent(selected), {
                "quarter": selected,
                "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
            }, function(response) {
                $("#updatePostalCode").val(response.postal_code);
            }, "JSON");
        });
        $(document).on("click", ".btnUpdate", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            let url = $(this).data("url");
            let formData = new FormData(document.getElementById("updateAddress"));
            formData.append("<?= $this->security->get_csrf_token_name() ?>", "<?= $this->security->get_csrf_hash() ?>");
            createAjax(url, formData, function() {
                closeModal("#addressModal");
                $("#addressPull2").load("<?= asset_url("home/get_address/chooseable") ?>");
                $("#addressPull").load("<?= asset_url("home/get_address") ?>");
            });
        });
    });
</script>