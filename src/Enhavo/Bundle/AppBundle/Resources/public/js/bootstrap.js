var form = null;
var admin = null;
var templating = null;

$(function() {
  templating = new Templating();
  admin = new Admin(Routing, templating, Translator);
  admin.initNavigation();
  form = new Form(Routing, templating, admin, Translator);
});