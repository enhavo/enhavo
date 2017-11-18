var ThreePictureItem = /** @class */ (function () {
    function ThreePictureItem() {
        this.setTitleHeightOfThreePictureItem();
    }
    ThreePictureItem.prototype.setTitleHeightOfThreePictureItem = function () {
        var maxHeight = -1;
        var imageTitles = $('[data-image-title]');
        imageTitles.each(function () {
            maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
        });
        imageTitles.each(function () {
            $(this).height(maxHeight);
        });
    };
    ;
    return ThreePictureItem;
}());
