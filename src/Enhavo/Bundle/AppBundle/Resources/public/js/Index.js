define(['jquery', 'app/admin', 'app/form', 'app/button', 'media/upload-form'], function($, admin, form, button, uploadForm) {
  $(function() {
    admin.initBlocks();
    admin.initActions();
    admin.initAfterSaveHandler();
  });
});