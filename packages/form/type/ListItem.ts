import $ from "jquery";
import ListType from "@enhavo/form/type/ListType";

export default class ListItem
{
    private $element: JQuery;

    private $buttons: JQuery;

    private list: ListType;

    constructor(element: HTMLElement, list: ListType)
    {
        this.$element = $(element);
        this.$buttons = this.$element.children('[data-list-item-buttons]');
        this.list = list;
        this.initDeleteButton();
        this.initUpButton();
        this.initDownButton();
    }

    private initDeleteButton()
    {
        let self = this;
        this.$buttons.children('[data-list-item-delete]').click(function(event) {
            self.$element.remove();
        });
    }

    private initUpButton()
    {
        let self = this;
        this.$buttons.children('[data-list-item-up]').click(function(event) {
            event.preventDefault();
            event.stopPropagation();
            self.list.moveItemUp(self);
        });
    }

    private initDownButton()
    {
        let self = this;
        this.$buttons.children('[data-list-item-down]').click(function(event) {
            event.preventDefault();
            event.stopPropagation();
            self.list.moveItemDown(self);
        });
    }

    public getElement(): HTMLElement
    {
        return <HTMLElement>this.$element.get(0);
    }

    public setPosition(number: number)
    {
        let fields: JQuery = this.$element.find('[data-position]');
        let designatedField: JQuery = fields.first();
        fields.each((index: number, field: JQuery) => {
            let $field: JQuery = $(field);
            if ($field.parents().length < designatedField.parents().length) {
                designatedField = $field;
            }
        });

        designatedField.val(number);
    }
}
