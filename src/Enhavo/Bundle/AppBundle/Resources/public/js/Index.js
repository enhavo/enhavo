define(['jquery', 'app/Admin', 'app/Form', 'app/Button', 'media/UploadForm', 'grid/ContentForm'], function($, admin, form, button, uploadForm, contentForm) {
  $(function() {
    admin.initBlocks();
    admin.initActions();
    admin.initAfterSaveHandler();
  });
});