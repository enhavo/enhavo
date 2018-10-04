import * as $ from 'jquery'
import 'blueimp-file-upload'
import 'jquery-ui'
import * as router from 'app/Router'
import * as admin from 'app/Admin'

export class BlockInitializer
{
    static init()
    {
        $('body').on('initBlock', function(event, data) {
            if(data.type == 'media_library') {
                let block = new Block(data.block);
                $(document).on('formSaveAfter', function () {
                    block.refresh();
                });
            }
        });
    }
}

export class Block
{
    private $element: JQuery;

    constructor(element:HTMLElement)
    {
        this.$element = $(element);
        this.stopLoading();
        this.initDropZone();
        this.initFileUpload();
        this.refresh();
    }

    private initDropZone()
    {
        let self = this;
        $(document).bind('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            self.showDropZone()
        });

        $(document).bind('dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            self.hideDropZone()
        });
    }

    private initFileUpload()
    {
        let self = this;

        this.$element.fileupload({
            dataType: 'json',
            paramName: 'files',
            done: function (event, data) {
                $.each(data.result, function (index, meta) {
                    self.refresh();
                });
            },
            fail: function (event, data) {
                self.showUploadError();
                self.stopLoading();
            },
            add: function (event, data) {
                data.url = router.generate('enhavo_media_library_upload', {});
                data.submit();
                self.startLoading();
            },
            progressall: function (event, data) {
                let progress = data.loaded / data.total * 100;
                if (progress >= 100) {
                    self.setProgress(0);
                } else {
                    self.setProgress(progress);
                }
            },
            dropZone: this.$element
        });
    }

    private setProgress(percent: number)
    {

    }

    private showUploadError()
    {

    }

    private setData(data: string)
    {
        let self = this;
        let html = $.parseHTML(data)[0];
        $(html).find('[data-item]').each(function () {
            new Item(this);
        });
        $(html).find('[data-page]').click(function () {
            let page = $(this).data('page');
            self.refresh(page);
        });
        this.$element.html(html);
    }

    refresh(page:number = 1)
    {
        this.startLoading();
        let self = this;
        let url = router.generate('enhavo_media_library_list', {page: page});
        $.ajax({
            type: 'post',
            url: url,
            success: function (data) {
                self.setData(data);
                self.stopLoading();
            }
        });
    }

    stopLoading() : void
    {
        this.$element.removeClass('loading');
    }

    startLoading() : void
    {
        this.$element.addClass('loading');
    }

    showDropZone() : void
    {
        this.$element.addClass('drop-zone');
    }

    hideDropZone() : void
    {
        this.$element.removeClass('drop-zone');
    }
}

class Item
{
    private $element: JQuery;

    private id: number;

    constructor(element:HTMLElement)
    {
        this.$element = $(element);
        this.id = this.$element.data('item');
        this.initEvents();
    }

    private initEvents()
    {
        let self = this;
        this.$element.on('click', function () {
            self.open();
        });
    }

    open()
    {
        let url = router.generate('enhavo_media_library_update', { id: this.id });
        admin.ajaxOverlay(url, {});
    }
}

BlockInitializer.init();