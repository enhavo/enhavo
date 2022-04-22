import FormType from "@enhavo/app/form/FormType";
import MediaRow from "@enhavo/media/type/MediaRow";
import MediaConfig from "@enhavo/media/type/MediaConfig";
import * as $ from "jquery";
import "blueimp-file-upload/js/jquery.iframe-transport";
import "blueimp-file-upload/js/jquery.fileupload";

export default class MediaType extends FormType
{
    private config: MediaConfig;

    public static mediaTypes: Array<MediaType> = [];

    private row: MediaRow;

    constructor(element:HTMLElement)
    {
        super(element);
        this.config = this.$element.data('media-type');
        let rowElement = <HTMLElement>this.$element.find('[data-media-row]').get(0);
        this.row = new MediaRow(rowElement, this);
        this.initFileUpload();
        this.initUploadButton();
        this.dispatchInitEvent();
    }

    protected init()
    {

    }

    private dispatchInitEvent()
    {
        $(document).trigger('mediaInit', [this]);
    }

    public showDropZone()
    {
        if(this.config.upload) {
            this.row.showDropZone();
        }
    }

    public hideDropZone()
    {
        if(this.config.upload) {
            this.row.hideDropZone();
        }
    }

    public getConfig(): MediaConfig
    {
        return this.config
    }

    private initFileUpload()
    {
        let self = this;

        if (this.config.upload) {
            this.$element.find('[data-file-upload]').fileupload({
                dataType: 'json',
                done: function (event, data) {
                    $.each(data.result, function (index, meta) {
                        let item = self.row.createItem(meta);
                        item.updateThumb();
                        self.row.setOrder();
                    });
                },
                fail: function (event, data) {
                    self.row.showError();
                },
                add: function (event, data) {
                    if (!self.config.multiple) {
                        self.row.clearItems();
                    }
                    self.row.closeEdit();
                    data.submit();
                },
                progressall: function (event, data) {
                    let progress = data.loaded / data.total * 100;
                    if (progress >= 100) {
                        self.setProgress(0);
                    } else {
                        self.setProgress(progress);
                    }
                },
                dropZone: this.$element.find('[data-media-drop-zone]'),
                pasteZone: null
            });
        }
    }

    private initUploadButton()
    {
        let self = this;
        this.$element.find('[data-file-upload-button]').on('click', (event: JQuery.ClickEvent) => {
            event.stopPropagation();
            self.$element.find('[data-file-upload]').trigger('click');
        });
    }

    private setProgress(value:number)
    {
        this.$element.find('[data-media-progress-bar]').css('width', value + '%');
    }

    public getRow(): MediaRow
    {
        return this.row;
    }

    public getElement(): HTMLElement
    {
        return <HTMLElement>this.$element.get(0);
    }

    static media(mediaType:HTMLElement): MediaType|null
    {
        for(let media of MediaType.mediaTypes) {
            if(media.$element.get(0) === mediaType) {
                return media;
            }
        }
        return null;
    }

    static map(callback: (mediaType:MediaType) => void)
    {
        for(let media of MediaType.mediaTypes) {
            callback(media);
        }
    }
}
