<?php

/**
 * Private Download admin configuration form.
 */
function private_download_admin_form() {
  $form['private_download_directory'] = array(
    '#type' => 'textfield',
    '#title' => t('private download directory'),
    '#default_value' => variable_get('private_download_directory', 'private'),
    '#required' => TRUE,
    '#description' => t('The directory where the private download files will be stored. Drupal will need to have write access to this directory.'),
  );

  $htaccess = variable_get('private_download_htaccess', "<IfModule mod_rewrite.c>\n  RewriteEngine on\n  RewriteBase ". base_path() ."system/files/private\n  RewriteRule ^(.*)$ $1 [L,R=301]\n</IfModule>");
  $form['private_download_htaccess'] = array(
    '#type' => 'textarea', 
    '#title' => t('htaccess content'),
    '#default_value' => $htaccess,
    '#required' => TRUE,
    '#rows' => 8,
    '#description' => t('Contents of htaccess file in the private download directory. The RewriteBase path must start with the base path ("/" plus the relative directory path from the webroot, if any) followed by "system/" and the !file_system directory name and ending with the private download directory name defined above. Exp. /system/files/private, /drupal/system/files/private', array('!file_system' => l('file system path', 'admin/settings/file-system'))),
  );

  $header = variable_get('private_download_header', "Content-Transfer-Encoding: binary\nCache-Control: private");
  $form['private_download_header'] = array(
    '#type' => 'textarea',
    '#title' => t('file headers'),
    '#default_value' => $header,
    '#required' => TRUE,
    '#rows' => 4,
    '#description' => t('Enter a list of header attributes, one entry per line.'),
  );

  $form['private_download_disposition'] = array(
    '#type' => 'radios',
    '#title' => t('Download behavior'),
    '#default_value' => variable_get('private_download_disposition', 'attachment'),
    '#options' => array(
      'attachment' => t('attachment - always trigger a "save as..." dialog'),
      'inline' => t('inline - attempt to display the file within the browser window if its type matches one of the patterns below'),
    ),
    '#required' => TRUE,
  );

  $inline_patterns = variable_get('private_download_inline_patterns', array('^text/', '^image/', '\bflash$', '\bpdf$'));
  $form['private_download_inline_patterns'] = array(
    '#type' => 'textarea',
    '#title' => t('Inline type patterns'),
    '#default_value' => implode("\n", $inline_patterns),
    '#rows' => 4,
    '#description' => t('Enter a list of MIME type patterns, one entry per line. This has only effect when option <em>inline</em> is selected in field <em>Download behavior</em> above.'),
  );

  $form['private_download_allowdeny_priority'] = array(
    '#type' => 'radios',
    '#title' => t('Allow/Deny priority'),
    '#default_value' => variable_get('private_download_allowdeny_priority', 'deny'),
    '#options' => array(
      'deny'  => t('<em>deny</em> has priority over <em>allow</em>'),
      'allow' => t('<em>allow</em> has priority over <em>deny</em>'),
    ),
    '#required' => TRUE,
    '#disabled' => !count(module_implements('private_download_access')),
  );
  if (count(module_implements('private_download_access'))) {
    $form['private_download_allowdeny_priority']['#description'] = t('Determines behavior when there are installed modules altering private download permissions: when <em>deny</em> has priority over <em>allow</em>, no module can allow but only deny access; when <em>allow</em> has priority over <em>deny</em>, any module may allow access bypassing the <em>access private download directory</em> permission.');
  }
  else {
    $form['private_download_allowdeny_priority']['#disabled'] = TRUE;
    $form['private_download_allowdeny_priority']['#description'] = t('This option has no effect here as there is currently no installed module altering private download permissions.');
  }

  $form['#submit'][] = 'private_download_admin_submit_handler';
  return system_settings_form($form);
}

/**
 * Validate handler.
 */
function private_download_admin_form_validate($form, &$form_state) {
  $directory = file_directory_path() .'/'. $form_state['values']['private_download_directory'];
  if (!file_check_directory($directory, TRUE)) {
    form_set_error('private_download_directory', t('The private download directory does not exist and the system was unable to create it. Please check your !file_system settings.', array('!file_system' => l('file system', 'admin/settings/file-system'))));
  }

  $needle = '/system/files/'. $form_state['values']['private_download_directory'] ."\r";
  if (!strstr($form_state['values']['private_download_htaccess'], $needle)) {
    form_set_error('private_download_htaccess', t('The RewriteBase path does not equal %path in htaccess content.', array('%path' => '/system/files/'. $form_state['values']['private_download_directory'])));
  }

  $inline_patterns = array_filter(array_map('trim', explode("\n", $form_state['values']['private_download_inline_patterns'])));
  form_set_value($form['private_download_inline_patterns'], $inline_patterns, $form_state);
}

/**
 * Custom submit handler.
 */
function private_download_admin_submit_handler($form, &$form_state) {
  $filename = file_directory_path() .'/'. $form_state['values']['private_download_directory'] .'/.htaccess';
  if (!private_download_write($filename, $form_state['values']['private_download_htaccess'])) {
    // Failed to write the .htaccess file; log the error and return.
    watchdog('private_download', t('Unable to write data to file: !filename', array('!filename' => $filename)), 'error');
    return;
  }

  // Clear menu cache to recognize private download directory.
  cache_clear_all('*', 'cache_menu', TRUE);

  drupal_set_message(t('Remember to set private download directory access permissions for permitted roles on the !permissions_url page.', array('!permissions_url' => l('permissions', 'admin/user/permissions'))));
}