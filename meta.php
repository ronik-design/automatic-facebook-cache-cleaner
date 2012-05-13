<div class="afu_linter_post_control">

	<?
	$afu_linter_tolint_url = get_permalink($post->ID);
	?>
	<iframe style="display:none" src="http://developers.facebook.com/tools/debug/og/object?q=<?=$afu_linter_tolint_url?>">
    </iframe>
	FB's cache for this post/page is automatically flushed whenever you save it, so your shares will always be up to date.<br/>
	* <b>Test <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?=$afu_linter_tolint_url?>">sharing this on Facebook</a></b> | * Check <a href="http://developers.facebook.com/tools/debug/og/object?q=<?=$afu_linter_tolint_url?>" target="_blank">FB's debug page for this URL</a> | * <b>Read <a target="_blank" href="http://automaticfacebookcachecleaner.com">our FAQ and tips for optimizing shares</a></b>.
	
</div>