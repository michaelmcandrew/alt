<?php

/**
 *
 *  @file
 *  Administrative functionality for Empty Page module.
 *
 */


/**
 * System settings form.
 * 
 * @return struct
 * - Settings form array.
 */
function empty_page_settings_form(&$form_state) {
  
  $form = array();
  
  return system_settings_form($form);
}


function empty_page_admin_overview() {
  // Clear menu cache.
  
  $output = theme('fieldset', array('#title' => t('Manage'), '#value' => theme('empty_page_callbacks_manage_render')));
  
  return $output;
}


function empty_page_admin_delete_form(&$form_state, $cid) {
  //drupal_set_message('<pre>Deleting editor '. print_r($id, 1) .'</pre>');
  // Get current editor properties.
  $callback = empty_page_get_callback($cid);
  
  drupal_set_title('Delete callback');
  
  $form = confirm_form(
    array(
      'callback' => array(
        '#type' => 'value',
        '#value' => $callback,
      ),
    ),
    t('Are you sure you want to delete the callback for <em>!path</em>?', array('!path' => $callback->path)),
    'admin/build/empty-page',
    t('This action cannot be undone.'),
    t('Delete callback'),
    t('Cancel')
  );
  
  $form['#redirect'] = 'admin/build/empty-page';
  
  return $form;
}


function empty_page_admin_delete_form_submit($form, &$form_state) {
  empty_page_delete_callback($form_state['values']['callback']->cid);
  // Clear menu cache.
  empty_page_clear_menu_cache();
  drupal_set_message(t('Callback <em>!path</em> deleted.', array('!path' => $form_state['values']['callback']->path)));
}


function empty_page_callbacks_form(&$form_state, $cid = NULL) {
  // If $cid exists, we're editing.
  if (isset($cid)) {
    $callback = empty_page_get_callback($cid);
  }
  
  if ($callback) {
    $form['empty_page_callback_cid'] = array(
      '#type' => 'hidden',
      '#value' => $callback->cid,
    );
    $form_title = t('Edit callback');
  }
  else {
    $form_title = t('Create a new callback');
  }
  $form['empty_page_basic'] = array('#type' => 'fieldset', '#title' => $form_title, '#description' => '', '#collapsible' => TRUE);
  $form['empty_page_basic']['empty_page_callback_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Internal path'),
    '#description' => '',
    '#default_value' => $callback ? $callback->path : '',
  );
  $form['empty_page_basic']['empty_page_callback_page_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Page title'),
    '#description' => '',
    '#default_value' => $callback ? $callback->page_title : '',
  );
  $form['empty_page_basic']['buttons']['submit'] = array(
    '#type' => 'submit',
    '#value' => $callback ? t('Save') : t('Add'),
  );
  
  return $form;
}


function empty_page_callbacks_form_validate($form, &$form_state) {
  if (empty($form_state['values']['empty_page_callback_path'])) {
    form_set_error('empty_page_callback_path', t('You must provide an <em>Internal path</em> for your callback.'));
  }
}


function empty_page_callbacks_form_submit($form, &$form_state) {
  //drupal_set_message('<pre>'. print_r($form_state, 1) .'</pre>');
  
  $is_existing_callback = isset($form_state['values']['empty_page_callback_cid']);
  $callback = new stdClass();
  
  if ($is_existing_callback) {
    // We're editing an existing callback.
    $callback->cid = $form_state['values']['empty_page_callback_cid'];
  }
  else {
    // This is a new editor.
    $callback->created = time();
  }
  
  $data = array();
  //$data['permissions'] = $form_state['values']['empty_page_callback_permissions'];
  
  $callback->path = $form_state['values']['empty_page_callback_path'];
  $callback->page_title = $form_state['values']['empty_page_callback_page_title'];
  // TODO: Handle saving of custom perms.
  //$callback->data = serialize($data);
  $callback->changed = time();
  
  empty_page_save_callback($callback);
  
  empty_page_clear_menu_cache();
  
  drupal_set_message(t('Changes saved.'));
  
  $redirect = 'admin/build/empty-page';
  drupal_goto($redirect);
}

