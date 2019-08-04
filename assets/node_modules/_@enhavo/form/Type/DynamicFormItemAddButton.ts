import * as $ from "jquery";
import DynamicFormType from "@enhavo/form/Type/DynamicFormType";

export default class DynamicFormItemAddButton
{
    private $element: JQuery;

    private dynamicForm: DynamicFormType;

    constructor(element: HTMLElement, dynamicForm: DynamicFormType)
    {
        this.dynamicForm = dynamicForm;
        this.$element = $(element);
        let that = this;

        this.$element.click(function() {
            dynamicForm.getMenu().show(that)
        });
    }

    public getElement() : HTMLElement
    {
        return <HTMLElement>this.$element.get(0);
    }
}