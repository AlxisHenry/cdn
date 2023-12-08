		<div class="copyright py-4 text-center text-white">
			<div class="switch-compact-mode">
				<div>
					<p>
						<input type="checkbox" id="change-view" checked="true">
						<label for="change-view" aria-describedby="label"><span class="ui"></span>Compact mode</label>
					</p>
				</div>
			</div>
			<div class="container"><small>&copy; <?= date('Y') . " " . $dashboard::settings()->getTitle() ?></small></div>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
		<?= @$asset::new(["build/js/scripts.js", "build/js/main.js"]); ?>
		<?= $swal; ?>
	</body>
</html>
