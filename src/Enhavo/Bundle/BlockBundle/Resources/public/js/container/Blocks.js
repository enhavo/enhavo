var ThreePictureBlock = /** @class */ (function () {
    function ThreePictureBlock() {
        this.setTitleHeightOfThreePictureBlock();
    }
    ThreePictureBlock.prototype.setTitleHeightOfThreePictureBlock = function () {
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
    return ThreePictureBlock;
}());
