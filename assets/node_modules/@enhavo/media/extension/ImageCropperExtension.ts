import MediaItem from '@enhavo/media/type/MediaItem';
import ImageCropperConfiguration from "@enhavo/media/extension/ImageCropperConfiguration";
import $ from 'jquery'

export default class ImageCropperExtension
{
    private item: MediaItem;

    private $itemElement: JQuery;

    constructor(item: MediaItem, config: ImageCropperConfiguration)
    {
        this.item = item;
        this.$itemElement = $(this.item.getElement());
        this.$itemElement.find('[data-image-cropping-tool]').click((event: Event) => {
            let format = $(event.target).closest('[data-image-cropping-tool]').data('image-cropping-tool');
            config.openCropper(this.item, format);
        });
    }
}
