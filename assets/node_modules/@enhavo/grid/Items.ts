class ThreePictureItem
{
    constructor()
    {
        this.setTitleHeightOfThreePictureItem();
    }

    protected setTitleHeightOfThreePictureItem()
    {
        let maxHeight = -1;

        let imageTitles = $('[data-image-title]');

        imageTitles.each(function () {
            maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
        });

        imageTitles.each(function () {
            $(this).height(maxHeight);
        });
    };
}