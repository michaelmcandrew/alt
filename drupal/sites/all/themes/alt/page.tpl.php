<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='<?php echo $language->language ?>' lang='<?php echo $language->language ?>' dir='<?php echo $language->dir ?>'>
<head>
	<title><?php echo $head_title; ?></title>
	<?php echo str_replace('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />','',$head); ?>
	<?php echo $styles; ?>
	
	<?php echo $scripts; ?>
</head>
<body class='<?php echo $body_classes; ?>' id='top'>
<div class='container_<?echo $total_grid ?>'>
	<?php include "elements/site_header.php"; ?>
	
	<div id='content'>
		<div id='primary' class='grid_<?php echo $primary_grid ?> push_<?php echo $primary_push ?>'>
		
			<?php if ($show_messages && $messages): ?>
				<?php print $messages; ?> 
			<?php endif; ?>
			
			<?php if ($pre_tabs): ?>
				<?php print $pre_tabs; ?> 
			<?php endif; ?>
			
			<?php if ($tabs): ?>
				<?php echo $tabs; ?>
			<?php endif; ?>
		
			<?php if (!any_in_array(array("page-node", "page-altc"), $template_files)): ?>
			<h1><?php echo $title ?></h1>
			<?php endif; ?>
			
			<?php if ($pre_content) : ?>
				<div id='pre_content'>
				<?php echo $pre_content; ?>
				</div>
			<?php endif; ?>

			<?php echo $content; ?>
			
			<?php if ($post_content): ?>
				<div id='post_content'>
				<?php if ($is_front): ?>
					<div class='news_and_events'>
						<div class='header'>
							<h3>News and Events</h3>
							<div class='subscribe'><?php echo l('Subscribe to updates', 'node/121') ?></div>
						</div>
						<div class='content_wrapper'>
							<?php echo $post_content; ?>
						</div>
						<div class='clear'>&nbsp;</div>
					</div>
				<?php else: ?>
					<?php echo $post_content; ?>
				<?php endif; ?>
					<div class='clear'>&nbsp;</div>
				</div>
			<?php endif; ?>
			
			
			<?php if ($post_content_2_col_1 or $post_content_2_col_2): ?>
			<div id='post_content_2_col_col_1'>
				<?php echo $post_content_2_col_1; ?>
			</div>
			<div id='post_content_2_col_col_2'>
				<?php echo $post_content_2_col_2; ?>
			</div>
			<div class='clear'>&nbsp;</div>
			<?php endif; ?>
			
		</div>
		
		<?php if ($secondary): ?>
		<div id='secondary' class='grid_<?php echo $secondary_grid ?> pull_<?php echo $secondary_pull ?>'>
			<?php echo $secondary; ?>
		</div>
		<?php endif; ?>
		
		<?php if ($tertiary): ?>
		<div id='tertiary' class='grid_<?php echo $tertiary_grid ?>'>
			<?php echo $tertiary; ?>
		</div>
		<?php endif; ?>
		<div class='clear'>&nbsp;</div>
	</div>
	
	<?php include "elements/site_footer.php"; ?>
</div>	
<?php echo $closure; ?>
</body>
</html>
