/**
 * Created by gseidel on 30/08/14.
 */


function UploadForm(routing)
{
  var self = this;

  this.initUploadForm = function(form) {
    $(form).find('.uploadForm').each(function(index, element) {
      $(element).find('.fileupload').fileupload({
        dataType: 'json',

        done: function (event, data) {
          var list = $(element).find('.list');
          var inputName = list.attr('data-name');
          $.each(data.result.files, function (index, file) {

            var html = templating.render($(element).find('script[data-id=files-template]'), {
              file_id: file.id,
              full_name: inputName
            });

            $(element).find('.list').append(html);
          });
          $(element).find('.progress .bar').css('width', '0%');
        },

        add: function (event, data) {
          data.submit();
        },

        progressall: function (event, data) {
          var progress = parseInt(data.loaded / data.total * 100, 10);
          $(element).find('.progress .bar').css('width', progress + '%');
        },

        dropZone: $(element).find('.dropzone')
      });

      $(element).find('.upload-button').click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        $(element).find('.fileupload').trigger('click');
      });

      $(element).bind('click', '.imgdelete', function(event)
      {
        var imageContainer = $(event.target).parentRecursive('.imgContainer');
        if(imageContainer != null) {
          imageContainer.remove();
        }
      });
    });

    var start = function() {
      $(document).bind('dragover', function (e)
      {
        $('.dropzone').css('background-color', 'red');
      });

      $(document).bind('dragleave drop', function (e)
      {
        $('.dropzone').css('background-color', 'palegreen');
      });
    };

    start();
  };

  var init = function() {
    $(document).on('formOpenAfter', function(event, form) {
      self.initUploadForm(form);
    });
  };

  init();
}
var uploadForm = new UploadForm(Routing);






