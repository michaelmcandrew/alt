<?php
// $Id: comment_moderation.tpl.php,v 1.1.2.2 2009/04/16 15:25:37 davidstosik Exp $

/**
 * @file
 * Default theme implementation for comments moderation.
 *
 * Available variables:
 * - $email: author's e-mail, with mailto link.
 * - $homepage: author's website link.
 * - $author: Comment author. Can be link or plain text.
 * - $content: Body of the post.
 * - $date: Date and time of posting.
 * - $links: Various operational links.
 * - $new: New comment marker.
 * - $picture: Authors picture.
 * - $signature: Authors signature.
 * - $status: Comment status. Possible values are:
 *   comment-unpublished, comment-published or comment-preview.
 * - $submitted: By line with date and time.
 * - $title: Linked title.
 * - $ip_whois: the comment hostname, linking to http://tools.whois.net
 *
 * These two variables are provided for context.
 * - $comment: Full comment object.
 * - $node: Node object the comments are attached to.
 *
 * @see template_preprocess_comment_moderation()
 * @see theme_comment_moderation_view()
 */
?>
<div class="comment <?print $status ?> clear-block">
  <?php
    if ($navlinks) {
      print $navlinks;
    }
    if ($links) {
      print $links;
    }
  ?>
  <br />

  <?php print $comment->picture ?>

  <div>
    <strong><?php print t('Author') ?>:</strong> <?php print $author .' ('. $ip_whois .')' ?>
    <br />
  
    <?php if ($email): ?>
      <strong><?php print t('Email') ?>:</strong> <?php print $email ?>
      <br />
    <?php endif; ?>
    <?php if ($homepage): ?>
      <strong><?php print t('Homepage') ?>:</strong> <?php print $homepage ?>
      <br />
    <?php endif; ?>
    <strong><?php print t('Date') ?>:</strong> <?php print $date ?>
    <br />
  </div>

  <h3><?php print $title ?></h3>
  <div class="content">
    <?php print $content ?>
    <?php if ($signature): ?>
    <div class="user-signature clear-block">
      <?php print $signature ?>
    </div>
    <?php endif; ?>
  </div>

</div>
