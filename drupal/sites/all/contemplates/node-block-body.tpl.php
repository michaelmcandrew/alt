<?php if ($node->field_block_subheading[0]['view']): ?>
<div class='subheading'><?php print $node->field_block_subheading[0]['view'] ?></div>
<?php endif; ?>
<?php print $node->field_block_icon[0]['view'] ?>
<?php print $node->content['body']['#value'] ?>