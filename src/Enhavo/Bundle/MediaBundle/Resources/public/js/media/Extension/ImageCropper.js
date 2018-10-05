define(["require", "exports", "jquery", "cropperjs", "app/Router"], function (require, exports, $, Cropper, router) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var ImageCropper = /** @class */ (function () {
        function ImageCropper() {
            ImageCropper.initFormListener();
        }
        ImageCropper.apply = function (item) {
            new ImageCropperItem(item);
        };
        ImageCropper.initFormListener = function () {
            $(document).on('mediaAddItem', function (event, item) {
                ImageCropper.apply(item);
            });
        };
        return ImageCropper;
    }());
    exports.ImageCropper = ImageCropper;
    var ImageCropperItem = /** @class */ (function () {
        function ImageCropperItem(item) {
            this.item = item;
            this.$itemElement = $(this.item.getElement());
            var overlayElement = this.$itemElement.find('[data-image-cropping-overlay]').get(0);
            this.overlay = new ImageCropperOverlay(overlayElement, item);
            this.init();
        }
        ImageCropperItem.prototype.init = function () {
            var self = this;
            this.$itemElement.find('[data-image-cropping-tool]').click(function () {
                var format = $(this).data('image-cropping-tool');
                self.overlay.open(format);
            });
        };
        return ImageCropperItem;
    }());
    exports.ImageCropperItem = ImageCropperItem;
    var ImageCropperOverlay = /** @class */ (function () {
        function ImageCropperOverlay(element, item) {
            this.closeMutex = false;
            this.item = item;
            this.$element = $(element);
        }
        ImageCropperOverlay.prototype.open = function (format) {
            var self = this;
            self.format = format;
            this.showOverlay();
            this.getFormatData(function (formatData) {
                var image = self.createImage();
                self.cropper = new Cropper(image, {
                    ready: function () {
                        self.finishLoading();
                        var data = formatData.getData();
                        if (formatData.ratio) {
                            self.cropper.setAspectRatio(formatData.ratio);
                        }
                        if (data !== null) {
                            self.cropper.setData(data);
                        }
                    }
                });
            });
        };
        ImageCropperOverlay.prototype.close = function () {
            this.$element.hide();
            this.$element.find('[data-image-crop-canvas-wrapper] img').remove();
            this.cropper.destroy();
        };
        ImageCropperOverlay.prototype.finishLoading = function () {
            this.$element.find('[data-image-crop-canvas-wrapper]').removeClass('loading');
        };
        ImageCropperOverlay.prototype.createImage = function () {
            var image = new Image();
            image.src = this.item.getFileUrl();
            this.$element.find('[data-image-crop-canvas-wrapper]').append(image);
            return image;
        };
        ImageCropperOverlay.prototype.showOverlay = function () {
            this.$element.show();
            var self = this;
            this.$element.find('[data-image-crop-cancel]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                self.close();
            });
            this.$element.find('[data-image-crop-submit]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                if (self.closeMutex) {
                    return;
                }
                self.closeMutex = true;
                self.sendCropData(function () {
                    self.close();
                    self.closeMutex = false;
                });
            });
            this.$element.find('[data-image-cropper-action]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                var action = $(this).data("image-cropper-action");
                switch (action) {
                    case "move-mode":
                        $(this).addClass('selected');
                        self.$element.find('[data-image-cropper-action="cropframe-mode"]').removeClass('selected');
                        self.cropper.setDragMode('move');
                        break;
                    case "cropframe-mode":
                        $(this).addClass('selected');
                        self.$element.find('[data-image-cropper-action="move-mode"]').removeClass('selected');
                        self.cropper.setDragMode('crop');
                        break;
                    case "zoom-in":
                        self.cropper.zoom(0.1);
                        break;
                    case "zoom-out":
                        self.cropper.zoom(-0.1);
                        break;
                    case "reset":
                        self.cropper.reset();
                        break;
                }
            });
        };
        ImageCropperOverlay.prototype.sendCropData = function (callback) {
            var data = this.cropper.getData();
            var url = router.generate('enhavo_media_image_cropper_crop', {
                token: this.item.getMeta().token,
                format: this.format
            });
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    height: data.height,
                    width: data.width,
                    x: data.x,
                    y: data.y,
                },
                success: function () {
                    if (callback) {
                        callback();
                    }
                },
            });
        };
        ImageCropperOverlay.prototype.getFormatData = function (callback) {
            var url = router.generate('enhavo_media_image_cropper_data', {
                token: this.item.getMeta().token,
                format: this.format
            });
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    var formatData = new FormatData();
                    formatData.width = data.width;
                    formatData.height = data.height;
                    formatData.x = data.x;
                    formatData.y = data.y;
                    formatData.ratio = data.ratio;
                    callback(formatData);
                },
            });
        };
        return ImageCropperOverlay;
    }());
    exports.ImageCropperOverlay = ImageCropperOverlay;
    var FormatData = /** @class */ (function () {
        function FormatData() {
            this.x = null;
            this.y = null;
            this.width = null;
            this.height = null;
            this.rotate = 0;
            this.scaleX = 1;
            this.scaleY = 1;
            this.ratio = null;
        }
        FormatData.prototype.getData = function () {
            if (this.x !== null && this.y !== null && this.width !== null && this.height !== null) {
                return {
                    x: this.x,
                    y: this.y,
                    width: this.width,
                    height: this.height,
                    scaleX: this.scaleX,
                    scaleY: this.scaleY,
                    rotate: this.rotate,
                };
            }
            return null;
        };
        return FormatData;
    }());
    exports.imageCropper = new ImageCropper();
});
