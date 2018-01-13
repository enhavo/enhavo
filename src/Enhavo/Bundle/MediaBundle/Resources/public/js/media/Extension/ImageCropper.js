define(["require", "exports", "jquery", "cropper"], function (require, exports, $) {
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
            this.loaded = false;
            this.item = item;
            this.$itemElement = $(this.item.getElement());
            this.$imageOverlay = this.$itemElement.find('[data-image-cropping-overlay]');
            this.$cropper = this.$imageOverlay.find('[data-image-crop-canvas]');
            this.init();
        }
        ImageCropperItem.prototype.init = function () {
            var self = this;
            this.$itemElement.find('[data-image-cropping-tool]').click(function () {
                self.loadCropper();
            });
        };
        ImageCropperItem.prototype.loadCropper = function () {
            this.loadImage();
            this.$cropper.cropper();
            var self = this;
            this.$cropper.on('built.cropper', function () {
                // Finished loading
                self.$imageOverlay.find('[data-image-crop-canvas-wrapper]').removeClass('loading');
            });
            this.showOverlay();
        };
        ImageCropperItem.prototype.loadImage = function () {
            if (!this.loaded) {
                this.$cropper.attr('src', this.item.getFileUrl());
            }
            this.loaded = true;
        };
        ImageCropperItem.prototype.showOverlay = function () {
            this.$imageOverlay.show();
            var self = this;
            this.$imageOverlay.find('[data-image-crop-cancel]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                self.$imageOverlay.hide();
            });
            this.$imageOverlay.find('[data-image-crop-submit]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                self.sendImage(function () {
                    self.$imageOverlay.hide();
                });
            });
            this.$imageOverlay.find('[data-image-cropper-action]').click(function (event) {
                event.stopPropagation();
                event.preventDefault();
                var action = $(this).data("image-cropper-action");
                switch (action) {
                    case "move-mode":
                        $(this).addClass('selected');
                        self.$imageOverlay.find('[data-image-cropper-action="cropframe-mode"]').removeClass('selected');
                        self.$cropper.cropper('setDragMode', 'move');
                        break;
                    case "cropframe-mode":
                        $(this).addClass('selected');
                        self.$imageOverlay.find('[data-image-cropper-action="move-mode"]').removeClass('selected');
                        self.$cropper.cropper('setDragMode', 'crop');
                        break;
                    case "zoom-in":
                        self.$cropper.cropper('zoom', '0.1');
                        break;
                    case "zoom-out":
                        self.$cropper.cropper('zoom', '-0.1');
                        break;
                    case "reset":
                        self.$cropper.cropper('reset');
                        break;
                }
            });
        };
        ImageCropperItem.prototype.sendImage = function (callback) {
            var canvasData = this.$cropper.cropper('getCropBoxData');
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
        ImageCropperItem.dataURItoBlob = function (dataURI) {
            // convert base64/URLEncoded data component to raw binary data held in a string
            var byteString;
            if (dataURI.split(',')[0].indexOf('base64') >= 0)
                byteString = atob(dataURI.split(',')[1]);
            else
                byteString = decodeURI(dataURI.split(',')[1]);
            // separate out the mime component
            var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
            // write the bytes of the string to a typed array
            var ia = new Uint8Array(byteString.length);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ia], { type: mimeString });
        };
        ;
        return ImageCropperItem;
    }());
    exports.ImageCropperItem = ImageCropperItem;
    exports.imageCropper = new ImageCropper();
});
