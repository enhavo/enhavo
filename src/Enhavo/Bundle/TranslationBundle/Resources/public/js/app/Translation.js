define(['jquery', 'app/Admin', 'app/Form', 'tinymce', 'app/Router'], function($, admin, form, tinymce, router) {
  function Translation() {
    var self = this;
    var switchToLocale;
    var currentLocale;
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

    this.initCurrentLocale = function() {
        if(!currentLocale) {
          currentLocale = $('[data-translation-current-locale]').data('translation-current-locale');
        }
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
      $('[data-translation-switch]').removeClass('active');
      $('[data-translation-current]').removeClass('active');

      $('[data-translation-switch="'+locale+'"]').addClass('active');
      $('[data-translation-current="'+locale+'"]').addClass('active');

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
        self.initCurrentLocale();
        self.initTranslationSwitcher(form);
        if(currentLocale) {
          self.switchLanguage(currentLocale);
        }
      });

      $(document).on('previewOpenBefore', function (event, data) {
        data.link = router.generate(data.route, {
          locale: currentLocale
        });
      });

      $(document).on('gridAddAfter', function (event, data) {
        window.setTimeout(function() {
          self.initCurrentLocale();
          self.switchLanguage(currentLocale);
        }, 10);
      });

      $(document).on('formListAddItem', function (event, data) {
        window.setTimeout(function() {
          self.initCurrentLocale();
          self.switchLanguage(currentLocale);
        }, 10);
      });

      self.initCurrentLocale();
    };

    self.init();
  }
  return new Translation();
});