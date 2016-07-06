var form = null;
var admin = null;
var templating = null;
var user = null;
var newsletter = null;
var button = null;
$(function() {
  templating = new Templating();
  admin = new Admin(Routing, templating, Translator);
  admin.initNavigation();
  admin.initUserMenu();
  form = new Form(Routing, templating, admin, Translator);
  newsletter = new Newsletter(Routing, Translator, admin);
  search = new SearchForm(Routing, admin);
  button = new Button(admin, form, Routing, Translator);
});
