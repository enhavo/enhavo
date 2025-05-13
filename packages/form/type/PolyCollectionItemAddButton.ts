import $ from "jquery";
import PolyCollectionType from "@enhavo/form/type/PolyCollectionType";

export default class PolyCollectionItemAddButton
{
    private $element: JQuery;

    private polyCollection: PolyCollectionType;

    constructor(element: HTMLElement, polyCollection: PolyCollectionType)
    {
        this.polyCollection = polyCollection;
        this.$element = $(element);
        let that = this;

        this.$element.click(function() {
            polyCollection.getMenu().show(that)
        });
    }

    public getElement() : HTMLElement
    {
        return <HTMLElement>this.$element.get(0);
    }
}