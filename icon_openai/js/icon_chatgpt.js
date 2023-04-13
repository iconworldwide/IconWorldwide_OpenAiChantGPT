(function ($, Drupal, once) {
  Drupal.behaviors.iconOpenaiBehavior = {
    attach: function (context, settings) {
      once('myCustomBehavior', '.icon-openai-key-form', context).forEach(function (element) {
        $('#drupal-modal').on('dialogopen', function(event, ui) {
          if ($(this).parent().hasClass('icon-chatgpt-modal-container')) {
            new ClipboardJS('.copyOpenAiAnswer');
          }
        })
      });
    }
  };
})(jQuery, Drupal, once);
