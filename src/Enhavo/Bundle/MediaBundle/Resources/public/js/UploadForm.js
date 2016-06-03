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

      $(uploadForm).find('[data-file-element]').each(function() {
        self.setThumbOrIcon($(this), uploadForm);
      });

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
                file_slug: data.result.files[0].slug,
                file_mime_type: data.result.files[0].mimeType
              });
              $(uploadForm).find('[data-file-list]').append(html);
              self.setThumbOrIcon($('[data-id=' + data.result.files[0].id + ']'), uploadForm);
            }
          } else {
            $.each(data.result.files, function (index, file) {
              var html = templating.render($(uploadForm).find('script[data-id=files-template]'), {
                file_id: file.id,
                full_name: inputName,
                file_index: ++currentIndexes[formIndex],
                file_name: file.filename,
                file_slug: file.slug,
                file_mime_type: data.result.files[0].mimeType
              });
              $(uploadForm).find('[data-file-list]').append(html);
              self.setThumbOrIcon($('[data-id=' + file.id + ']'), uploadForm);
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
        if (typeof selected[formIndex] != 'undefined' && selected[formIndex] != null) {
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

        if (typeof selected[formIndex] != 'undefined' && selected[formIndex] != null) {
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
        selected[formIndex] = null;
      });
      $(uploadForm).on('input', '.fileupload-field-input', function(event) {
        if (typeof selected[formIndex] != 'undefined' && selected[formIndex] != null) {
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
    $(form).find('[data-download-button]').attr("href", source.data('download-link'));
  };

  this.setThumbOrIcon = function($element, uploadForm) {
    var mimeType = $element.find('[data-mime-type]').data('mime-type');
    if (mimeType.startsWith('image')) {
      var thumb = templating.render($(uploadForm).find('script[data-id=thumb-template]'), {
        file_id: $element.data('id')
      });
      $element.find('[data-thumb-container]').html(thumb);
      $element.find('[data-file-icon]').addClass('icon-image');
    } else if (mimeType.startsWith('audio')) {
      $element.find('[data-file-icon]').addClass('icon-file-audio');
    } else if (mimeType.startsWith('video')) {
      $element.find('[data-file-icon]').addClass('icon-file-video');
    } else {
      switch(mimeType) {
        case 'application/postscript':
        case 'application/rtf':
          $element.find('[data-file-icon]').addClass('icon-doc-text');
          break;
        case 'application/pdf':
          $element.find('[data-file-icon]').addClass('icon-file-pdf');
          break;
        case 'application/msword':
        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document ':
          $element.find('[data-file-icon]').addClass('icon-file-word');
          break;
        case 'application/msexcel':
        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
          $element.find('[data-file-icon]').addClass('icon-file-excel');
          break;
        case 'application/mspowerpoint':
          $element.find('[data-file-icon]').addClass('icon-file-powerpoint');
          break;
        case 'application/gzip':
        case 'application/x-compress':
        case 'application/x-compressed':
        case 'application/x-zip-compressed':
        case 'application/x-gtar':
        case 'application/x-shar':
        case 'application/x-tar':
        case 'application/x-ustar':
        case 'application/zip':
          $element.find('[data-file-icon]').addClass('icon-file-archive');
          break;
        case 'text/css':
        case 'text/html':
        case 'text/javascript':
        case 'text/xml':
        case 'text/x-php':
        case 'application/json':
        case 'application/xhtml+xml':
        case 'application/xml':
        case 'application/x-httpd-php':
        case 'application/x-javascript':
        case 'application/x-latex':
        case 'application/x-php':
          $element.find('[data-file-icon]').addClass('icon-file-code');
          break;
        default:
          if (mimeType.startsWith('text/x-script') || mimeType.startsWith('application/x-script')) {
            $element.find('[data-file-icon]').addClass('icon-file-code');
          } else if (mimeType.startsWith('text')) {
            $element.find('[data-file-icon]').addClass('icon-doc-text');
          } else {
            $element.find('[data-file-icon]').addClass('icon-doc');
          }
      }
    }
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






