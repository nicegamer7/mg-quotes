<?php global $quote; ?>

<blockquote class="mg_qt_quote">
	<?php echo $quote['content']; ?>
	<?php if (!empty($quote['author'])): ?>
		<footer class="meta">
			<cite class="author"><?php echo $quote['author']; ?></cite>
		</footer>
	<?php endif; ?>
</blockquote>