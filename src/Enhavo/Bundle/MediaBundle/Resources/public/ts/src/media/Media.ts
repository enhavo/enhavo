import * as $ from 'jquery'
import 'blueimp-file-upload'
import 'jquery-ui'

interface MediaConfig
{
    multiple: boolean;
    sortable: boolean;
    extensions: Array<UploadExtension>;
    upload: boolean;
    edit: boolean;
}

interface UploadExtension
{
    application: string;
}

export class Media
{
    private $element: JQuery;

    private config: MediaConfig;

    private static mediaTypes: Array<Media> = [];

    private static isDragAndDropBind: boolean = false;

    private row: MediaRow;

    constructor(element:HTMLElement)
    {
        this.$element = $(element);
        this.config = this.$element.data('media-type');
        this.init();
        this.dispatchInitEvent();
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

    private init()
    {
        let element:HTMLElement = this.$element.find('[data-media-row]').get(0);
        this.row = new MediaRow(element, this);
        this.initFileUpload();
        this.initUploadButton();
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
                dropZone: this.$element.find('[data-media-drop-zone]')
            });
        }
    }

    private initUploadButton()
    {
        let self = this;
        this.$element.find('[data-file-upload-button]').click(function (event) {
            event.stopPropagation();
            event.preventDefault();
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
        return this.$element.get(0);
    }

    static apply(form:HTMLElement|string)
    {
        $(form).find('[data-media-type]').each(function() {
            Media.mediaTypes.push(new Media(this));
        });

        if(!Media.isDragAndDropBind)
        {
            $(document).bind('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                Media.map(function (mediaType) {
                    mediaType.showDropZone();
                });
            });

            $(document).bind('dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                Media.map(function (mediaType) {
                    mediaType.hideDropZone();
                });
            });

            Media.isDragAndDropBind = true;
        }
    }

    static media(mediaType:HTMLElement): Media|null
    {
        for(let media of Media.mediaTypes) {
            if(media.$element.get(0) === mediaType) {
                return media;
            }
        }
        return null;
    }

    static map(callback: (mediaType:Media) => void)
    {
        for(let media of Media.mediaTypes) {
            callback(media);
        }
    }
}

export class MediaRow
{
    private $element: JQuery;

    private items: Array<MediaItem>;

    private config: MediaConfig;

    private openEditItem: MediaItem;

    private index = 0;

    private resizeHandler: () => void;

    private media: Media;

    constructor(element:HTMLElement, media:Media)
    {
        this.config = media.getConfig();
        this.media = media;
        this.$element = $(element);
        this.items = [];
        this.initSortable();

        let self = this;
        this.$element.children('[data-media-item]').each(function (index, element) {
            let $element =  $(element);
            let id = $element.find('[data-media-item-id]').data('media-item-id');
            let meta = $element.data('media-meta');
            $element.find('[data-media-item-id]').val(meta.id);
            let item = new MediaItem(<HTMLElement>element, meta, self);
            item.updateThumb();
            self.items.push(item);
            $(document).trigger('mediaAddItem', [item]);
            self.index++;
        });
        self.setOrder();
    }

    public getItems(): Array<MediaItem>
    {
        return this.items;
    }

    public getElement(): HTMLElement
    {
        return this.$element.get(0);
    }

    public getMedia(): Media
    {
        return this.media;
    }

    updateThumbs() {
        for (let item of this.items) {
            item.updateThumb();
        }
    }

    showDropZone()
    {
        this.$element.css('background-color', '#49a4e5');
    }

    hideDropZone()
    {
        this.$element.css('background-color', '');
    }

    createItem(meta:MediaItemMeta): MediaItem
    {
        let template = this.$element.parent().find('[data-media-item-template]').text();
        template = template.replace(/__name__/g, this.index.toString());
        let html = $.parseHTML(template)[0];
        let item = new MediaItem(html, meta, this);
        item.setFilename(meta.filename);
        item.setId(meta.id);
        this.items.push(item);
        this.$element.append(html);
        $(document).trigger('mediaAddItem', [item]);
        this.index++;
        return item;
    }

    setOrder()
    {
        let self = this;
        this.$element.children().each(function(index, child) {
            for (let item of self.items) {
                if(item.getElement() === child) {
                    item.setOrder(index);
                }
            }
        });
    }

    showError()
    {
        console.log('[Media][Error]: ' + this);
    }

    clearItems()
    {
        this.$element.children().remove();
    }

    openEdit(item: MediaItem)
    {
        if(this.openEditItem) {
            this.closeEdit();
        }
        item.active();
        let editBox = "<li data-media-edit-container class='media-edit'></li>";
        let html = $.parseHTML(editBox)[0];
        let $html = $(html);
        this.resizeEdit($html);
        $html.append(item.getEditElements());
        let afterElement = this.getListAfterElement(item);
        $(afterElement).after(html);
        this.openEditItem = item;
    }

    closeEdit()
    {
        if(this.openEditItem) {
            this.openEditItem.inactive();
            let $edit = this.$element.find('[data-media-edit-container]');
            let html = $edit.children().toArray();
            this.openEditItem.setEditElements(html);
            this.removeResizeHandler();
            $edit.remove();
            this.openEditItem = null;
        }
    }

    private resizeEdit($editElement: JQuery)
    {
        if(!this.config.multiple) {
            let self = this;
            this.resizeHandler = function() {
                let width = self.$element.parent().innerWidth();
                $editElement.css('width', width + 'px');
            };

            $(window).bind('resize', 'resize', this.resizeHandler);
            this.resizeHandler();
        }
    }

    private removeResizeHandler()
    {
        if(!this.config.multiple) {
            $(window).unbind('resize', this.resizeHandler);
        }
    }

    private getListAfterElement(item: MediaItem): HTMLElement
    {
        let position = 0;
        let width:number = null;
        let rowWidth = this.$element.innerWidth();
        let itemCount = this.$element.children('[data-media-item]').length;
        this.$element.children('[data-media-item]').each(function(index, element) {
            if(width === null) {
                width = $(element).outerWidth();
            }
            if(item.getElement() === element) {
                position = index;
                return false;
            }
            width = $(element).outerWidth();
        });

        let itemPerRows = Math.floor(rowWidth / width);
        let afterPosition = Math.floor(position/itemPerRows)*itemPerRows+itemPerRows;
        if(afterPosition > itemCount) {
            afterPosition = itemCount;
        }
        afterPosition--;
        return this.$element.children('[data-media-item]').get(afterPosition);
    }

    private initSortable()
    {
        if (this.config.multiple && this.config.sortable) {
            let self = this;
            this.$element.sortable({
                delay: 150,
                update: function () {
                    self.setOrder();
                },
                items: '> li[data-media-item]',
                start: function() {
                    self.closeEdit();
                }
            });
        }
    }
}

export class MediaItemMeta
{
    mimeType: string;

    md5Checksum: string;

    id: number;

    extension: string;

    filename: string;

    token: string;

    order: number
}

export class MediaItem
{
    private $element: JQuery;

    private meta: MediaItemMeta;

    private row: MediaRow;

    constructor(element:HTMLElement, meta:MediaItemMeta, row:MediaRow)
    {
        this.$element = $(element);
        this.meta = meta;
        this.row = row;
        this.addHandler();
    }

    private addHandler()
    {
        let self = this;

        if(this.row.getMedia().getConfig().edit) {
            this.$element.click(function() {
                self.openEdit();
            });
        }

        this.$element.find('[data-media-item-delete]').click(function(event) {
            event.stopPropagation();
            event.preventDefault();
            self.remove();
        });
    }

    inactive()
    {
        this.$element.removeClass('active');
    }

    active()
    {
        this.$element.addClass('active');
    }

    remove()
    {
        this.$element.remove();
        this.row.closeEdit();
        this.row.setOrder();
    }

    getElement():HTMLElement
    {
        return this.$element.get(0);
    }

    getEditElements():Array<HTMLElement>
    {
        return this.$element.find('[data-media-edit]').children().toArray();
    }

    setEditElements(element:HTMLElement|Array<HTMLElement>)
    {
        return this.$element.find('[data-media-edit]').append(element);
    }

    setOrder(order:number)
    {
        this.meta.order = order;
        this.$element.find('[data-position]').val(order);
    }

    setFilename(filename:string)
    {
        this.meta.filename = filename;
        this.$element.find('[data-media-item-filename]').val(filename);
    }

    setId(id:number)
    {
        this.meta.id = id;
        this.$element.find('[data-media-item-id]').val(id);
    }

    getId(): number
    {
        return this.meta.id;
    }

    openEdit()
    {
        this.row.openEdit(this);
    }

    closeEdit()
    {
        this.row.closeEdit();
    }

    getRow(): MediaRow
    {
        return this.row;
    }

    getMeta(): MediaItemMeta
    {
        return this.meta;
    }

    updateThumb()
    {
        //reset
        this.$element.find('[data-media-thumb]').css('background-image', 'none');
        this.$element.find('[data-media-thumb-icon]').removeClass().addClass('icon');

        switch (this.meta.mimeType) {
            case 'image/png':
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/gif':
                this.$element.find('[data-media-thumb]').css('background-image', 'url('+this.getThumbUrl()+')');
                break;
            case 'video/mpeg':
            case 'video/quicktime':
            case 'video/vnd.vivo':
            case 'video/x-msvideo':
            case 'video/x-sgi-movie':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-video');
                break;
            case 'audio/basic':
            case 'audio/echospeech':
            case 'audio/tsplayer':
            case 'audio/voxware':
            case 'audio/x-aiff':
            case 'audio/x-dspeeh':
            case 'audio/x-midi':
            case 'audio/x-mpeg':
            case 'audio/x-pn-realaudio':
            case 'audio/x-pn-realaudio-plugin':
            case 'audio/x-qt-stream:':
            case 'audio/x-wav':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-audio');
                break;
            case 'application/postscript':
            case 'application/rtf':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-doc-text');
                break;
            case 'application/pdf':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-pdf');
                break;
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document ':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-word');
                break;
            case 'application/msexcel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-excel');
                break;
            case 'application/mspowerpoint':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-powerpoint');
                break;
            case 'application/gzip':
            case 'application/x-compress':
            case 'application/x-compressed':
            case 'application/x-zip-compressed':
            case 'application/x-gtar':
            case 'application/x-shar':
            case 'application/x-tar':
            case 'application/x-ustar':
            case 'application/zip':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-archive');
                break;
            case 'text/css':
            case 'text/html':
            case 'text/javascript':
            case 'text/xml':
            case 'text/x-php':
            case 'application/json':
            case 'application/xhtml+xml':
            case 'application/xml':
            case 'application/x-httpd-php':
            case 'application/x-javascript':
            case 'application/x-latex':
            case 'application/x-php':
                this.$element.find('[data-media-thumb-icon]').addClass('icon-file-code');
                break;
            default:
                this.$element.find('[data-file-icon]').addClass('icon-doc');
        }
    }

    getThumbUrl(): string
    {
        let url = ' /file/resolve/{token}/{format}?v={random}';
        url = url.replace('{token}', this.meta.token.toString());
        url = url.replace('{format}', 'enhavoPreviewThumb');
        url = url.replace('{random}', Math.random().toString());
        return url;
    }

    getFileUrl(): string
    {
        let url = ' /file/resolve/{token}?v={random}';
        url = url.replace('{token}', this.meta.token.toString());
        url = url.replace('{random}', Math.random().toString());
        return url;
    }
}

export default Media;