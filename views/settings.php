<h1><?php echo __('Page Metadata settings'); ?></h1>
<h2><?php echo __('Delete metadata table'); ?></h2>

<a href="#" id="<?php echo $css_id_prefix; ?>delete-table-link"><?php echo __("Delete Page Metadata table"); ?></a>

<div id="<?php echo $css_id_prefix; ?>delete-table" style="display: none;">
  <?php echo __('Once you delete your table, there is no going back. Please be certain.'); ?>
  <form id="<?php echo $css_id_prefix; ?>delete-table-form" action="<?php echo $plugin_url;?>cleanup" method="post">
    <input type="submit" id="cleanup" value="<?php echo __("Delete Page Metadata table"); ?>"/>
  </form>
</div>
<script type="text/javascript">
//<![CDATA[
(function($) {
  // Document load
  $(function() {
    $('#<?php echo $css_id_prefix; ?>delete-table-link').click(function() {
      // Show confirmation
      $('#<?php echo $css_id_prefix; ?>delete-table').css('display', 'block');
      return false;
    });
  });
})(jQuery);
//]]>
</script>