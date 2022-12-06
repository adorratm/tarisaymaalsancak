<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<form id="updateCoupon" onsubmit="return false" action="" method="post">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Başlık </label>
                <input class="form-control form-control-sm rounded-0" placeholder="Başlık" name="title" value="<?= $item->title; ?>" required>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>İndirim Oranı </label>
                <input class="form-control form-control-sm rounded-0" placeholder="İndirim Oranı" name="discount" value="<?= $item->discount; ?>" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Sepet Minimum Tutarı </label>
                <input class="form-control form-control-sm rounded-0" placeholder="Sepet Minimum Tutarı" name="minPrice" value="<?= $item->minPrice; ?>" required>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Kullanıcıya Tanımla</label>
                <select name="user_id" class="form-control form-control-sm rounded-0">
                    <option value="" <?= (empty($item->user_id) ? "selected" : null) ?>>Tüm Kullanıcılarda Geçerli</option>
                    <?php if (!empty($users)) : ?>
                        <?php foreach ($users as $k => $v) : ?>
                            <option value="<?= $v->id ?>" <?= (!empty($item->user_id) && $v->id == $item->user_id ? "selected" : null) ?>><?= $v->full_name ?> - <?= $v->email ?> - <?= $v->phone ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Başlangıç Tarihi</label>
                <input type="text" name="startedAt" placeholder="Başlangıç Tarihi" class="form-control form-control-sm datetimepicker" data-flatpickr data-alt-input="true" data-enable-time="true" data-enable-seconds="true" value="<?= !empty($item->startedAt) ? $item->startedAt : date("Y-m-d H:i:s") ?>" data-default-date="<?= !empty($item->startedAt) ? $item->startedAt : date("Y-m-d H:i:s") ?>" data-date-format="Y-m-d H:i:S" required>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label>Bitiş Tarihi</label>
                <input type="text" name="finishedAt" placeholder="Bitiş Tarihi" class="form-control form-control-sm datetimepicker" data-flatpickr data-alt-input="true" data-enable-time="true" data-enable-seconds="true" value="<?= !empty($item->finishedAt) ? $item->finishedAt : date("Y-m-d H:i:s") ?>" data-default-date="<?= !empty($item->finishedAt) ? $item->finishedAt : date("Y-m-d H:i:s") ?>" data-date-format="Y-m-d H:i:S" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label>Dil</label>
                <input type="text" class="form-control form-control-sm rounded-0" name="lang" disabled value="<?= !empty($item->lang) ? $item->lang : "tr" ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button role="button" data-url="<?= base_url("coupons/update/{$item->id}"); ?>" class="btn btn-sm btn-outline-primary rounded-0 btnUpdate">Güncelle</button>
            <a href="javascript:void(0)" onclick="closeModal('#couponModal')" class="btn btn-sm btn-outline-danger rounded-0">İptal</a>
        </div>
    </div>
</form>