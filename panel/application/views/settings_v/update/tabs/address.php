<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<div class="tab-pane fade" id="address-informations" role="tabpanel" aria-labelledby="address-informations-tab">
	<div class="row">
		<div class="form-group col-md-12">
			<label>Adres Bilgisi</label>
			<textarea name="address" class="m-0 tinymce" required>
				<?= $item->address; ?>
			</textarea>
		</div>
		<div class="form-group col-md-12">
			<label>Harita Bilgisi</label>
			<input name="map" class="m-0 form-control form-control-sm rounded-0" value="<?=htmlspecialchars(html_entity_decode($item->map))?>" required>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-12">
			<label>Adres Bilgisi 2</label>
			<textarea name="address_2" class="m-0 tinymce" required>
				<?= $item->address_2; ?>
			</textarea>
		</div>
		<div class="form-group col-md-12">
			<label>Harita Bilgisi (Embed) 2</label>
			<input name="map_2" class="m-0 form-control form-control-sm rounded-0" value="<?=htmlspecialchars(html_entity_decode($item->map_2))?>" required>
		</div>
	</div>
</div>