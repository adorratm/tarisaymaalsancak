<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form onsubmit="return false" method="post" enctype="multipart/form-data" id="updateJson">
    <div class="mb-3 nav-tabs-horizontal">
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <?php $i = 0 ?>
            <?php if (!empty($content)) : ?>
                <?php foreach ($content as $key => $v) : ?>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link  <?= ($i == 0 ? "active" : null) ?>" data-toggle="tab" href="#tab<?= $key ?>" role="tab" aria-controls="tab" aria-selected="true"><?= $key ?></a>
                    </li>
                    <?php $i++ ?>
                <?php endforeach ?>
            <?php endif ?>
        </ul>
        <div class="tab-content" id="myTabContent">
            <?php $i = 0 ?>
            <?php if (!empty($content)) : ?>
                <?php foreach ($content as $key => $v) : ?>
                    <div class="tab-pane <?= ($i == 0 ? "active show" : null) ?>" id="tab<?= $key ?>" role="tabpanel">
                        <div class="row">
                            <?php if (!empty((array)$v)) : ?>
                                <?php foreach ((array)$v as $v_key => $value) : ?>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                        <div class="form-group">
                                            <?php if (is_object($value)) : ?>
                                                <label for="k<?= $v_key ?>"><?= $value->top ?></label>
                                                <input class="form-control" id="k<?= $v_key ?>top" type="hidden" name="<?= "{$key}[{$v_key}][top]" ?>" value="<?= $value->top ?>">
                                                <input class="form-control" id="k<?= $v_key ?>" type="text" name="<?= "{$key}[{$v_key}][value]" ?>" value="<?= $value->value ?>">
                                            <?php else : ?>
                                                <label for="v<?= $v_key ?>"><?= $v_key ?></label>
                                                <input class="form-control" id="v<?= $v_key ?>" type="text" name="<?= "{$key}[{$v_key}]" ?>" value="<?= $value ?>">
                                            <?php endif ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>
                    <?php $i++ ?>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>
    <button data-url="<?= base_url("settings/update_json/$item->id"); ?>" type="button" class="btn btn-sm btn-outline-primary rounded-0 btnUpdateJson">Güncelle</button>
    <a href="javascript:void(0)" onclick="closeModal('#settingsModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
</form>