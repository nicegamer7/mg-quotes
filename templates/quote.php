<?php global $mg_qt; ?>

<blockquote class="mg_qt_quote">
	<p><?php echo $mg_qt['quote']; ?></p>
	<?php if (!empty($mg_qt['author'])): ?>
		<footer class="meta">
			<cite class="author"><?php echo $mg_qt['author']; ?></cite>
		</footer>
	<?php endif; ?>
</blockquote>