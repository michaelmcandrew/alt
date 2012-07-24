<?php
// $Id: menu_site_map.admin.inc,v 1.1 2009/10/15 18:10:43 ghing Exp $

/**
 * @file Form generating and form handling functions for the settings pages of 
 *       Menu Site Map module.
 */

// Define some constants for default settings to make later code more readable.
define('MENU_SITE_MAP_DEFAULT_DEPTH', 2);
define('MENU_SITE_MAP_MAX_DEPTH', 3);

/**
 * Menu callback; presents the Menu Site Map settings page.
 */
function menu_site_map_settings_form() {
  $form['menu_site_map_menus'] = array(
    '#type' => 'fieldset',
    '#title' => t('Include these menus in the site map'),
    '#tree' => TRUE,
  );

  // Get all menu names and titles
  $menus = menu_get_menus(); 

  // Get the menus that the site map already knows about.
  $site_map_menus = variable_get('menu_site_map_menus', array());

  // Add unknown menus to the array and add titles
  foreach ($menus as $name => $title) {
    if (!isset($site_map_menus[$name])) {
      $site_map_menus[$name] = array('weight' => 0, 'included' => FALSE);
    }

    $site_map_menus[$name]['title'] = $title;
  }

  // Sort the menus by weight, not name
  uasort($site_map_menus, "_menu_site_map_menus_cmp_weight");

  foreach ($site_map_menus as $name => $properties) {
    $form['menu_site_map_menus'][$name]['title']['#value'] = l($properties['title'], 'admin/build/menu-customize/' . $name); 
    $form['menu_site_map_menus'][$name]['included'] = array(
      '#type' => 'checkbox',
      '#default_value' => $properties['included'],
    );
    $form['menu_site_map_menus'][$name]['weight'] = array(
      '#type' => 'weight',
      '#delta' => 50,
      '#default_value' => $properties['weight'],
    );
  }

  $display_style_options = array(
    'default' => t('Default'),
    'lists' => t('Nested Lists'),
    'theme' => t('Theme override'),
  );
  $form['menu_site_map_display_style'] = array(
    '#type' => 'radios',
    '#title' => t('Display Style'),
    '#default_value' => variable_get('menu_site_map_display_style', 'default'),
    '#options' => $display_style_options,
    '#description' => t("Should the menus be output in the default style (headings and lists) or as nested lists (similar to the output of menu_tree())?  Alternately, one can further customize the output of the menu-based site map by overriding this module's theme functions in a theme's template.php."),
  );

  $depths = drupal_map_assoc(range(0, MENU_SITE_MAP_MAX_DEPTH)); 

  $form['menu_site_map_menu_depth'] = array(
    '#type' => 'select',
    '#title' => t('Menu depth'),
    '#default_value' => variable_get('menu_site_map_menu_depth', 
                                     MENU_SITE_MAP_DEFAULT_DEPTH),
    '#options' => $depths,
    '#description' => t('Enter the depth of the menu that should be shown in the sitemap.'),
  );

  $form['menu_site_map_move_second_level_leafs'] = array(
    '#type' => 'checkbox',
    '#title' => 'Move second-level items without entries to the end?',
    '#default_value' => variable_get('menu_site_map_move_second_level_leafs',
                                     TRUE),
    '#description' => t('On the second level of menus, should items without children be moved to the end of the output?'),
  );

  $form['buttons']['submit'] = array('#type' => 'submit', '#value' => t('Save configuration') );
  $form['buttons']['reset'] = array('#type' => 'submit', '#value' => t('Reset to defaults') );

  if (!empty($_POST) && form_get_errors()) {
    drupal_set_message(t('The settings have not been saved because of the errors.'), 'error');
  }
  $form['#submit'][] = 'menu_site_map_settings_form_submit';

  return $form;
} 

/**
 * Generate the administrative settings form.
 *
 * @return Form array.
 */
function theme_menu_site_map_settings_form ($form) {
  drupal_add_tabledrag('menu-site-map-settings-select-menu', 'order', 'sibling', 'menu-weight');

  $header = array(
    t('Menu'),
    array('data' => t('Included in site map'), 'class' => 'checkbox'),
    t('Weight'),
  );
  
  $menus = menu_get_menus();
  $rows = array();
  foreach (array_keys($form['menu_site_map_menus']) as $name) {
    if (isset($menus[$name])) {

      $element = &$form['menu_site_map_menus'][$name]; 
      // Add special classes to be used for tabledrag.js.
      $element['weight']['#attributes']['class'] = 'menu-weight';
      // Change the weight field to a hidden. This allows any value but hides the field.

      $row = array();
      $row[] = drupal_render($element['title']);
      $row[] = array('data' => drupal_render($element['included']), 'class' => 'checkbox');
      $row[] = drupal_render($element['weight']);

      $rows[] = array(
        'data' => $row,
        'class' => 'draggable',
      );
    }
  }

  $output = '';
  $output .= drupal_render($form['menu_site_map_menus']);
  if ($rows) {
    $output .= theme('table', $header, $rows, array('id' => 'menu-site-map-settings-select-menu'));
  }
  $output .= drupal_render($form);
  return $output;
}

/**
 * Form submission handler for the settings form.
 */
function menu_site_map_settings_form_submit($form, &$form_state) {
  $op = isset($form_state['values']['op']) ? $form_state['values']['op'] : '';

  // Exclude unnecessary elements.
  unset($form_state['values']['submit'], $form_state['values']['reset'], $form_state['values']['form_id'], $form_state['values']['op'], $form_state['values']['form_token'], $form_state['values']['form_build_id']);

  foreach ($form_state['values'] as $key => $value) {
    if ($op == t('Reset to defaults')) {
      variable_del($key);
    }
    else {
      if (is_array($value) && isset($form_state['values']['array_filter'])) {
        $value = array_keys(array_filter($value));
      }
      variable_set($key, $value);
    }
  }
  if ($op == t('Reset to defaults')) {
    drupal_set_message(t('The configuration options have been reset to their default values.'));
  }
  else {
    drupal_set_message(t('The configuration options have been saved.'));
  }

  // Delete any menu link data that we have for menus that are no longer
  // part of the site map.
  if ($op == t('Reset to defaults')) {
      db_query("DELETE FROM {menu_site_map_links}");
  }
  else {
    $query = "SELECT DISTINCT menu_name FROM {menu_site_map_links}";
    $result = db_query($query);
    $menu_names = array();
    while ($row = db_fetch_array($result)) {
      $menu_name = $row['menu_name'];
      if (!isset($form_state['values']['menu_site_map_menu'][$menu_name])) {
        db_query("DELETE FROM {menu_site_map_links} WHERE menu_name = '%s'",
                 $menu_name);
      }
    }
  }  

  cache_clear_all();
  drupal_rebuild_theme_registry();
  
}

/**
 * Load site map specific link information for a given link.
 */
function site_map_menu_link_load($mlid) {
  if (is_numeric($mlid) && $item = db_fetch_array(db_query("SELECT * FROM {menu_site_map_links} WHERE mlid = %d", $mlid))) {
    return $item;
  }
  return FALSE;
}

/**
 * Recursive helper function for menu_site_map_select_menu_links_form().
 *
 * Based on _menu_overview_tree_form().
 */
function _menu_site_map_select_menu_tree_form($tree, $depth, $max_depth) {
  if ($depth <= $max_depth) {
    static $form = array('#tree' => TRUE);
    foreach ($tree as $data) {
      $title = '';
      $item = $data['link'];
      // Don't show callbacks; these have $item['hidden'] < 0.
      if ($item && $item['hidden'] >= 0) {
        $mlid = 'mlid:'. $item['mlid'];
        if ($smitem = site_map_menu_link_load($item['mlid'])) {
          // We've altered this menu link
          // in our site map
          $item['included'] = $smitem['included'];
        }
        else {
          $item['included'] = TRUE;
        }
        $form[$mlid]['#item'] = $item;
        $form[$mlid]['#attributes'] = $item['included'] ? array('class' => 'menusite-map-excluded') : array('class' => 'menu-site-map-included');
        $form[$mlid]['title']['#value'] = l($item['title'], $item['href'], $item['localized_options']);
        $form[$mlid]['included'] = array(
          '#type' => 'checkbox',
          '#default_value' => $item['included'],
        );

        $form[$mlid]['mlid'] = array(
          '#type' => 'hidden',
          '#value' => $item['mlid'],
        );
      }

      if ($data['below']) {
        _menu_site_map_select_menu_tree_form($data['below'], $depth + 1, $max_depth);
      }
    }
    return $form;
  }
  else {
    return NULL;
  }
} // function _menu_site_map_select_menu_tree_form

/**
 * Theme the menu selection form into a table.
 */
function theme_menu_site_map_select_menu_links_form($form) {
  $header = array(
    t('Menu item'),
    array('data' => t('Included in site map'), 'class' => 'checkbox'),
  );

  $rows = array();
  foreach (element_children($form) as $mlid) {
    if (isset($form[$mlid]['included'])) {
      $element = &$form[$mlid];
      
      $row = array();
      $row[] = theme('indentation', $element['#item']['depth'] - 1) . drupal_render($element['title']);
      $row[] = array('data' => drupal_render($element['included']), 'class' => 'checkbox');
      $rows[] = $row;
    }
  }
  $output = '';
  if ($rows) {
    $output .= theme('table', $header, $rows, array('id' => 'menu-site-map-select-menu-links'));
  }
  $output .= drupal_render($form);
  return $output;
} 

/**
 * Menu callback; presents the form to decide which portions of the menu to include in the site map.
 */
function menu_site_map_select_menu_links_form() {
  $site_map_menus = variable_get('menu_site_map_menus', null);
  uasort($site_map_menus, "_menu_site_map_menus_cmp_weight");
  $max_depth = variable_get('menu_site_map_menu_depth', 2);
  $form = array();

  foreach ($site_map_menus as $name => $menu) {
    if ($menu['included']) {
      $menu_tree = menu_site_map_tree_all_data($name);
      $tree_form = _menu_site_map_select_menu_tree_form($menu_tree, 0, $max_depth);
      $form = array_merge($form, $tree_form);
    }
  }

  $form['submit'] = array('#type' => 'submit', '#value' => t('Save'));

  return $form;
} // function menu_site_map_select_menu_form

/**
 * Save site map specific information about a link.
 */
function menu_site_map_link_save(&$item) {
  $existing_item = db_fetch_array(db_query("SELECT * FROM {menu_site_map_links} WHERE mlid = %d", $item['mlid']));  
  if ($existing_item) {
    // We've somehow altered this menu item before for the site map.

    if ($item['included']) {
      // The site map menu item has gone from excluded to included, we can 
      // just delete our record of this.
      db_query("DELETE FROM {menu_site_map_links} WHERE mlid = %d",
               $item['mlid']);
    }
    else {
      db_query("UPDATE {menu_site_map_links} SET included = %d WHERE mlid = %d",
               $item['included'], $item['mlid']);
    }
  }
  else {
    // Before we just used the default weight, parent, and included the
    // menu item in the site map.  This has changed somehow, so we need to
    // remember this.
    db_query("INSERT INTO {menu_site_map_links} 
              (site_map_name, menu_name, mlid, included)
              VALUES ('%s', '%s', %d, %d)",
             'default', $item['menu_name'], $item['mlid'], $item['included']);
  }
}

/*
 * Submit handler for site map menu item selection form.
 *
 * This is heavily based on menu_overview_form_submit() in menu.admin.inc
 * of the Drupal core.
 */
function menu_site_map_select_menu_links_form_submit($form, &$form_state) {
  // When dealing with saving menu items, the order in which these items are
  // saved is critical. If a changed child item is saved before its parent,
  // the child item could be saved with an invalid path past its immediate
  // parent. To prevent this, save items in the form in the same order they
  // are sent by $_POST, ensuring parents are saved first, then their children.
  // See http://drupal.org/node/181126#comment-632270
  $order = array_flip(array_keys($form['#post'])); // Get the $_POST order.
  $form = array_merge($order, $form); // Update our original form with the new order.
  $updated_items = array();
  $fields = array('included');

  foreach (element_children($form) as $mlid) {
    if (isset($form[$mlid]['#item'])) {
      $element = $form[$mlid];
      // Update any fields that have changed in this menu item.
      foreach ($fields as $field) {
        if ($element[$field]['#value'] != $element[$field]['#default_value']) {
          $element['#item'][$field] = $element[$field]['#value'];
          $updated_items[$mlid] = $element['#item'];
        }
      }

      // Did we exclude the parent item and forget to exclude the child?
      // If so, exclude the child too.
      if ($element['included']['#value'] == TRUE && 
          isset($updated_items['mlid:' . $element['#item']['plid']]) &&
          !$updated_items['mlid:' . $element['#item']['plid']]['included']) {
        $element['#item']['included'] = FALSE;
        $updated_items[$mlid] = $element['#item'];
      }
    }
  }

  // Save all our changed items to the database.
  foreach ($updated_items as $item) {
    $item['customized'] = 1;
    menu_site_map_link_save($item);
  }
}
