<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<div class="tab-pane fade" id="about-informations" role="tabpanel" aria-labelledby="about-informations-tab">
	<div class="row">
		<div class="form-group col-md-12">
			<label>Hakkımızda Bilgisi</label>
			<textarea name="about_us" class="m-0 tinymce" required>
				<?= $item->about_us; ?>
			</textarea>
		</div>
	</div>
</div>