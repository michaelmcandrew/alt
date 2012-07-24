<?php

/**
 * Implementation of hook_context_default_contexts().
 */
function alt_groups_members_context_default_contexts() {
  $items = array();

  $items['context_ui-section-alt_group'] = array(
    'namespace' => 'context_ui',
    'attribute' => 'section',
    'value' => 'alt_group',
    'description' => '',
    'node' => array(
      '0' => 'alt_group',
    ),
    'block' => array(
      'views_alt_group_members-block_1' => array(
        'module' => 'views',
        'delta' => 'alt_group_members-block_1',
        'weight' => 31,
        'region' => 'post_content',
        'status' => '0',
        'label' => 'alt_group_members: Block: Members by Group',
        'type' => 'context_ui',
      ),
    ),
  );
  return $items;
}

/**
 * Implementation of hook_imagecache_default_presets().
 */
function alt_groups_members_imagecache_default_presets() {
  $items = array(
    'altc_speaker_thumb' => array(
      'presetname' => 'altc_speaker_thumb',
      'actions' => array(
        '0' => array(
          'weight' => '0',
          'module' => 'imagecache',
          'action' => 'imagecache_scale',
          'data' => array(
            'width' => '67',
            'height' => '',
            'upscale' => 0,
          ),
        ),
      ),
    ),
  );
  return $items;
}

/**
 * Implementation of hook_node_info().
 */
function alt_groups_members_node_info() {
  $items = array(
    'alt_group' => array(
      'name' => t('ALT Group'),
      'module' => 'features',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'has_body' => '1',
      'body_label' => t('Body'),
      'min_word_count' => '0',
      'help' => t('<br />
'),
    ),
    'alt_group_member' => array(
      'name' => t('ALT Group Member'),
      'module' => 'features',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Individual\'s name'),
      'has_body' => '1',
      'body_label' => t('Description'),
      'min_word_count' => '0',
      'help' => t('<br />'),
    ),
  );
  return $items;
}

/**
 * Implementation of hook_views_api().
 */
function alt_groups_members_views_api() {
  return array(
    'api' => '2',
  );
}
