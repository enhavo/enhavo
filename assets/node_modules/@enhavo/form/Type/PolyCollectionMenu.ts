import * as $ from "jquery";
import PolyCollectionType from "@enhavo/form/Type/PolyCollectionType";
import PolyCollectionItemAddButton from "@enhavo/form/Type/PolyCollectionItemAddButton";

export default class PolyCollectionMenu
{
    private $element: JQuery;

    private polyCollection: PolyCollectionType;

    private button: PolyCollectionItemAddButton;

    constructor(element: HTMLElement, polyCollection: PolyCollectionType)
    {
        this.polyCollection = polyCollection;
        this.$element = $(element);
        this.initActions();
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

    private topToElement(element:HTMLElement, toElement:HTMLElement, top: number = 0): number
    {
        let parent = <HTMLElement>$(element).offsetParent().get(0);
        if(parent == $('html').get(0)) {
            return top;
        }
        let topOffset = $(element).position().top;
        if(toElement == parent) {
            return top + topOffset;
        }
        return this.topToElement(parent, toElement, top + topOffset)
    }

    public hide()
    {
        this.button = null;
        this.$element.hide();
    }
}