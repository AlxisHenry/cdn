<section class='<?= !$auth ?: 'masthead' ?> page-section' id="upload">
	<div class="container">
		<h2 class='page-section-heading text-center text-uppercase'>Upload</h2>
		<div class='divider-custom'>
			<div class='divider-custom-line'></div>
			<div class='divider-custom-icon'><i class='fas fa-star'></i></div>
			<div class='divider-custom-line'></div>
		</div>
		<div class="">
			<p class="text-center">This page allows you to upload files to the server. The max file size to upload is <?= $helper::formatFilesize($helper::config('MAX_FILE_SIZE')) ?>.</p>
		</div>
		<div class="container d-flex justify-content-center mb-5 uploadbar">
			<form class="form-inline my-2 my-lg-0 d-flex gap-1" style="width: 70%; <?= $auth ?: "cursor: not-allowed;" ?>" <?= $auth ? 'method="POST" action="/files.php" enctype="multipart/form-data"' : 'disabled' ?>>
				<input type="hidden" id="action" name="action" value="upload">
				<input type="hidden" id="token" name="token" value="<?= !$auth ?: $_SESSION["token"] ?>">
				<input class="form-control mr-sm-2" type="file" name="files[]" placeholder="Upload files" aria-label="Upload files" multiple <?= $auth ?: 'disabled' ?>>
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit" <?= $auth ?: 'disabled' ?> id="upload-button">Upload</button>
			</form>
		</div>
	</div>
</section>
<section class='page-section bg-primary-light text-white mb-0' id="latest">
	<div class="container">
		<h2 class='page-section-heading text-center text-uppercase text-white'>Latest uploads</h2>
		<div class='divider-custom divider-light'>
			<div class='divider-custom-line'></div>
			<div class='divider-custom-icon'><i class='fas fa-star'></i></div>
			<div class='divider-custom-line'></div>
		</div>
		<?= $latestUploads // @phpstan-ignore-line ?>
	</div>
</section>