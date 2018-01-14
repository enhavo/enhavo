import { MediaItem } from 'media/Media';
import * as $ from 'jquery'
import * as Cropper from 'cropperjs';

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

    private $itemElement: JQuery;

    private overlay: ImageCropperOverlay;

    constructor(item: MediaItem)
    {
        this.item = item;
        this.$itemElement = $(this.item.getElement());
        let overlayElement = this.$itemElement.find('[data-image-cropping-overlay]').get(0);
        this.overlay = new ImageCropperOverlay(overlayElement, item);
        this.init();
    }

    private init()
    {
        let self = this;
        this.$itemElement.find('[data-image-cropping-tool]').click(function() {
            let format = $(this).data('image-cropping-tool');
            self.overlay.open(format);
        });
    }
}

export class ImageCropperOverlay
{
    private item: MediaItem;

    private $element: JQuery;

    private cropper: Cropper;

    constructor(element: HTMLElement, item: MediaItem)
    {
        this.item = item;
        this.$element = $(element);

    }

    public open(format: String)
    {
        let image = this.createImage();
        this.showOverlay();

        let self = this;
        this.cropper = new Cropper(image, {
            ready: function () {
                self.finishLoading();
                this.cropper.move(1, -1);
            }
        });
    }

    private finishLoading()
    {
        this.$element.find('[data-image-crop-canvas-wrapper]').removeClass('loading');
    }

    private createImage(): HTMLImageElement
    {
        let image = new Image();
        image.src = this.item.getFileUrl();
        this.$element.find('[data-image-crop-canvas-wrapper]').html();
        this.$element.find('[data-image-crop-canvas-wrapper]').append(image);
        return image
    }

    private showOverlay()
    {
        this.$element.show();
        let self = this;

        this.$element.find('[data-image-crop-cancel]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();
            self.$element.hide();
        });

        this.$element.find('[data-image-crop-submit]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();

            self.sendImage(function() {
                self.$element.hide();
            });
        });


        this.$element.find('[data-image-cropper-action]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();

            let action = $(this).data("image-cropper-action");

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
    }

    private sendImage(callback: () => void)
    {
        let canvasData = this.cropper.getCropBoxData();
        console.log(canvasData);

        let url = '/media/image/cropper';
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
                if(callback) {
                    callback();
                }
            },
        });
    }
}

export let imageCropper = new ImageCropper();