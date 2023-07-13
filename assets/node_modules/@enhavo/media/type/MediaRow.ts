import MediaType from "@enhavo/media/type/MediaType";
import MediaConfig from "@enhavo/media/type/MediaConfig";
import MediaItem from "@enhavo/media/type/MediaItem";
import MediaItemMeta from "@enhavo/media/type/MediaItemMeta";
import Sortable from 'sortablejs';
import * as $ from "jquery";

export default class MediaRow
{
    private static sorting = false;

    private $element: JQuery;

    private readonly items: Array<MediaItem>;

    private config: MediaConfig;

    private openEditItem: MediaItem;

    private index = 0;

    private resizeHandler: () => void;

    private readonly media: MediaType;

    constructor(element:HTMLElement, media:MediaType)
    {
        this.config = media.getConfig();
        this.media = media;
        this.$element = $(element);
        this.items = [];
        this.initSortable();

        let self = this;
        this.$element.children('[data-media-item]').each(function (index, element) {
            let $element =  $(element);
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
        return <HTMLElement>this.$element.get(0);
    }

    public getMedia(): MediaType
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
        if (MediaRow.sorting) return;
        this.$element.css('background-color', '#49a4e5');
    }

    hideDropZone()
    {
        this.$element.css('background-color', '');
    }

    createItem(meta:MediaItemMeta): MediaItem
    {
        let template = this.$element.parents('[data-media-type]').find('[data-media-item-template]').text();
        template = template.replace(/__name__/g, this.index.toString());
        let html = $.parseHTML(template)[0];
        let item = new MediaItem(html, meta, this);
        item.setFilename(meta.filename);
        item.setId(meta.id);
        item.setParameters(meta.parameters);
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
            let html = <HTMLElement[]>$edit.children().toArray();
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
        return <HTMLElement>this.$element.children('[data-media-item]').get(afterPosition);
    }

    private initSortable()
    {
        if (this.config.multiple && this.config.sortable) {
            let self = this;
            Sortable.create(<HTMLElement>this.$element[0], {
                animation: 150,
                touchStartThreshold: 10,
                draggable: '[data-media-item]',
                onStart: function() {
                    MediaRow.sorting = true;
                    self.closeEdit();
                },
                onUpdate: function () {
                    self.setOrder();
                },
                onEnd: function () {
                    MediaRow.sorting = false;
                },
            });
        }
    }
}
