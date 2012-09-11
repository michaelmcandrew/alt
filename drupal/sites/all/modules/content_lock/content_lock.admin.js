/* -*- indent-tabs-mode: nil; js-indent-level: 2; -*- */
/**
 * @file
 *   Javascriptize the administration page.
 */

Drupal.behaviors.content_lock_admin = function() {
  var js_obj = jQuery('input[name="content_lock_unload_js"]');
  var js_message_obj = jQuery('input[name="content_lock_unload_js_message"]');
  var js_message_enable_obj = jQuery('input[name="content_lock_unload_js_message_enable"]');
  var js_fieldset_obj = js_message_enable_obj.parents('fieldset');

  function check_js() {
    if (js_obj.is(':checked')) {
      js_fieldset_obj.removeAttr('disabled');
    } else {
      js_fieldset_obj.attr('disabled', 'disabled');
    }
  }

  function check_js_message_enable() {
    if (js_message_enable_obj.is(':checked')) {
      js_message_obj.removeAttr('disabled');
    } else {
      js_message_obj.attr('disabled', 'disabled');
    }
  }

  /* Do not lose the existing message when the message is disabled */
  js_message_obj.parents('form').submit(function() {
    js_fieldset_obj.removeAttr('disabled');
    js_message_obj.removeAttr('disabled');
  });

  js_message_enable_obj.click(check_js_message_enable);
  js_obj.click(check_js);
  check_js_message_enable();
  check_js();
};
