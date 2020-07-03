import * as $ from "jquery";
import PolyCollectionType from "@enhavo/form/Type/PolyCollectionType";
import FormDispatcher from "@enhavo/app/Form/FormDispatcher";

export default class PolyCollectionItem
{
    private $element: JQuery;

    private polyCollection: PolyCollectionType;

    constructor(element: HTMLElement, polyCollection: PolyCollectionType)
    {
        this.polyCollection = polyCollection;
        this.$element = $(element);
        this.initActions();
        if(polyCollection.isCollapse()) {
            this.collapse();
        } else {
            this.expand();
        }
    }

    private initActions()
    {
        let polyCollection = this;

        let $actions =  this.$element.children('[data-poly-collection-item-action]');

        $actions.find('[data-poly-collection-item-action-up]').click(function () {
            polyCollection.up();
        });

        $actions.find('[data-poly-collection-item-action-down]').click(function () {
            polyCollection.down();
        });

        $actions.find('[data-poly-collection-item-action-remove]').click(function () {
            polyCollection.remove();
        });

        $actions.find('[data-poly-collection-item-action-collapse]').click(function () {
            polyCollection.collapse();
        });

        $actions.find('[data-poly-collection-item-action-expand]').click(function () {
            polyCollection.expand();
        });
    }

    public getElement(): HTMLElement
    {
        return <HTMLElement>this.$element.get(0);
    }

    public collapse()
    {
        let $actions =  this.$element.children('[data-poly-collection-item-action]');

        $actions.find('[data-poly-collection-item-action-expand]').show();
        $actions.find('[data-poly-collection-item-action-collapse]').hide();
        this.$element.children('[data-poly-collection-item-container]').hide();
    }

    public expand()
    {
        let $actions =  this.$element.children('[data-poly-collection-item-action]');

        $actions.find('[data-poly-collection-item-action-expand]').hide();
        $actions.find('[data-poly-collection-item-action-collapse]').show();
        this.$element.children('[data-poly-collection-item-container]').show();
    }

    public up()
    {
        FormDispatcher.dispatchMove(this.getElement());
        this.polyCollection.moveItemUp(this,() => {
            FormDispatcher.dispatchDrop(this.getElement());
        });
    }

    public down()
    {
        FormDispatcher.dispatchMove(this.getElement());
        this.polyCollection.moveItemDown(this,() => {
            FormDispatcher.dispatchDrop(this.getElement());
        });
    }

    public remove()
    {
        FormDispatcher.dispatchRemove(this.getElement());
        this.polyCollection.removeItem(this);
    }
}