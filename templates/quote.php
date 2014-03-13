<?php global $mg_qt; ?>

<blockquote class="mg_qt_quote">
	<?php echo $mg_qt['quote']; ?>
	<?php if (!empty($mg_qt['author'])): ?>
		<footer>
			<cite class="author"><?php echo $mg_qt['author']; ?></cite>
		</footer>
	<?php endif; ?>
</blockquote>