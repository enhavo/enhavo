define(["require", "exports", "jquery"], function (require, exports, $) {
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
            this.init();
        }
        ImageCropperItem.prototype.init = function () {
            console.log($(this.item.getElement()).find('[data-image-cropper]').html());
        };
        return ImageCropperItem;
    }());
    exports.ImageCropperItem = ImageCropperItem;
    exports.imageCropper = new ImageCropper();
});
