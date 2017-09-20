define(["require", "exports", "jquery", "blueimp-file-upload", "jquery-ui"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Media = /** @class */ (function () {
        function Media(mediaType) {
            this.$mediaType = $(mediaType);
            this.config = this.$mediaType.data('media-type');
            this.init();
        }
        Media.prototype.showDropZone = function () {
            this.row.showDropZone();
        };
        Media.prototype.hideDropZone = function () {
            this.row.hideDropZone();
        };
        Media.prototype.init = function () {
            var element = this.$mediaType.find('[data-media-row]').get(0);
            this.row = new MediaRow(element, this.config);
            this.initFileUpload();
            var self = this;
            var data = this.$mediaType.data('media-data');
            $.each(data, function (index, meta) {
                var item = self.row.createItem(meta);
                item.updateThumb();
            });
            self.row.setOrder();
        };
        Media.prototype.initFileUpload = function () {
            var self = this;
            this.$mediaType.find('[data-file-upload]').fileupload({
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
                dropZone: this.$mediaType.find('[data-media-drop-zone]')
            });
        };
        Media.prototype.setProgress = function (value) {
            this.$mediaType.find('[data-media-progress-bar]').css('width', value + '%');
        };
        Media.apply = function (form) {
            $(form).find('[data-media-type]').each(function () {
                Media.mediaTypes.push(new Media(this));
            });
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
        };
        Media.media = function (mediaType) {
            for (var _i = 0, _a = Media.mediaTypes; _i < _a.length; _i++) {
                var media = _a[_i];
                if (media.$mediaType.get(0) === mediaType) {
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
        return Media;
    }());
    exports.Media = Media;
    var MediaRow = /** @class */ (function () {
        function MediaRow(element, config) {
            this.config = config;
            this.$element = $(element);
            this.items = [];
            this.initSortable();
        }
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
            var html = $.parseHTML(template)[0];
            var item = new MediaItem(html, meta, this);
            this.items.push(item);
            this.$element.append(html);
            return item;
        };
        MediaRow.prototype.setOrder = function () {
        };
        MediaRow.prototype.showError = function () {
        };
        MediaRow.prototype.openEdit = function (item) {
            if (this.openEditItem) {
                this.closeEdit();
            }
            item.active();
            var editBox = "<li data-media-edit-container class='media-edit'></li>";
            var html = $.parseHTML(editBox)[0];
            var $html = $(html);
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
                $edit.remove();
                this.openEditItem = null;
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
                var self_1 = this;
                this.$element.sortable({
                    delay: 150,
                    update: function () {
                        self_1.$element.children().each(function (index, child) {
                            for (var _i = 0, _a = self_1.items; _i < _a.length; _i++) {
                                var item = _a[_i];
                                if (item.getElement() === child) {
                                    item.setOrder(index);
                                }
                            }
                        });
                    },
                    items: '> li[data-media-item]'
                });
            }
        };
        return MediaRow;
    }());
    var MediaItemMeta = /** @class */ (function () {
        function MediaItemMeta() {
        }
        return MediaItemMeta;
    }());
    var MediaItem = /** @class */ (function () {
        function MediaItem(element, meta, row) {
            this.$element = $(element);
            this.meta = meta;
            this.row = row;
            this.addHandler();
        }
        MediaItem.prototype.addHandler = function () {
            var self = this;
            this.$element.click(function () {
                self.openEdit();
            });
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
            var url = '/file/format/{id}/{format}/{shortMd5Checksum}/{filename}?v={random}';
            url = url.replace('{id}', this.meta.id.toString());
            url = url.replace('{format}', 'enhavoPreviewThumb');
            url = url.replace('{shortMd5Checksum}', this.meta.md5Checksum.substring(0, 6));
            url = url.replace('{filename}', this.meta.filename);
            url = url.replace('{random}', Math.random().toString());
            return url;
        };
        return MediaItem;
    }());
    exports.default = Media;
});
