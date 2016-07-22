var Templating = function()
{
  var self = this;

  this.render = function(templateSelector, parameters)
  {
    var text = $(templateSelector).html();
    return self.replace(text, parameters);
  };

  this.replace = function(text, parameters)
  {
    if(parameters) {
      for (var key in parameters) {
        if (parameters.hasOwnProperty(key)) {
          var regExp = new RegExp('{'+key+'}', 'ig');
          text = text.replace(regExp, parameters[key]);
        }
      }
    }
    return text;
  };
};