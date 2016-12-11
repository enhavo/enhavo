var SimpleFileUploader = function()
{
    var self = this;

    this.init = function () {
        self.initFileUpload();
        self.initDeleteButton();
    };

    this.initDeleteButton = function()
    {
        $(document).on('click', "[data-delete-item]", function(event) {
            event.preventDefault();
            $(this).parents('[data-files]').remove();
        });
    };

    this.initFileUpload = function() {
        $('[data-simple-file-uploader] [data-file-upload]').each(function (index, element) {

            //search marked dom nodes
            var $statusBar = $(element).parents('[data-simple-file-uploader]').find('[data-file-status-bar]');
            var $fileUpload = $(element);
            var $fileList =  $(element).parents('[data-simple-file-uploader]').find('[data-file-list]');

            $fileUpload.fileupload({
                dataType: 'json',
                progress: function (e, data) {
                    if($statusBar.length) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $statusBar.fadeIn();
                        $statusBar.css(
                          'width',
                          progress + '%'
                        );
                        $statusBar.text(progress + '%');
                    }
                },
                done: function (e, data) {

                    var prototype = $fileUpload.data('prototype');
                    var index = $fileUpload.data('index');
                    var multiple = $fileUpload.data('multiple');
                    var result = data.result[0];

                    prototype = self.replace(prototype, '__index__', index);
                    prototype = self.replace(prototype, '__id__', result.id);
                    prototype = self.replace(prototype, '__filename__', result.filename);
                    prototype = self.replace(prototype, '__slug__', result.slug);

                    $fileUpload.data('index', index+1);
                    prototype = $.parseHTML(prototype);

                    if(multiple) {
                        $fileList.append(prototype);
                    } else {
                        $fileList.html(prototype);
                    }
                }
            });
        });
    };

    this.replace = function(input, key, replace) {
        var regExp = new RegExp(key, 'ig');
        input = input.replace(regExp, replace);
        return input;
    };

    this.init();
};

var simpleFileUploader;
$(function() {
    simpleFileUploader = new SimpleFileUploader();
});