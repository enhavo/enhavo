var form = null;
var admin = null;
var templating = null;

$(function() {
  templating = new Templating();
  admin = new Admin(Routing, templating, Translator);
  admin.initNavigation();
  admin.initUserMenu();
  form = new Form(Routing, templating, admin, Translator);
});