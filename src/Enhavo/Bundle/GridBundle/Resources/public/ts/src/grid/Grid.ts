import { enhavoAdapter } from 'media/Adapter/EnhavoAdapter';
import * as admin from 'app/Admin'
import * as router from 'app/Router'
import * as form from 'app/Form'

export class Grid
{
    private $element: JQuery;

    private items: GridItem[];

    private menu: GridMenu;

    private $container: JQuery;

    private placeholderIndex:number = 0;

    constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.$container = this.$element.find('[data-grid-container]');
        this.initMenu();
        this.initActions();
        this.initItems();
        this.initContainer();
    }

    public static apply(element: HTMLElement)
    {
        $(element).find('[data-grid]').each(function() {
            new Grid(this);
        });
    }

    private initItems()
    {
        let items:GridItem[]  = [];
        let grid = this;
        this.$element.find('[data-grid-item]').each(function() {
            items.push(new GridItem(this, grid));
        });
        this.items = items;
    }

    private initContainer()
    {
        let grid = this;

        if (typeof this.$container.attr('data-reindexable') != 'undefined') {
            // Save initial index
            this.$container.data('initial-list-index', this.$container.children('[data-grid-item]').length);
        }
        this.setOrder();
        form.reindex();

        this.$container.children('[data-grid-add-button]').each(function() {
            new GridItemAddButton(this, grid);
        });
    }

    private initActions()
    {
        let grid = this;

        this.$element.find('[data-grid-action-collapse-all]').click(function() {
            for (let item of grid.items) {
                item.collapse();
            }
        });

        this.$element.find('[data-grid-action-expand-all]').click(function() {
            for (let item of grid.items) {
                item.expand();
            }
        });
    }

    private initMenu()
    {
        let element :HTMLElement = this.$element.find('[data-grid-menu]').get(0);
        this.menu = new GridMenu(element, this);
    }

    public getMenu()
    {
        return this.menu;
    }

    public addItem(type: string, button: GridItemAddButton)
    {
        let url = router.generate('enhavo_grid_item', {
            type: type
        });

        // Generate unique placeholder for reindexing service
        let placeholder = '__grid_name' + this.placeholderIndex + '__';
        let formName = this.$element.data('grid-name') + '[items][' + placeholder + ']';
        this.placeholderIndex++;
        let grid = this;

        this.startLoading();
        $.ajax({
            type: 'POST',
            data: {
                formName: formName
            },
            url: url,
            success: function (data) {
                grid.endLoading();
                data = $.parseHTML(data.trim());
                grid.items.push(new GridItem(data, grid));
                // Initialize sub-elements for reindexing
                form.initReindexableItem(data, placeholder);
                $(document).trigger('gridAddAfter', [data]);
                $(button.getElement()).after(data);
                let newButton = grid.createAddButton();
                $(data).after(newButton.getElement());
                grid.setOrder();
                form.reindex();
            },
            error: function () {
                grid.endLoading();
            }
        });
    }

    private createAddButton() : GridItemAddButton
    {
        let html =
            '<div data-grid-add-button>\n' +
            '<i class="icon-circle-with-plus btnsmall add-grid-button"></i>\n' +
            '</div>';
        let element = $.parseHTML(html)[0];
        return new GridItemAddButton(element, this);
    }

    private setOrder()
    {
        this.$container.children().children('[data-grid-item-order]').each(function (index:number, element:HTMLElement) {
            $(element).val(index + 1);
        });
    };

    public startLoading()
    {
        admin.openLoadingOverlay();
    }

    public endLoading()
    {
        admin.closeLoadingOverlay();
    }

    public moveItemUp(item: GridItem)
    {
        let index = this.$container.children('[data-grid-item]').index(item.getElement());

        if (index > 0) { // is not first element
            let buttonToMove = this.$container.children('[data-grid-add-button]').get(index + 1);
            let buttonTarget = this.$container.children('[data-grid-add-button]').get(index - 1);
            $(buttonTarget).after(item.getElement());
            $(item.getElement()).after(buttonToMove);
        }

        this.setOrder();
        form.reindex();
    }

    public moveItemDown(item: GridItem)
    {
        let index = this.$container.children('[data-grid-item]').index(item.getElement());
        let size = this.$container.children('[data-grid-item]').length;

        if (index < (size - 1)) { // is not last element
            let buttonToMove = this.$container.children('[data-grid-add-button]').get(index + 1);
            let buttonTarget = this.$container.children('[data-grid-add-button]').get(index + 2);
            $(buttonTarget).after(item.getElement());
            $(item.getElement()).after(buttonToMove);
        }

        this.setOrder();
        form.reindex();
    }

    public removeItem(item: GridItem)
    {
        $(item.getElement()).next().remove();
        $(item.getElement()).remove();

        let index = this.items.indexOf(item);
        if (index > -1) {
            this.items.splice(index, 1);
        }
    }
}

export class GridMenu
{
    private $element: JQuery;

    private grid: Grid;

    private button: GridItemAddButton;

    constructor(element: HTMLElement, grid: Grid)
    {
        this.grid = grid;
        this.$element = $(element);
        this.initActions();
    }

    private initActions()
    {
        let menu = this;
        this.$element.find('[data-grid-menu-item]').click(function () {
            let name = $(this).data('grid-menu-item');
            menu.grid.addItem(name, menu.button);
            menu.hide();
        });
    }

    public show(button: GridItemAddButton)
    {
        if(this.button === button) {
            this.hide();
            return;
        }

        this.button = button;

        let position = $(button.getElement()).position();
        let dropDown = true;

        let topOffset = $(button.getElement()).offset().top;
        let windowHeight = $(window).height();
        let menuHeight = this.$element.height();
        let buttonHeight = $(button.getElement()).height();

        if((topOffset + menuHeight+ buttonHeight) > windowHeight) {
            dropDown = false;
        }

        if (dropDown) {
            this.$element.addClass('topTriangle');
            this.$element.css('top', 35 + position.top + 'px');
        } else {
            this.$element.addClass('bottomTriangle');
            this.$element.css('top', -1 * this.$element.height() - 25 + position.top + 'px');
        }
        this.$element.css('left', position.left + 'px');

        this.$element.show();
    }

    public hide()
    {
        this.button = null;
        this.$element.hide();
    }
}

export class GridItem
{
    private $element: JQuery;

    private grid: Grid;

    constructor(element: HTMLElement, grid: Grid)
    {
        this.grid = grid;
        this.$element = $(element);
        this.initActions();
    }

    private initActions()
    {
        let grid = this;

        this.$element.on('click', '.button-up', function () {
            grid.up();
        });

        this.$element.on('click', '.button-down', function () {
            grid.down();
        });

        this.$element.on('click', '.button-delete', function () {
            grid.remove();
        });
    }

    public getElement(): HTMLElement
    {
        return this.$element.get(0);
    }

    public collapse()
    {
        this.$element.find('[data-grid-item-container]').hide();
    }

    public expand()
    {
        this.$element.find('[data-grid-item-container]').show();
    }

    public up()
    {
        let wyswigs = this.$element.find('[data-wysiwyg]');
        if (wyswigs.length) {
            form.destroyWysiwyg(this.$element);
            this.grid.moveItemUp(this);
            form.initWysiwyg(this.getElement());
        } else {
            this.grid.moveItemUp(this);
        }
    }

    public down()
    {
        let wyswigs = this.$element.find('[data-wysiwyg]');
        if (wyswigs.length) {
            form.destroyWysiwyg(this.$element);
            this.grid.moveItemDown(this);
            form.initWysiwyg(this.getElement());
        } else {
            this.grid.moveItemDown(this);
        }
    }

    public remove()
    {
        this.grid.removeItem(this);
    }
}

export class GridItemAddButton
{
    private $element: JQuery;

    private grid: Grid;

    constructor(element: HTMLElement, grid: Grid)
    {
        this.grid = grid;
        this.$element = $(element);
        let that = this;

        this.$element.click(function() {
            grid.getMenu().show(that)
        });
    }

    public getElement() : HTMLElement
    {
        return this.$element.get(0);
    }
}