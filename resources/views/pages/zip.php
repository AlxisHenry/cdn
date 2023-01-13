<section class='<?= !Auth::check() ?: 'masthead' ?> page-section' id="zip">
	<div class="container">
		<h2 class='page-section-heading text-center text-uppercase'>Archive</h2>
		<div class='divider-custom'>
			<div class='divider-custom-line'></div>
			<div class='divider-custom-icon'><i class='fas fa-star'></i></div>
			<div class='divider-custom-line'></div>
		</div>
		<div class="">
			<p class="text-center">This page contains all the files that have been uploaded to the server. You can choose some files and click the button below to download them in a zip file.</p>
		</div>
		<div class="download-button w-100 mb-4 mt-3 flex justify-items-center">
			<button class="download-btn btn btn-primary w-100 p-4" id="download-button">Download</button>
		</div>
		<?= $items // @phpstan-ignore-line ?>
	</div>
</section>