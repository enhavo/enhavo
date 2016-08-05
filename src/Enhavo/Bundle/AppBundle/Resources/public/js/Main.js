define(['jquery', 'app/Admin'], function($, admin) {
  $(function() {
    admin.initDescriptionTextPosition();
    admin.initNavigation();
    admin.initUserMenu();
  });
});