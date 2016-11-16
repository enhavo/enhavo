define(['jquery', 'app/Admin', 'app/Form', 'tinymce'], function($, admin, form, tinymce) {
  function Translation() {
    var self = this;
    var switchToLocale;
    var currentLocale = 'de';
    var switchLanguageMutex = false;

    this.getSwitchToLocale = function() {
      return switchToLocale;
    };

    this.getCurrentLocale = function() {
      return currentLocale;
    };

    this.isSwitchLanguageMutex = function() {
      return switchLanguageMutex;
    };

    this.switchLanguage = function(locale) {
      switchLanguageMutex = true;
      switchToLocale = locale;
      $('[data-translation-input]').each(function () {
        if($(this).data('translation-input') == locale) {
          $(this).addClass('active');
        } else {
          $(this).removeClass('active');
        }
      });
      $('[data-translation-current]').each(function () {
        if($(this).data('translation-current') == locale) {
          $(this).addClass('active');
        } else {
          $(this).removeClass('active');
        }
      });
      var id;
      for (id in tinymce.editors) {
        tinymce.editors[id].execCommand('switchLanguage');
      }
      currentLocale = locale;
      switchLanguageMutex = false;
    };

    this.initTranslationSwitcher = function(form) {
      $(form).on('click', '[data-translation-switch]', function() {
        var locale = $(this).data('translation-switch');
        self.switchLanguage(locale);
      });
    };

    this.init = function () {
      $(document).on('formOpenAfter', function (event, form) {
        self.initTranslationSwitcher(form);
      });

      $(document).on('gridAddAfter', function (event, data) {
        window.setTimeout(function() {
          self.switchLanguage(currentLocale);
        }, 10);
      });
    };

    self.init();
  }
  return new Translation();
});