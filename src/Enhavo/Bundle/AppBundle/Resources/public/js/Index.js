define(['jquery', 'app/Admin', 'app/Form', 'app/Button'], function($, admin, form, button) {
  $(function() {
    admin.initBlocks();
    admin.initActions();
    admin.initAfterSaveHandler();
    admin.initEscButton();
  });
});