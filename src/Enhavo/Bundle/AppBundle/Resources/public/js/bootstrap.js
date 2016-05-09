var form = null;
var admin = null;
var templating = null;
var user = null;
$(function() {
  templating = new Templating();
  admin = new Admin(Routing, templating, Translator);
  admin.initNavigation();
  admin.initUserMenu();
  form = new Form(Routing, templating, admin, Translator);
  user = new User(admin);
  newsletter = new Newsletter(Routing, Translator, admin);
});
