define(['jquery', 'app/Admin', 'app/Form', 'app/Button', 'media/UploadForm', 'grid/ContentForm', 'search/SearchForm'], function($, admin, form, button, uploadForm, contentForm, searchForm) {
  $(function() {
    admin.initTable();
    admin.initBlocks();
    admin.initActions();
    admin.initAfterSaveHandler();
    admin.initEscButton();
  });
});