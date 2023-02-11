<section class='<?= !$auth ?: 'masthead' ?> page-section' id="search">
    <div class="container">
		<h2 class='page-section-heading text-center text-uppercase'>Search</h2>
		<div class='divider-custom'>
			<div class='divider-custom-line'></div>
		    <div class='divider-custom-icon'><i class='fas fa-star'></i></div>
			<div class='divider-custom-line'></div>
		</div>
		<div class="container d-flex justify-content-center mb-5 searchbar">
			<form class="form-inline my-2 my-lg-0 d-flex gap-1" style="width: 70%;" method="GET" action="#search">
				<input class="form-control mr-sm-2" type="search" name="search" placeholder="Search filename" aria-label="Search" value="<?= $_GET['search'] ?? "" ?>">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
		<?= $searchResults // @phpstan-ignore-line ?>
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
<?php
    $background = false;
    // @phpstan-ignore-next-line
    foreach ($categories as $category) {
        echo \App\Classes\Category::section($category, $background);
        $background = !$background;
    }
?>
