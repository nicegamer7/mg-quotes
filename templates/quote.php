<?php global $mg_qt; ?>

<blockquote class="mg_qt_quote">
	<?php echo $mg_qt['quote']; ?>
	<footer>
		<?php if (!empty($mg_qt['quote'])): ?>
			<cite class="author"><?php echo $mg_qt['author']; ?></cite>
		<?php endif; ?>
	</footer>
</blockquote>