/**
 * Created by gseidel on 30/08/14.
 */


function UploadForm(routing)
{
  var self = this;

  this.initUploadForm = function(form) {
    var currentIndexes = [];
    var isMultiple = [];
    var selected = [];
    $(form).find('.uploadForm').each(function(formIndex, uploadForm) {
      isMultiple[formIndex] = $(this).data('multiple') == '1';

      if (isMultiple[formIndex]) {
        $(this).find('[data-file-list]').sortable({
          delay: 150,
          update: function() {
            self.setFileOrder(uploadForm);
          }
        });
      }

      currentIndexes[formIndex] = $(this).find('[data-file-element]').length-1;
      $(uploadForm).find('.fileupload').fileupload({
        dataType: 'json',

        done: function (event, data) {
          var list = $(uploadForm).find('.list');
          var inputName = list.attr('data-name');
          if (!isMultiple[formIndex]) {
            $(uploadForm).find('[data-file-list]').empty();
            if (data.result.files.length > 0) {
              var html = templating.render($(uploadForm).find('script[data-id=files-template]'), {
                file_id: data.result.files[0].id,
                full_name: inputName,
                file_index: 0,
                file_name: data.result.files[0].filename,
                file_slug: data.result.files[0].slug
              });
              $(uploadForm).find('[data-file-list]').append(html);
            }
          } else {
            $.each(data.result.files, function (index, file) {
              var html = templating.render($(uploadForm).find('script[data-id=files-template]'), {
                file_id: file.id,
                full_name: inputName,
                file_index: ++currentIndexes[formIndex],
                file_name: file.filename,
                file_slug: file.slug
              });
              $(uploadForm).find('[data-file-list]').append(html);
            });
          }
          self.setFileOrder(uploadForm);
          $(uploadForm).find('.progress .bar').css('width', '0%');
          $(uploadForm).find('.dropzone').removeClass('empty');
        },

        add: function (event, data) {
          data.submit();
        },

        progressall: function (event, data) {
          var progress = parseInt(data.loaded / data.total * 100, 10);
          $(uploadForm).find('.progress .bar').css('width', progress + '%');
        },

        dropZone: $(uploadForm).find('.dropzone')
      });

      $(uploadForm).find('.upload-button').click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        $(uploadForm).find('.fileupload').trigger('click');
      });

      $(uploadForm).on('click', '.imgdelete', function(event)
      {
        var imageContainer = $(event.target).parents('.imgContainer');
        if(imageContainer != null) {
          if (imageContainer.hasClass('selected')) {
            $(uploadForm).find('.fileupload-fields').addClass('hidden').addClass('disabled');
          }
          var dropZone = imageContainer.parents('.dropzone');
          imageContainer.remove();
          if (dropZone.find('[data-file-element]').length == 0) {
            dropZone.addClass('empty');
          }
          event.stopPropagation();
        }
      });

      $(uploadForm).on('mouseenter', '.imgContainer', function(event) {
        var fileUploadFields = $(uploadForm).find('.fileupload-fields');
        $(uploadForm).find('.imgContainer').removeClass('selected');
        $(this).addClass('selected');
        if (typeof selected[formIndex] != 'undefined') {
          if (selected[formIndex].data('id') == $(this).data('id')) {
            fileUploadFields.removeClass('disabled');
            fileUploadFields.find('input').prop('disabled', false);
            self.loadFields(uploadForm, selected[formIndex]);
          } else {
            fileUploadFields.addClass('disabled');
            fileUploadFields.find('input').prop('disabled', true);
            self.loadFields(uploadForm, $(this));
          }
        } else {
          fileUploadFields.addClass('disabled');
          fileUploadFields.find('input').prop('disabled', true);
          self.loadFields(uploadForm, $(this));
        }
      });
      $(uploadForm).on('mouseleave', '.imgContainer', function(event) {
        var fileUploadFields = $(uploadForm).find('.fileupload-fields');
        $(uploadForm).find('.imgContainer').removeClass('selected');

        if (typeof selected[formIndex] != 'undefined') {
          selected[formIndex].addClass('selected');
          fileUploadFields.removeClass('disabled');
          fileUploadFields.find('input').prop('disabled', false);
          self.loadFields(uploadForm, selected[formIndex]);
        } else {
          fileUploadFields.addClass('disabled');
          fileUploadFields.find('input').each(function() {
            $(this).prop('disabled', true);
            $(this).val('');
         });
        }
      });
      $(uploadForm).on('click', '.imgContainer', function(event) {
        selected[formIndex] = $(this);
        selected[formIndex].addClass('selected');
        var fileUploadFields = $(uploadForm).find('.fileupload-fields');
        fileUploadFields.removeClass('hidden');
        fileUploadFields.removeClass('disabled');
        fileUploadFields.find('input').prop('disabled', false);
        event.stopPropagation();
      }).on('click', '.fileupload-fields', function(event) {
        event.stopPropagation();
      });
      $(form).on('click', function() {
        $(uploadForm).find('.fileupload-fields').addClass('hidden').addClass('disabled');
        $(uploadForm).find('.imgContainer.selected').removeClass('selected');
      });
      $(uploadForm).on('input', '.fileupload-field-input', function(event) {
        if (typeof selected[formIndex] != 'undefined') {
          selected[formIndex].find('[data-field-name="' + $(this).data('field-name') + '"]').val($(this).val());
          if ($(this).data('field-name') == "filename") {
            var slug = self.slugifyFileName($(this).val());
            selected[formIndex].find('[data-field-name="slug"]').val(slug);
            $(uploadForm).find('#fileupload-field-slug').html(slug);
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

  this.loadFields = function(form, source) {
    var fileUploadFields = $(form).find('.fileupload-fields');
    var slugField = $(form).find('#fileupload-field-slug');
    fileUploadFields.find('input').each(function() {
      $(this).val(source.find('[data-field-name="' + $(this).data('field-name') + '"]').val());
    });
    var slug = source.find('[data-field-name="' + slugField.data('field-name') + '"]').val();
    if (slug == "") {
      slug = self.slugifyFileName(source.find('[data-field-name="filename"]').val());
      source.find('[data-field-name="slug"]').val(slug);
    }
    slugField.html(slug);
  };

  this.slugifyFileName = function(text) {
    return text.toLowerCase()
      .replace(/\s+/g, '-')           // Replace spaces with -
      .replace(/[^\w\-\.]+/g, '')     // Remove all non-word chars
      .replace(/\-\-+/g, '-')         // Replace multiple - with single -
      .replace(/^-+/, '')             // Trim - from start of text
      .replace(/-+$/, '');            // Trim - from end of text
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






