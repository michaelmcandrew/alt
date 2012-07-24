<div id='pre_header_wrapper'>
<div id='pre_header' class='grid_16'>
	<a class='hidden' href='#primary'>Skip to content</a>
	<div id='google_translate' class='grid_8 alpha'>
<?php if ($_SERVER['HTTPS']==null): ?>
		<a href="javascript:var%20t%3D((window.getSelection%26%26window.getSelection())%7C%7C(document.getSelection%26%26document.getSelection())%7C%7C(document.selection%26%26document.selection.createRange%26%26document.selection.createRange().text))%3Bvar%20e%3D(document.charset%7C%7Cdocument.characterSet)%3Bif(t!%3D'')%7Blocation.href%3D'http%3A%2F%2Ftranslate.google.com%2F%3Ftext%3D'%2Bt%2B'%26hl%3Den%26langpair%3Dauto%7Ccy%26tbb%3D1%26ie%3D'%2Be%3B%7Delse%7Blocation.href%3D'http%3A%2F%2Ftranslate.google.com%2Ftranslate%3Fu%3D'%2BencodeURIComponent(location.href)%2B'%26hl%3Den%26langpair%3Dauto%7Ccy%26tbb%3D1%26ie%3D'%2Be%3B%7D%3B">Translate to Welsh</a>
		 :: Powered By 
		<a href='http://translate.google.com/'><img src='http://translate.googleapis.com/translate_static/img/mini_google.png' alt='Google'/> Translate</a>
<?php else: ?>
  &nbsp;
<?php endif; ?>
	</div>
	<div id='auth' class='grid_6 push_2 omega'>
	<?php if ($user->uid == 0): ?>
		<a href='<?php echo $base_path ?>user'>Sign In</a> | <strong><?php echo l('Join ALT','node/28') ?></strong>
	<?php else: ?>
		Welcome back <a href='<?php echo $base_path ?>user'><?php echo $user->name ?></a> | <?php echo l('Your dashboard','civicrm/user') ?> | <a href='<?php echo $base_path ?>logout'>Sign Out</a>
	<?php endif; ?>
	</div>
	<div class='clear'>&nbsp;</div>
</div>
<div class='clear'>&nbsp;</div>
</div>

<div id='header' class='container_16'>
  	<div class='logo grid_4'>
		<a href='<?php echo check_url($front_page) ?>'><img src='<?php echo $logo; ?>' alt='<?php echo $site_name ?>'/></a>
  	</div>
  	<div class='inner'>
  
  		<div class='grid_12 alpha omega'>
  			<div class='links'>
  				<?php echo theme("links", $header_links) ?>
  			</div>
  		</div>
  		<div class='clear'>&nbsp;</div>
  		
  		<div class='grid_6 alpha'>
  			<div class='slogan'>
  				<?php echo $site_slogan ?>
  			</div>
  		</div>
  		<div class='grid_6 omega'>
  			<?php echo $search_box; ?><?php //print drupal_get_form('google_cse_searchbox_form');?>
  		</div>
  		<div class='clear'>&nbsp;</div>
  		
  	</div>
  	<div class='clear'>&nbsp;</div>
</div>

<div id='primary_links' class='container_16'>
	<div class='grid_16'>
	<?php echo theme("links", $primary_links) ?>
	</div>
	<div class='clear'>&nbsp;</div>
</div>
	
<?php if ($breadcrumb): ?>
<div id='breadcrumb' class='container_16'>
	<div class='wrapper grid_16'>
	You are here: <?php echo $breadcrumb; ?>
	</div>
	<div class='clear'>&nbsp;</div>
</div>
<?php endif; ?>
