import $ from "jquery";
import PolyCollectionType from "@enhavo/form/type/PolyCollectionType";
import PolyCollectionItemAddButton from "@enhavo/form/type/PolyCollectionItemAddButton";

export default class PolyCollectionMenu
{
    private $element: JQuery;

    private polyCollection: PolyCollectionType;

    private button: PolyCollectionItemAddButton;

    constructor(element: HTMLElement, polyCollection: PolyCollectionType)
    {
        this.polyCollection = polyCollection;
        this.$element = $(element);
        this.buildMenu();
        this.initActions();
    }

    private buildMenu()
    {
        let $container = this.$element.find('[data-poly-collection-menu-container]');
        let template = $container.data('poly-collection-menu-container');

        for (let prototype of this.polyCollection.getEntries()) {
            let menuItem = template.replace('__key__', prototype.getParameter('key'));
            menuItem = menuItem.replace('__label__', prototype.getLabel());
            $container.append(menuItem);
        }
    }

    private initActions()
    {
        let menu = this;
        this.$element.find('[data-poly-collection-menu-item]').click(function () {
            let name = $(this).data('poly-collection-menu-item');
            menu.polyCollection.addItem(name, menu.button);
            menu.hide();
        });
    }

    public show(button: PolyCollectionItemAddButton)
    {
        if(this.button === button) {
            this.hide();
            return;
        }

        this.button = button;
        this.$element.insertAfter(this.button.getElement()).show();
    }

    public hide()
    {
        this.button = null;
        this.$element.hide();
    }
}