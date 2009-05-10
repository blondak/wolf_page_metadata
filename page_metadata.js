// Used keywords
var keyword_hash = {};

page_metadata_remove = function(keyword_css_id, keyword) {
  // Hide the row with the data
  Element.fade($(keyword_css_id));
  // Clear value, backend will remove the keyword with empty values
  $(keyword_css_id + '-field').value = '';
  // Remove from the keyword hash, so that the keyword can be added again
  delete(keyword_hash[keyword]);
}

page_metadata_add = function(url, i18n_keyword, i18n_value) {
  keyword = $('Page-metadata-new-keyword').value;
  value = $('Page-metadata-new-value').value;
  
  // Keyword empty or already used?
  // NOTE: empty values don't care
  if (keyword == '' || keyword_hash[keyword]) {
    Element.show($('Page-metadata-new-popup-error'));
    return false;
  }

  // Get template from backend
  new Ajax.Updater('Page-metadata-container', url, {
    asynchronous: true,
    evalScripts: false,
    parameters: { "keyword": keyword, "value": value },
    insertion: 'bottom'
  });

  // Add keyword to hash
  keyword_hash[keyword] = true;

  // Hide form
  Element.hide($('Page-metadata-new-popup'));
  Element.hide($('Page-metadata-new-popup-error'));

  // Set back to default values
  $('Page-metadata-new-keyword').value = i18n_keyword;
  $('Page-metadata-new-value').value = i18n_value;
};


(function($) {
  // Document load
  $(function() {
    // Build an hash with the (existing) keywords to avoid duplicate entries
    $('#Page-metadata-container label').each(function() {
      keyword = ($(this).text());
      if (keyword) {
        keyword_hash[keyword] = true;
      }
    });
  });
})(jQuery);