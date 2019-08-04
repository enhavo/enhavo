import MediaItem from '@enhavo/media/Type/MediaItem';
import ImageCropperConfiguration from "@enhavo/media/Extension/ImageCropperConfiguration";
import * as $ from 'jquery'

export default class ImageCropperExtension
{
    private item: MediaItem;

    private $itemElement: JQuery;

    constructor(item: MediaItem, config: ImageCropperConfiguration)
    {
        this.item = item;
        this.$itemElement = $(this.item.getElement());
        this.$itemElement.find('[data-image-cropping-tool]').click((event: Event) => {
            let format = $(event.target).data('image-cropping-tool');
            config.openCropper(this.item, format);
        });
    }
}
