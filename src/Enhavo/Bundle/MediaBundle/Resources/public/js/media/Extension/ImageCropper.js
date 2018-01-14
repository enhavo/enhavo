define(["require", "exports", "jquery", "cropperjs"], function (require, exports, $, Cropper) {
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
            this.item = item;
            this.$element = $(element);
        }
        ImageCropperOverlay.prototype.open = function (format) {
            var image = this.createImage();
            this.showOverlay();
            var self = this;
            this.cropper = new Cropper(image, {
                ready: function () {
                    self.finishLoading();
                    this.cropper.move(1, -1);
                }
            });
        };
        ImageCropperOverlay.prototype.finishLoading = function () {
            this.$element.find('[data-image-crop-canvas-wrapper]').removeClass('loading');
        };
        ImageCropperOverlay.prototype.createImage = function () {
            var image = new Image();
            image.src = this.item.getFileUrl();
            this.$element.find('[data-image-crop-canvas-wrapper]').html();
            this.$element.find('[data-image-crop-canvas-wrapper]').append(image);
            return image;
        };
        ImageCropperOverlay.prototype.showOverlay = function () {
            this.$element.show();
            var self = this;
            this.$element.find('[data-image-crop-cancel]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                self.$element.hide();
            });
            this.$element.find('[data-image-crop-submit]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                self.sendImage(function () {
                    self.$element.hide();
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
        ImageCropperOverlay.prototype.sendImage = function (callback) {
            var canvasData = this.cropper.getCropBoxData();
            console.log(canvasData);
            var url = '/media/image/cropper';
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    width: canvasData.width,
                    height: canvasData.height,
                    left: canvasData.left,
                    top: canvasData.top,
                },
                processData: false,
                contentType: false,
                success: function () {
                    if (callback) {
                        callback();
                    }
                },
            });
        };
        return ImageCropperOverlay;
    }());
    exports.ImageCropperOverlay = ImageCropperOverlay;
    exports.imageCropper = new ImageCropper();
});
