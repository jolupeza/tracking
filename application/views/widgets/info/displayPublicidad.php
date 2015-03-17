<div class="widget">
    <figure class="widget__img">
    <?php if (!empty($publi->post_excerpt)) : ?>
    	<a href="<?php echo $publi->post_excerpt; ?>" target="_blank" title="<?php echo $publi->post_title; ?>">
        	<img class="img-responsive" src="<?php echo $publi->guid; ?>"></img>
        </a>
       <?php else : ?>
			<img class="img-responsive" src="<?php echo $publi->guid; ?>"></img>
   		<?php endif; ?>
    </figure><!-- end widget__info -->
</div><!-- end widget -->