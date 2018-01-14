define(["require", "exports", "jquery", "blueimp-file-upload", "jquery-ui"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Media = /** @class */ (function () {
        function Media(element) {
            this.$element = $(element);
            this.config = this.$element.data('media-type');
            this.init();
            this.dispatchInitEvent();
        }
        Media.prototype.dispatchInitEvent = function () {
            $(document).trigger('mediaInit', [this]);
        };
        Media.prototype.showDropZone = function () {
            if (this.config.upload) {
                this.row.showDropZone();
            }
        };
        Media.prototype.hideDropZone = function () {
            if (this.config.upload) {
                this.row.hideDropZone();
            }
        };
        Media.prototype.getConfig = function () {
            return this.config;
        };
        Media.prototype.init = function () {
            var element = this.$element.find('[data-media-row]').get(0);
            this.row = new MediaRow(element, this);
            this.initFileUpload();
            this.initUploadButton();
        };
        Media.prototype.initFileUpload = function () {
            var self = this;
            if (this.config.upload) {
                this.$element.find('[data-file-upload]').fileupload({
                    dataType: 'json',
                    done: function (event, data) {
                        $.each(data.result, function (index, meta) {
                            var item = self.row.createItem(meta);
                            item.updateThumb();
                            self.row.setOrder();
                        });
                    },
                    fail: function (event, data) {
                        self.row.showError();
                    },
                    add: function (event, data) {
                        if (!self.config.multiple) {
                            self.row.clearItems();
                        }
                        self.row.closeEdit();
                        data.submit();
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
                    dropZone: this.$element.find('[data-media-drop-zone]')
                });
            }
        };
        Media.prototype.initUploadButton = function () {
            var self = this;
            this.$element.find('[data-file-upload-button]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                self.$element.find('[data-file-upload]').trigger('click');
            });
        };
        Media.prototype.setProgress = function (value) {
            this.$element.find('[data-media-progress-bar]').css('width', value + '%');
        };
        Media.prototype.getRow = function () {
            return this.row;
        };
        Media.prototype.getElement = function () {
            return this.$element.get(0);
        };
        Media.apply = function (form) {
            $(form).find('[data-media-type]').each(function () {
                Media.mediaTypes.push(new Media(this));
            });
            if (!Media.isDragAndDropBind) {
                $(document).bind('dragover', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    Media.map(function (mediaType) {
                        mediaType.showDropZone();
                    });
                });
                $(document).bind('dragleave drop', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    Media.map(function (mediaType) {
                        mediaType.hideDropZone();
                    });
                });
                Media.isDragAndDropBind = true;
            }
        };
        Media.media = function (mediaType) {
            for (var _i = 0, _a = Media.mediaTypes; _i < _a.length; _i++) {
                var media = _a[_i];
                if (media.$element.get(0) === mediaType) {
                    return media;
                }
            }
            return null;
        };
        Media.map = function (callback) {
            for (var _i = 0, _a = Media.mediaTypes; _i < _a.length; _i++) {
                var media = _a[_i];
                callback(media);
            }
        };
        Media.mediaTypes = [];
        Media.isDragAndDropBind = false;
        return Media;
    }());
    exports.Media = Media;
    var MediaRow = /** @class */ (function () {
        function MediaRow(element, media) {
            this.index = 0;
            this.config = media.getConfig();
            this.media = media;
            this.$element = $(element);
            this.items = [];
            this.initSortable();
            var self = this;
            this.$element.children('[data-media-item]').each(function (index, element) {
                var $element = $(element);
                var id = $element.find('[data-media-item-id]').data('media-item-id');
                var meta = $element.data('media-meta');
                $element.find('[data-media-item-id]').val(meta.id);
                var item = new MediaItem(element, meta, self);
                item.updateThumb();
                self.items.push(item);
                $(document).trigger('mediaAddItem', [item]);
                self.index++;
            });
            self.setOrder();
        }
        MediaRow.prototype.getItems = function () {
            return this.items;
        };
        MediaRow.prototype.getElement = function () {
            return this.$element.get(0);
        };
        MediaRow.prototype.getMedia = function () {
            return this.media;
        };
        MediaRow.prototype.updateThumbs = function () {
            for (var _i = 0, _a = this.items; _i < _a.length; _i++) {
                var item = _a[_i];
                item.updateThumb();
            }
        };
        MediaRow.prototype.showDropZone = function () {
            this.$element.css('background-color', '#49a4e5');
        };
        MediaRow.prototype.hideDropZone = function () {
            this.$element.css('background-color', '');
        };
        MediaRow.prototype.createItem = function (meta) {
            var template = this.$element.parent().find('[data-media-item-template]').text();
            template = template.replace(/__name__/g, this.index.toString());
            var html = $.parseHTML(template)[0];
            var item = new MediaItem(html, meta, this);
            item.setFilename(meta.filename);
            item.setId(meta.id);
            this.items.push(item);
            this.$element.append(html);
            $(document).trigger('mediaAddItem', [item]);
            this.index++;
            return item;
        };
        MediaRow.prototype.setOrder = function () {
            var self = this;
            this.$element.children().each(function (index, child) {
                for (var _i = 0, _a = self.items; _i < _a.length; _i++) {
                    var item = _a[_i];
                    if (item.getElement() === child) {
                        item.setOrder(index);
                    }
                }
            });
        };
        MediaRow.prototype.showError = function () {
            console.log('[Media][Error]: ' + this);
        };
        MediaRow.prototype.clearItems = function () {
            this.$element.children().remove();
        };
        MediaRow.prototype.openEdit = function (item) {
            if (this.openEditItem) {
                this.closeEdit();
            }
            item.active();
            var editBox = "<li data-media-edit-container class='media-edit'></li>";
            var html = $.parseHTML(editBox)[0];
            var $html = $(html);
            this.resizeEdit($html);
            $html.append(item.getEditElements());
            var afterElement = this.getListAfterElement(item);
            $(afterElement).after(html);
            this.openEditItem = item;
        };
        MediaRow.prototype.closeEdit = function () {
            if (this.openEditItem) {
                this.openEditItem.inactive();
                var $edit = this.$element.find('[data-media-edit-container]');
                var html = $edit.children().toArray();
                this.openEditItem.setEditElements(html);
                this.removeResizeHandler();
                $edit.remove();
                this.openEditItem = null;
            }
        };
        MediaRow.prototype.resizeEdit = function ($editElement) {
            if (!this.config.multiple) {
                var self_1 = this;
                this.resizeHandler = function () {
                    var width = self_1.$element.parent().innerWidth();
                    $editElement.css('width', width + 'px');
                };
                $(window).bind('resize', 'resize', this.resizeHandler);
                this.resizeHandler();
            }
        };
        MediaRow.prototype.removeResizeHandler = function () {
            if (!this.config.multiple) {
                $(window).unbind('resize', this.resizeHandler);
            }
        };
        MediaRow.prototype.getListAfterElement = function (item) {
            var position = 0;
            var width = null;
            var rowWidth = this.$element.innerWidth();
            var itemCount = this.$element.children('[data-media-item]').length;
            this.$element.children('[data-media-item]').each(function (index, element) {
                if (width === null) {
                    width = $(element).outerWidth();
                }
                if (item.getElement() === element) {
                    position = index;
                    return false;
                }
                width = $(element).outerWidth();
            });
            var itemPerRows = Math.floor(rowWidth / width);
            var afterPosition = Math.floor(position / itemPerRows) * itemPerRows + itemPerRows;
            if (afterPosition > itemCount) {
                afterPosition = itemCount;
            }
            afterPosition--;
            return this.$element.children('[data-media-item]').get(afterPosition);
        };
        MediaRow.prototype.initSortable = function () {
            if (this.config.multiple && this.config.sortable) {
                var self_2 = this;
                this.$element.sortable({
                    delay: 150,
                    update: function () {
                        self_2.setOrder();
                    },
                    items: '> li[data-media-item]',
                    start: function () {
                        self_2.closeEdit();
                    }
                });
            }
        };
        return MediaRow;
    }());
    exports.MediaRow = MediaRow;
    var MediaItemMeta = /** @class */ (function () {
        function MediaItemMeta() {
        }
        return MediaItemMeta;
    }());
    exports.MediaItemMeta = MediaItemMeta;
    var MediaItem = /** @class */ (function () {
        function MediaItem(element, meta, row) {
            this.$element = $(element);
            this.meta = meta;
            this.row = row;
            this.addHandler();
        }
        MediaItem.prototype.addHandler = function () {
            var self = this;
            if (this.row.getMedia().getConfig().edit) {
                this.$element.click(function () {
                    self.openEdit();
                });
            }
            this.$element.find('[data-media-item-delete]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                self.remove();
            });
        };
        MediaItem.prototype.inactive = function () {
            this.$element.removeClass('active');
        };
        MediaItem.prototype.active = function () {
            this.$element.addClass('active');
        };
        MediaItem.prototype.remove = function () {
            this.$element.remove();
            this.row.setOrder();
        };
        MediaItem.prototype.getElement = function () {
            return this.$element.get(0);
        };
        MediaItem.prototype.getEditElements = function () {
            return this.$element.find('[data-media-edit]').children().toArray();
        };
        MediaItem.prototype.setEditElements = function (element) {
            return this.$element.find('[data-media-edit]').append(element);
        };
        MediaItem.prototype.setOrder = function (order) {
            this.meta.order = order;
            this.$element.find('[data-position]').val(order);
        };
        MediaItem.prototype.setFilename = function (filename) {
            this.meta.filename = filename;
            this.$element.find('[data-media-item-filename]').val(filename);
        };
        MediaItem.prototype.setId = function (id) {
            this.meta.id = id;
            this.$element.find('[data-media-item-id]').val(id);
        };
        MediaItem.prototype.getId = function () {
            return this.meta.id;
        };
        MediaItem.prototype.openEdit = function () {
            this.row.openEdit(this);
        };
        MediaItem.prototype.closeEdit = function () {
            this.row.closeEdit();
        };
        MediaItem.prototype.getRow = function () {
            return this.row;
        };
        MediaItem.prototype.getMeta = function () {
            return this.meta;
        };
        MediaItem.prototype.updateThumb = function () {
            //reset
            this.$element.find('[data-media-thumb]').css('background-image', 'none');
            this.$element.find('[data-media-thumb-icon]').removeClass().addClass('icon');
            switch (this.meta.mimeType) {
                case 'image/png':
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/gif':
                    this.$element.find('[data-media-thumb]').css('background-image', 'url(' + this.getThumbUrl() + ')');
                    break;
                case 'video/mpeg':
                case 'video/quicktime':
                case 'video/vnd.vivo':
                case 'video/x-msvideo':
                case 'video/x-sgi-movie':
                    this.$element.find('[data-media-thumb-icon]').addClass('icon-file-video');
                    break;
                case 'audio/basic':
                case 'audio/echospeech':
                case 'audio/tsplayer':
                case 'audio/voxware':
                case 'audio/x-aiff':
                case 'audio/x-dspeeh':
                case 'audio/x-midi':
                case 'audio/x-mpeg':
                case 'audio/x-pn-realaudio':
                case 'audio/x-pn-realaudio-plugin':
                case 'audio/x-qt-stream:':
                case 'audio/x-wav':
                    this.$element.find('[data-media-thumb-icon]').addClass('icon-file-audio');
                    break;
                case 'application/postscript':
                case 'application/rtf':
                    this.$element.find('[data-media-thumb-icon]').addClass('icon-doc-text');
                    break;
                case 'application/pdf':
                    this.$element.find('[data-media-thumb-icon]').addClass('icon-file-pdf');
                    break;
                case 'application/msword':
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document ':
                    this.$element.find('[data-media-thumb-icon]').addClass('icon-file-word');
                    break;
                case 'application/msexcel':
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    this.$element.find('[data-media-thumb-icon]').addClass('icon-file-excel');
                    break;
                case 'application/mspowerpoint':
                    this.$element.find('[data-media-thumb-icon]').addClass('icon-file-powerpoint');
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
                    this.$element.find('[data-media-thumb-icon]').addClass('icon-file-archive');
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
                    this.$element.find('[data-media-thumb-icon]').addClass('icon-file-code');
                    break;
                default:
                    this.$element.find('[data-file-icon]').addClass('icon-doc');
            }
        };
        MediaItem.prototype.getThumbUrl = function () {
            var url = ' /file/resolve/{token}/{format}?v={random}';
            url = url.replace('{token}', this.meta.token.toString());
            url = url.replace('{format}', 'enhavoPreviewThumb');
            url = url.replace('{random}', Math.random().toString());
            return url;
        };
        MediaItem.prototype.getFileUrl = function () {
            var url = ' /file/resolve/{token}?v={random}';
            url = url.replace('{token}', this.meta.token.toString());
            url = url.replace('{random}', Math.random().toString());
            return url;
        };
        return MediaItem;
    }());
    exports.MediaItem = MediaItem;
    exports.default = Media;
});
