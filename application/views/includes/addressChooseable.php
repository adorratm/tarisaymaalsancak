<?php if (!empty($address_informations)) : ?>
    <?php foreach ($address_informations as $key => $value) : ?>
        <address class="mb-3 border p-3">
            <p class="mb-3">
                <strong class="fw-bolder text-dark-green">
                    <?= $value->title ?>
                </strong>
            </p>
            <address class="mb-3"><?= $value->address ?> <?= $value->quarter ?> <?= $value->neighborhood ?> <?= $value->district ?> <?= $value->city ?>, <?= $value->country ?> <?= $value->postalCode ?> </address>
            <p class="mb-3"> <?= lang("addressPhone") ?> : <?= $value->phone ?> </p>
            <div class="d-flex flex-wrap align-items-center align-self-center">
                <div class="flex-grow-1 pe-3">
                    <a rel="dofollow" data-url="<?= asset_url("home/editAddressForm/{$value->id}") ?>" href="javascript:void(0)" class="btn border w-100 p-3 mb-3 mb-lg-0 mb-xl-0 editAddressBtn" title="<?= lang("addressEdit") ?>"><i class="fa fa-edit"></i> <?= lang("addressEdit") ?></a>
                </div>
                <div class="flex-grow-1 pe-3">
                    <button aria-label="<?=$settings->company_name?>" data-url="<?= asset_url("home/delete_address/{$value->id}") ?>" class="btn w-100 border p-3 mb-3 mb-lg-0 mb-xl-0 deleteAddress"><i class="fa fa-trash"></i> <?= lang("addressDelete") ?></button>
                </div>
                <div class="flex-shrink-1">
                    <?php if (empty($this->session->choosedAddress) && $key == 0) : ?>
                        <?php $this->session->set_userdata("choosedAddress", $value->id) ?>
                    <?php endif; ?>
                    <input type="radio" id="<?= $value->title ?><?= $key ?>" <?= (!empty($this->session->choosedAddress) && $value->id == $this->session->choosedAddress ? "checked" : ($key == 0 ? "checked" : null)) ?> name="address" class="address" onchange="changeSelectedAddress($(this))" onclick="$(this).change()" value="<?= $value->id ?>">
                    <label for="<?= $value->title ?><?= $key ?>" class="h3 my-auto py-auto"><?= lang("chooseThisAddress") ?></label>
                </div>
            </div>

        </address>
    <?php endforeach ?>
<?php endif ?>