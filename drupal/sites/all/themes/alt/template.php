<?php

/**
 * returns a grid960 grid <div>
 * @param $content
 * @param $grid
 * @param $class
 * @return unknown_type
 */
function grid($content, $grid, $class = "") {
	
	return "<div class='grid_{$grid} {$class}'>{$content}</div>";
	
}

function gdv() {
	
	echo krumo(get_defined_vars());
	
}

/**
 * Preprocess page
 * - set up page layout vars
 * 
 * @param $v	array	page variables passed by reference
 * @return none
 * @author Richard Allsebrook <richard@futurate.com>
 */
function alt_preprocess_page(&$v, $hook) {

  if ( context_get('context','context_ui-civicrm') ) {

    // If in CiviCRM remove the secondary region to maximise the screen width
    // so that CiviCRM screens don't overflow.
    $v['secondary'] = false;
  }
    
    // Make footer links open in a new window on CiviCRM pages
    drupal_add_js("
      $(document).ready(function() {  
        var footerLinks = $('body.page-civicrm #footer .links a[href^=\'/\']');
        $(footerLinks).attr('title','Opens in printable form in a new smaller window');
        $(footerLinks).click(function(e){
          window.open('/print'+$(this).attr('href'),'alt_new'
            ,'width=750,height=660,toolbar=1');
          e.preventDefault();
        });
      });  
    ",'inline');
    
  // Make service links open in a new window
  drupal_add_js("
    $(document).ready(function() {  
      $('.service-links a').click(function(e){
        window.open($(this).attr('href'),'alt_new'
          ,'width=750,height=660,toolbar=1');
        e.preventDefault();
      });
    });  
  ",'inline');
    
  // Tidy up carousel
  drupal_add_js("
    $(document).ready(function() {  
    	var h1 = $('ul#viewscarousel-sponsoring-member-block-1').height();
      $('ul#viewscarousel-sponsoring-member-block-1 li img').each(function(){
        var h2 = $(this).height();
        if ((h1-h2)>0) { 
        	$(this).css('margin-top',(h1-h2)/2);
        }
      });
    });  
  ",'inline');
  // Need to make sure that the scripts variable has been updated to include
  // any additional JS added during the preprocess
  $v['scripts'] = drupal_get_js();

	/**
	 * layout measurements
	 * This assumes a basic 3 column layout (souce order: primary, secondary, tertiaty)
	 * to be displayed secondary, primary, tertiary
	 */
	
	// total number of columns
	$v['total_grid']     = 16;

	// secondary column width
	$v['secondary_grid'] = 4;
	
	// tertiary column width
	$v['tertiary_grid']  = 4;
	
	// primary column width (total_grid - (secondary if used) - (teriary if used)
	$v['primary_grid']   = $v['total_grid'] - ($v['secondary'] ? $v['secondary_grid'] : 0) - ($v['tertiary'] ? $v['tertiary_grid'] : 0);
	
	// how much to push the primary column right (secondary_grid if secondary column used)
	$v['primary_push']   = ($v['secondary'] ? $v['secondary_grid'] : 0);
	
	// how much to pull the secondary column left (as it comes after primary in source order) so it appears before primary 
	$v['secondary_pull'] = $v['total_grid'] - ($v['tertiary'] ? $v['secondary_grid'] + $v['tertiary_grid'] : $v['secondary_grid']);

	// menus
	$v['header_links'] = menu_navigation_links("menu-header-links");
	$v['footer_links'] = menu_navigation_links("menu-footer-links");
	
	// helper vars
	$v['theme_path'] = path_to_theme();
	$v['context'] = context_context(CONTEXT_GET);
	
	//print_r($v); die();
	
	if ($v['node']->type=='alt_conference') {
	  $view = views_get_view('altc_highlight') or die('no such view');
	}
	
	// are we in an altc section?
	// traverse up menu till we either hit a altc content type or run out of parents

	$trail = menu_get_active_trail();
	
	$count = count($trail);
	
	for($i = $count; $i--; $i > 0) {
		
		$link_path = $trail[$i]['link_path'];
		
		$tmp = menu_get_object("node", 1, $link_path);
		
		if ($tmp && $tmp->type == "alt_conference") {
			
			$colour_scheme = $tmp->field_altc_colour[0]['value'];
			
			$v['body_classes'] = "altc_theme_". $colour_scheme;
			
			break;

		}
		
	}
	
	
	
	// dont let page node override page title
	if ($v['is_front']) {
		$v['head_title'] = $v['site_name'];
	}
	
}

/**
 * format the page breadcrumb trail
 * @param $breadcrumb
 * @return unknown_type
 */
function alt_breadcrumb($breadcrumb) {
	
  if (!empty($breadcrumb)) {
  	
    return '<div class="breadcrumb">'. implode(' &gt; ', $breadcrumb) .'</div>';
    
  }
  
}


function alt_preprocess_node(&$variables) {
  $node = $variables['node'];

  if (module_exists('taxonomy')) {
    $variables['taxonomy'] = taxonomy_link('taxonomy terms', $node);
  }
  else {
    $variables['taxonomy'] = array();
  }

  if ($variables['teaser'] && $node->teaser) {
    $variables['content'] = $node->teaser;
  }
  elseif (isset($node->body)) {
    $variables['content'] = $node->body;
  }
  else {
    $variables['content'] = '';
  }

  $variables['date']      = format_date($node->created);
  $variables['changed'] = format_date($node->changed);
  $variables['links']     = !empty($node->links) ? theme('links', $node->links, array('class' => 'links inline')) : '';
  $variables['name']      = theme('username', $node);
  // Set the last user to edit the document as the "editor"
  $variables['editor']    = theme('username', user_load($node->revision_uid));
  $variables['node_url']  = url('node/'. $node->nid);
  
  // terms
  switch($node->type) {
  	
  	case "news_item":
  		$variables['terms'] = alt_theme_news_taxonomy($variables['taxonomy']);
  		break;
  		
  	default: 
  		$variables['terms'] = theme('links', $variables['taxonomy'], array('class' => 'links inline'));
  		break;
  	
  } 
  $variables['title']     = check_plain($node->title);

  // Flatten the node object's member fields.
  $variables = array_merge((array)$node, $variables);
  
  // Output page update information, except on homepage
  if (!$variables['is_front']) $variables['updated'] = 'Page last updated on '.$variables['changed'].' ('.$variables['editor'].')';

  // Display info only on certain node types.
  if (theme_get_setting('toggle_node_info_'. $node->type)) {
    $variables['submitted'] = theme('node_submitted', $node);
    $variables['picture'] = theme_get_setting('toggle_node_user_picture') ? theme('user_picture', $node) : '';
  }
  else {
    $variables['submitted'] = '';
    $variables['picture'] = '';
  }
  
  if ($node->type=='media_release') {
    $suggestions[] = 'node-news_item';
    $variables['template_files'] = $suggestions;
  }
  
  // Clean up name so there are no underscores.
  $variables['template_files'][] = 'node-'. $node->type;
}


/**
 * format a news item topics list
 * @param $taxonomy
 * @return unknown_type
 */
function alt_theme_news_taxonomy($taxonomy) {
	
	$out = "";
	
	$count = count($taxonomy);
	$i = 1;
	
	foreach ($taxonomy as $tid=>$attr) {
		
		if ($count>1 && $i == $count) {
			$out .= " and ";
		}
		elseif ($i > 1) {
			$out .= ", ";
		}
		
		$out .= "<a href='" . base_path() . $attr['href'] . "'>" . $attr['title'] . "</a>";
		
		$i++;
		
	}
	
	if ($out) {
		$out = "<span class='links'>" . $out . "</span>";
	}
	
	return $out;
	
}

/**
 * alternative in_array(). Tests if one or more of $needle are in $haystack
 * 
 * e.g.
 *   echo any_in_array(array("a", "x", "z"), array("a", "b", "c")); 
 *   returns true ("a" is in $haystack)
 * 
 * @param $needles	array	things to search for
 * @param $haystack array	thing to search in
 * 
 * @return true if any of $needles in $haystack
 */
function any_in_array($needles = array(), $haystack = array()) {
 
	foreach($needles as $needle) {
 
		if (in_array($needle, $haystack)) {
 
			return true;
 
		}
 
	}
 
	return false;
 
}
