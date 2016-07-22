define(['jquery', 'app/admin', 'app/form', 'app/button'], function($, admin, form, button) {
  $(function() {
    admin.initBlocks();
    admin.initActions();
    admin.initAfterSaveHandler();
  });
});