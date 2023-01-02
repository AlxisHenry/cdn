<?php if (!($_SESSION['connected'] ?? false)) { ?>
    <section class='page-section' id="search">
<?php } else { ?>
	<section class='masthead page-section' id="search">
<?php } ?>
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
		<?= searchResults() ?>
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
<?php
    $background = false;
    foreach (getCategories() as $category) {
        echo generateCategorySection($category, $background);
        $background = !$background;
    }
?>