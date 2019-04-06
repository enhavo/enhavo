import * as $ from "jquery";
import DynamicFormType from "@enhavo/form/Type/DynamicFormType";
import DynamicFormItemAddButton from "@enhavo/form/Type/DynamicFormItemAddButton";

export default class DynamicFormMenu
{
    private $element: JQuery;

    private dynamicForm: DynamicFormType;

    private button: DynamicFormItemAddButton;

    constructor(element: HTMLElement, dynamicForm: DynamicFormType)
    {
        this.dynamicForm = dynamicForm;
        this.$element = $(element);
        this.initActions();
    }

    private initActions()
    {
        let menu = this;
        this.$element.find('[data-dynamic-form-menu-item]').click(function () {
            let name = $(this).data('dynamic-form-menu-item');
            menu.dynamicForm.addItem(name, menu.button);
            menu.hide();
        });
    }

    public show(button: DynamicFormItemAddButton)
    {
        if(this.button === button) {
            this.hide();
            return;
        }

        this.button = button;

        let position = $(button.getElement()).position();
        let dropDown = true;

        let scope:HTMLElement;
        scope = this.dynamicForm.getScope();
        if(scope == null) {
            scope = <HTMLElement>$('body').get(0);
        }

        let topOffset = this.topToElement(button.getElement(), scope, 0);
        let center = $(button.getElement()).height()/2 + topOffset;
        let halfHeight = $(scope).height()/2;

        if(halfHeight < center) {
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