<?php print $node->field_altc_image[0]['view'] ?>
<h1><?php print $node->title ?></h1>
<?php if ($node->field_altc_subheading[0]): ?>
<div class='subheading'><?php print $node->field_altc_subheading[0]['view'] ?></div>
<?php endif; ?>
<?php print $node->content['body']['#value'] ?>
