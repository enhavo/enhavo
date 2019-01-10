import * as admin from 'app/Admin'
import * as router from 'app/Router'
import * as form from 'app/Form'
import * as $ from 'jquery'
import { FormListener } from "app/Form/Form";
import { FormConvertEvent } from "app/Form/Form";
import { FormInitializer } from "app/Form/Form";

export class DynamicFormConfig
{
    route: string;
    prototypeName: string;
    collapsed: boolean;
}

export class DynamicForm
{
    private $element: JQuery;

    private items: DynamicFormItem[] = [];

    private menu: DynamicFormMenu;

    private $container: JQuery;

    private placeholderIndex: number = -1;

    private collapse: boolean = true;

    private config: DynamicFormConfig;

    private scope: HTMLElement;

    private formListener: FormListener;

    constructor(element: HTMLElement, config: DynamicFormConfig|null = null, scope: HTMLElement = null)
    {
        this.$element = $(element);
        this.$container = this.$element.children('[data-dynamic-form-container]');
        this.scope = scope;

        if(config == null) {
            this.config = this.$element.data('dynamic-config');
        } else {
            this.config = config;
        }

        this.initMenu();
        this.initActions();
        this.initItems();
        this.initContainer();

        this.formListener = new FormListener();

        let self = this;
        this.formListener.onConvert(function(event: FormConvertEvent) {
            let html = event.getHtml();
            html = html.replace(new RegExp(self.config.prototypeName, 'g'), String(self.placeholderIndex));
            event.setHtml(html);
        });

        this.placeholderIndex = this.$container.children('[data-dynamic-form-item]').length - 1;
    }

    public static apply(element: HTMLElement)
    {
        $(element).find('[data-dynamic-form]').each(function() {
            new DynamicForm(this);
        });
    }

    private initItems()
    {
        let items:DynamicFormItem[]  = [];
        let dynamicForm = this;
        this.$container.children('[data-dynamic-form-item]').each(function() {
            items.push(new DynamicFormItem(this, dynamicForm));
        });
        this.items = items;
    }

    private initContainer()
    {
        let dynamicForm = this;

        if (typeof this.$container.attr('data-reindexable') != 'undefined') {
            // Save initial index
            this.$container.data('initial-list-index', this.$container.children('[data-dynamic-form-item]').length);
        }
        this.setOrder();

        this.$container.children('[data-dynamic-form-add-button]').each(function() {
            new DynamicFormItemAddButton(this, dynamicForm);
        });
    }

    private initActions()
    {
        let dynamicForm = this;

        this.collapse = this.config.collapsed;
        if(this.collapse) {
            dynamicForm.collapseAll();
        } else {
            dynamicForm.expandAll();
        }

        this.$element.children('[data-dynamic-form-action]').children('[data-dynamic-form-action-collapse-all]').click(function() {
            dynamicForm.collapseAll();
        });

        this.$element.children('[data-dynamic-form-action]').children('[data-dynamic-form-action-expand-all]').click(function() {
            dynamicForm.expandAll();
        });
    }

    public getConfig()
    {
        return this.config;
    }

    public getScope()
    {
        return this.scope;
    }

    public collapseAll()
    {
        this.$element.children('[data-dynamic-form-action]').children('[data-dynamic-form-action-collapse-all]').hide();
        this.$element.children('[data-dynamic-form-action]').children('[data-dynamic-form-action-expand-all]').show();
        this.collapse = true;
        for (let item of this.items) {
            item.collapse();
        }
    }

    public expandAll()
    {
        this.$element.children('[data-dynamic-form-action]').children('[data-dynamic-form-action-collapse-all]').show();
        this.$element.children('[data-dynamic-form-action]').children('[data-dynamic-form-action-expand-all]').hide();
        this.collapse = false;
        for (let item of this.items) {
            item.expand();
        }
    }

    private initMenu()
    {
        let element :HTMLElement = this.$element.children('[data-dynamic-form-menu]').get(0);
        this.menu = new DynamicFormMenu(element, this);
    }

    public getMenu()
    {
        return this.menu;
    }

    public isCollapse()
    {
        return this.collapse;
    }

    public addItem(type: string, button: DynamicFormItemAddButton)
    {
        let url = router.generate(this.config.route, {
            type: type
        });

        let formName = this.$element.data('dynamic-form-name') + '[' + this.config.prototypeName + ']';
        this.placeholderIndex++;

        let dynamicForm = this;

        this.startLoading();
        $.ajax({
            type: 'POST',
            data: {
                formName: formName,
                prototypeName: this.config.prototypeName
            },
            url: url,
            success: function (data) {
                dynamicForm.endLoading();

                let form = new FormInitializer();
                form.setHtml(data);
                form.insertAfter(button.getElement());

                dynamicForm.items.push(new DynamicFormItem(form.getElement(), dynamicForm));
                let newButton = dynamicForm.createAddButton();
                $(form.getElement()).after(newButton.getElement());
                dynamicForm.setOrder();
            },
            error: function () {
                dynamicForm.endLoading();
            }
        });
    }

    private createAddButton() : DynamicFormItemAddButton
    {
        let html = this.$element.data('dynamic-form-add-button-template').trim();
        let element = $.parseHTML(html)[0];
        return new DynamicFormItemAddButton(element, this);
    }

    private setOrder()
    {
        this.$container.find('[data-position]').each(function (index:number, element:HTMLElement) {
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

    public moveItemUp(item: DynamicFormItem, callback: () => void = function() {})
    {
        let index = this.$container.children('[data-dynamic-form-item]').index(item.getElement());
        let self = this;

        if (index > 0) { // is not first element
            let buttonToMove = $(this.$container.children('[data-dynamic-form-add-button]').get(index + 1));
            let buttonTarget = $(this.$container.children('[data-dynamic-form-add-button]').get(index - 1));
            let domElementToMove = $(item.getElement());

            domElementToMove.slideUp(200,function() {
                buttonTarget.after(domElementToMove);
                domElementToMove.after(buttonToMove);
                domElementToMove.slideDown(200,function() {
                    self.setOrder();
                    if(typeof callback != "undefined") {
                        callback();
                    }
                });
            });
        } else {
            this.setOrder();
            if(typeof callback != "undefined") {
                callback();
            }
        }
    }

    public moveItemDown(item: DynamicFormItem, callback: () => void = function() {})
    {
        let index = this.$container.children('[data-dynamic-form-item]').index(item.getElement());
        let size = this.$container.children('[data-dynamic-form-item]').length;
        let self = this;

        if (index < (size - 1)) { // is not last element
            let buttonToMove = $(this.$container.children('[data-dynamic-form-add-button]').get(index + 1));
            let buttonTarget = $(this.$container.children('[data-dynamic-form-add-button]').get(index + 2));
            let domElementToMove = $(item.getElement());

            domElementToMove.slideUp(200,function() {
                buttonTarget.after(domElementToMove);
                domElementToMove.after(buttonToMove);
                domElementToMove.slideDown(200,function() {
                    self.setOrder();
                    if(typeof callback != "undefined") {
                        callback();
                    }
                });
            });
        } else {
            this.setOrder();
            if(typeof callback != "undefined") {
                callback();
            }
        }
    }

    public removeItem(item: DynamicFormItem)
    {
        $(item.getElement()).next().remove();
        $(item.getElement()).css({opacity:0,transition: 'opacity 550ms'}).slideUp(350,function() {
            this.remove();
        });

        let index = this.items.indexOf(item);
        if (index > -1) {
            this.items.splice(index, 1);
        }
    }
}

export class DynamicFormMenu
{
    private $element: JQuery;

    private dynamicForm: DynamicForm;

    private button: DynamicFormItemAddButton;

    constructor(element: HTMLElement, dynamicForm: DynamicForm)
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
            scope = $('body').get(0);
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
        let parent = $(element).offsetParent().get(0);
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

export class DynamicFormItem
{
    private $element: JQuery;

    private dynamicForm: DynamicForm;

    constructor(element: HTMLElement, dynamicForm: DynamicForm)
    {
        this.dynamicForm = dynamicForm;
        this.$element = $(element);
        this.initActions();
        if(dynamicForm.isCollapse()) {
            this.collapse();
        } else {
            this.expand();
        }
    }

    private initActions()
    {
        let dynamicForm = this;

        let $actions =  this.$element.children('[data-dynamic-form-item-action]');

        $actions.find('[data-dynamic-form-item-action-up]').click(function () {
            dynamicForm.up();
        });

        $actions.find('[data-dynamic-form-item-action-down]').click(function () {
            dynamicForm.down();
        });

        $actions.find('[data-dynamic-form-item-action-remove]').click(function () {
            dynamicForm.remove();
        });

        $actions.find('[data-dynamic-form-item-action-collapse]').click(function () {
            dynamicForm.collapse();
        });

        $actions.find('[data-dynamic-form-item-action-expand]').click(function () {
            dynamicForm.expand();
        });
    }

    public getElement(): HTMLElement
    {
        return this.$element.get(0);
    }

    public collapse()
    {
        let $actions =  this.$element.children('[data-dynamic-form-item-action]');

        $actions.find('[data-dynamic-form-item-action-expand]').show();
        $actions.find('[data-dynamic-form-item-action-collapse]').hide();
        this.$element.children('[data-dynamic-form-item-container]').hide();
    }

    public expand()
    {
        let $actions =  this.$element.children('[data-dynamic-form-item-action]');

        $actions.find('[data-dynamic-form-item-action-expand]').hide();
        $actions.find('[data-dynamic-form-item-action-collapse]').show();
        this.$element.children('[data-dynamic-form-item-container]').show();
    }

    public up()
    {
        let wyswigs = this.$element.find('[data-wysiwyg]');
        let self = this;
        if (wyswigs.length) {
            form.destroyWysiwyg(this.$element);
            this.dynamicForm.moveItemUp(this,function() {
                form.initWysiwyg(self.getElement());
            });
        } else {
            this.dynamicForm.moveItemUp(this);
        }
    }

    public down()
    {
        let wyswigs = this.$element.find('[data-wysiwyg]');
        let self = this;
        if (wyswigs.length) {
            form.destroyWysiwyg(this.$element);
            this.dynamicForm.moveItemDown(this,function() {
                form.initWysiwyg(self.getElement());
            });
        } else {
            this.dynamicForm.moveItemDown(this);
        }
    }

    public remove()
    {
        this.dynamicForm.removeItem(this);
    }
}

export class DynamicFormItemAddButton
{
    private $element: JQuery;

    private dynamicForm: DynamicForm;

    constructor(element: HTMLElement, dynamicForm: DynamicForm)
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
        return this.$element.get(0);
    }
}