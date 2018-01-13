import { MediaItem } from 'media/Media';
import * as $ from 'jquery'

export class ImageCropper
{

    constructor()
    {
        ImageCropper.initFormListener();
    }

    private static apply(item: MediaItem)
    {
        new ImageCropperItem(item);
    }

    private static initFormListener(): void
    {
        $(document).on('mediaAddItem', function (event, item) {
            ImageCropper.apply(item);
        });
    }
}

export class ImageCropperItem
{
    private item: MediaItem;

    constructor(item: MediaItem)
    {
        this.item = item;
        this.init();
    }

    private init()
    {
        console.log($(this.item.getElement()).find('[data-image-cropper]').html());
    }
}

export let imageCropper = new ImageCropper();