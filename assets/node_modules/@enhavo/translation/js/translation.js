require(['translation/app/Translation', 'tinymce', 'jquery'], function(translation, tinymce, jquery) {
  //load tinymce plugin
  tinymce.PluginManager.add('enhavo_translation', function(editor, url) {
    var $translationDom = $('#' + editor.id).parents('[data-translation]');

    editor.on('change', function(ed, e) {
      if(!translation.getCurrentLocale() || translation.isSwitchLanguageMutex()) {
        return
      }
      //save content
      var content = editor.getContent();
      $translationDom.find('[data-translation-value=' + translation.getCurrentLocale() + ']').val(content);
    });

    editor.addCommand('switchLanguage', function(ui, v) {
      if(!translation.getCurrentLocale()) {
        return;
      }
      //load content
      var value = $translationDom.find('[data-translation-value=' + translation.getSwitchToLocale() + ']').val();
      editor.setContent(value);
    });
  });
});