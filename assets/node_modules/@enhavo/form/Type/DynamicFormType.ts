import * as $ from 'jquery'
import Router from "@enhavo/core/Router";
import FormListener from "@enhavo/app/Form/FormListener";
import FormConvertEvent from "@enhavo/app/Form/Event/FormConvertEvent";
import FormInitializer from "@enhavo/app/Form/FormInitializer";
import DynamicFormItem from "@enhavo/form/Type/DynamicFormItem";
import DynamicFormMenu from "@enhavo/form/Type/DynamicFormMenu";
import DynamicFormConfig from "@enhavo/form/Type/DynamicFormConfig";
import DynamicFormItemAddButton from "@enhavo/form/Type/DynamicFormItemAddButton";
import FormType from "@enhavo/app/Form/FormType";

export default class DynamicFormType extends FormType
{
    private items: DynamicFormItem[] = [];

    private menu: DynamicFormMenu;

    private $container: JQuery;

    private placeholderIndex: number = -1;

    private collapse: boolean = true;

    private config: DynamicFormConfig;

    private scope: HTMLElement;

    private router: Router;

    constructor(element: HTMLElement, router: Router, config: DynamicFormConfig, scope: HTMLElement = null)
    {
        super(element);
        this.router = router;
        this.$container = this.$element.children('[data-dynamic-form-container]');
        this.scope = scope;
        this.config = config;
        this.initMenu();
        this.initActions();
        this.initItems();
        this.initContainer();

        let self = this;
        FormListener.onConvert(function(event: FormConvertEvent) {
            let html = event.getHtml();
            html = html.replace(new RegExp(self.config.prototypeName, 'g'), String(self.placeholderIndex));
            event.setHtml(html);
        });

        this.placeholderIndex = this.$container.children('[data-dynamic-form-item]').length - 1;
    }

    protected init()
    {

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
        let element: HTMLElement = <HTMLElement>this.$element.children('[data-dynamic-form-menu]').get(0);
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
        let url = this.router.generate(this.config.route, {
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
                if(button && button.getElement()) {
                    form.insertAfter(button.getElement());
                } else {
                    form.append(<HTMLElement>dynamicForm.$container.get(0));
                }

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
        if(this.config.startLoading) {
            this.config.startLoading();
        }
    }

    public endLoading()
    {
        if(this.config.endLoading) {
            this.config.endLoading();
        }
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