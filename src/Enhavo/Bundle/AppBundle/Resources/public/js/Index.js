define(['jquery', 'app/admin', 'app/form', 'app/button', 'media/upload-form', 'grid/ContentForm'], function($, admin, form, button, uploadForm, contentForm) {
  $(function() {
    admin.initBlocks();
    admin.initActions();
    admin.initAfterSaveHandler();
  });
});