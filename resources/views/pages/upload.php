<section class='<?= !Auth::check() ?: 'masthead' ?> page-section' id="upload">
	<div class="container">
		<h2 class='page-section-heading text-center text-uppercase'>Upload</h2>
		<div class='divider-custom'>
			<div class='divider-custom-line'></div>
			<div class='divider-custom-icon'><i class='fas fa-star'></i></div>
			<div class='divider-custom-line'></div>
		</div>
		<div class="">
			<p class="text-center">This page allows you to upload files to the server. The max file size to upload is <?= formatFilesize(config('MAX_FILE_SIZE')) ?>.</p>
		</div>
		<div class="container d-flex justify-content-center mb-5 uploadbar">
			<form class="form-inline my-2 my-lg-0 d-flex gap-1" style="width: 70%; <?= Auth::check() ?: "cursor: not-allowed;" ?>" <?= Auth::check() ? 'method="POST" action="/files.php" enctype="multipart/form-data"' : 'disabled' ?>>
				<input type="hidden" id="action" name="action" value="upload">
				<input type="hidden" id="token" name="token" value="<?= !Auth::check() ?: $_SESSION["token"] ?>">
				<input class="form-control mr-sm-2" type="file" name="file" placeholder="Upload file" aria-label="Upload file" <?= Auth::check() ?: 'disabled' ?>>
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit" <?= Auth::check() ?: 'disabled' ?> id="upload-button">Upload</button>
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
		<?= latestUploads() ?>
	</div>
</section>