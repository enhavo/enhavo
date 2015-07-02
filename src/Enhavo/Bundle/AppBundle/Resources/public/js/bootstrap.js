var form = null;
var admin = null;
var templating = null;

$(function() {
  templating = new Templating();
  admin = new Admin(Routing, templating, Translator);
  form = new Form(Routing, templating, admin, Translator);
});