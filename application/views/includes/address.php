<?php if (!empty($address_informations)) : ?>
    <?php foreach ($address_informations as $key => $value) : ?>
        <address class="p-3" style="border:2px solid var(--main-theme-color);">
            <p class="mb-3">
                <strong class="font-weight-bolder text-dark-green">
                    <?= $value->title ?>
                </strong>
            </p>
            <address class="mb-3"><?= $value->address ?> <?= $value->quarter ?> <?= $value->neighborhood ?> <?= $value->district ?> <?= $value->city ?>, <?= $value->country ?> <?= $value->postalCode ?> </address>
            <p class="mb-3"> <?= lang("addressPhone") ?> : <?= $value->phone ?> </p>
            <a rel="dofollow" data-url="<?= asset_url("home/editAddressForm/{$value->id}") ?>" href="javascript:void(0)" class="show-more-btn hover-btn me-1 editAddressBtn" title="<?= lang("addressEdit") ?>"><i class="fa fa-edit"></i> <?= lang("addressEdit") ?></a>
            <button aria-label="<?= $settings->company_name ?>" data-url="<?= asset_url("home/delete_address/{$value->id}") ?>" class="show-more-btn hover-btn deleteAddress"><i class="fa fa-trash"></i> <?= lang("addressDelete") ?></button>
        </address>
    <?php endforeach ?>
<?php endif ?>