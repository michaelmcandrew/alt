<div class='node-news_item'>
	
	<?php if ($page == 0): ?>
    <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php else: ?>
    <h1><?php print $title; ?></h1>
  <?php endif; ?>
	
	<?php if ($field_news_item_subheading[0]['value']): ?>
	  <h2 class='subheading'><?php echo $field_news_item_subheading[0]['value'] ?></h2>
	<?php elseif ($field_media_subheading[0]['value']): ?>
	  <h2 class='subheading'><?php echo $field_media_subheading[0]['value'] ?></h2>
	<?php endif; ?>
	
	<div class='submitted'>
		<div class='author'><?php echo $submitted ?></div>
		<div class='rss'>
		  <?php if ($type=='media_release'): ?>
		  <a href='<?php echo base_path() . "news/media_releases/rss"?>'>Subscribe to Media Release updates</a>
		  <?php else: ?>
		  <a href='<?php echo base_path() . "news/rss"?>'>Subscribe to News updates</a>
		  <?php endif; ?>
		</div>
		<div class='clear'>&nbsp;</div>
	</div>
	
	<div class='body'><?php echo $body ?></div>
	<div class='clear'>&nbsp;</div>

	<?php if ($terms): ?>	
		<div class='terms'>Topics: <?php echo $terms ?></div>
	<?php endif; ?>
	
	<div class='updated'><?php echo $updated; ?></div>
	
	<?php if ($links): ?>
		<div class="links"><?php print $links; ?></div>
	<?php endif; ?>
	
</div>
