define(["require", "exports", "jquery", "app/Router", "app/Admin", "blueimp-file-upload", "jquery-ui"], function (require, exports, $, router, admin) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var BlockInitializer = /** @class */ (function () {
        function BlockInitializer() {
        }
        BlockInitializer.init = function () {
            $('body').on('initBlock', function (event, data) {
                if (data.type == 'media_library') {
                    var block_1 = new Block(data.block);
                    $(document).on('formSaveAfter', function () {
                        block_1.refresh();
                    });
                }
            });
        };
        return BlockInitializer;
    }());
    exports.BlockInitializer = BlockInitializer;
    var Block = /** @class */ (function () {
        function Block(element) {
            this.$element = $(element);
            this.$listElement = $('[data-file-list]', this.$element);
            this.stopLoading();
            this.initDropZone();
            this.initFileUpload();
            this.refresh();
        }
        Block.prototype.initDropZone = function () {
            var self = this;
            $(document).bind('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if ($(e.originalEvent.srcElement).parents('[data-file-list]').length == 0) {
                    self.showDropZone();
                }
            });
            $(document).bind('dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                self.hideDropZone();
            });
        };
        Block.prototype.initFileUpload = function () {
            var self = this;
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
                    var progress = data.loaded / data.total * 100;
                    if (progress >= 100) {
                        self.setProgress(0);
                    }
                    else {
                        self.setProgress(progress);
                    }
                },
                dropZone: this.$element
            });
        };
        Block.prototype.setProgress = function (percent) {
        };
        Block.prototype.showUploadError = function () {
        };
        Block.prototype.setData = function (data) {
            var self = this;
            var html = $.parseHTML(data)[0];
            $(html).find('[data-item]').each(function () {
                new Item(this);
            });
            $(html).find('[data-page]').click(function () {
                var page = $(this).data('page');
                self.refresh(page);
            });
            this.$listElement.html(html);
        };
        Block.prototype.refresh = function (page) {
            if (page === void 0) { page = 1; }
            this.startLoading();
            var self = this;
            var url = router.generate('enhavo_media_library_list', { page: page });
            $.ajax({
                type: 'post',
                url: url,
                success: function (data) {
                    self.setData(data);
                    self.stopLoading();
                }
            });
        };
        Block.prototype.stopLoading = function () {
            this.$element.removeClass('loading');
        };
        Block.prototype.startLoading = function () {
            this.$element.addClass('loading');
        };
        Block.prototype.showDropZone = function () {
            this.$element.addClass('drop-zone');
        };
        Block.prototype.hideDropZone = function () {
            this.$element.removeClass('drop-zone');
        };
        return Block;
    }());
    exports.Block = Block;
    var Item = /** @class */ (function () {
        function Item(element) {
            this.$element = $(element);
            this.id = this.$element.data('item');
            this.initEvents();
        }
        Item.prototype.initEvents = function () {
            var self = this;
            this.$element.on('click', function () {
                self.open();
            });
        };
        Item.prototype.open = function () {
            var url = router.generate('enhavo_media_library_update', { id: this.id });
            admin.ajaxOverlay(url, {});
        };
        return Item;
    }());
    BlockInitializer.init();
});
