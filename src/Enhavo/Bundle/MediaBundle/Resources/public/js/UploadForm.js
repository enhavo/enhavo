/**
 * Created by gseidel on 30/08/14.
 */


function UploadForm(routing)
{
  var self = this;

  this.initUploadForm = function(form) {
    var currentIndexes = [];
    var isMultiple = [];
    $(form).find('.uploadForm').each(function(formIndex, element) {
      isMultiple[formIndex] = $(this).data('multiple') == '1';

      if (isMultiple[formIndex]) {
        $(this).find('[data-file-list]').sortable({
          update: function() {
            self.setFileOrder(element);
          }
        });
      }

      currentIndexes[formIndex] = $(this).find('[data-file-element]').length-1;
      $(element).find('.fileupload').fileupload({
        dataType: 'json',

        done: function (event, data) {
          var list = $(element).find('.list');
          var inputName = list.attr('data-name');
          if (!isMultiple[formIndex]) {
            $(element).find('[data-file-list]').empty();
            if (data.result.files.length > 0) {
              var html = templating.render($(element).find('script[data-id=files-template]'), {
                file_id: data.result.files[0].id,
                full_name: inputName,
                file_index: 0
              });
              $(element).find('[data-file-list]').append(html);
            }
          } else {
            $.each(data.result.files, function (index, file) {
              var html = templating.render($(element).find('script[data-id=files-template]'), {
                file_id: file.id,
                full_name: inputName,
                file_index: ++currentIndexes[formIndex]
              });
              $(element).find('[data-file-list]').append(html);
            });
            self.setFileOrder(element);
          }
          $(element).find('.progress .bar').css('width', '0%');
          $(element).find('.dropzone').removeClass('empty');
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

      $(element).find('.imgdelete').bind('click', function(event)
      {
        console.log('Delete');
        var imageContainer = $(event.target).parents('.imgContainer');
        if(imageContainer != null) {
          var dropZone = imageContainer.parents('.dropzone');
          imageContainer.remove();
          if (dropZone.find('[data-file-element]').length == 0) {
            dropZone.addClass('empty');
          }
        }
      });
    });
    self.start();
  };

  this.setFileOrder = function(form) {
    $(form).find('[data-file-element-order]').each(function(index) {
      $(this).val(index);
    });
  };

  this.start = function() {
    $(document).bind('dragover', function (e)
    {
      $('.dropzone').css('background-color', '#49a4e5');
    });

    $(document).bind('dragleave drop', function (e)
    {
      $('.dropzone').css('background-color', '');
    });
  };

  this.init = function() {
    $(document).on('formOpenAfter', function(event, form) {
      self.initUploadForm(form);
    });
  };

  this.init();
}
var uploadForm = new UploadForm(Routing);






