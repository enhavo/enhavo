import $ from 'jquery'
import FormInitializer from "@enhavo/app/form/FormInitializer";
import PolyCollectionItem from "@enhavo/form/type/PolyCollectionItem";
import PolyCollectionMenu from "@enhavo/form/type/PolyCollectionMenu";
import PolyCollectionConfig from "@enhavo/form/type/PolyCollectionConfig";
import PolyCollectionItemAddButton from "@enhavo/form/type/PolyCollectionItemAddButton";
import FormType from "@enhavo/app/form/FormType";
import {PrototypeManager} from "@enhavo/form/prototype/PrototypeManager";
import Prototype from "@enhavo/form/prototype/Prototype";
import View from "@enhavo/app/view/View";
import Confirm from "@enhavo/app/ui/Confirm";
import Translator from "@enhavo/core/Translator";
import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";

export default class PolyCollectionType extends FormType
{
    private items: PolyCollectionItem[] = [];

    private menu: PolyCollectionMenu;

    private $container: JQuery;

    private placeholderIndex: number = -1;

    private collapse: boolean = true;

    private config: PolyCollectionConfig;

    private prototypeManager: PrototypeManager;

    private prototypes: Prototype[];

    private view: View;

    private translator: Translator;

    private eventDispatcher: FrameEventDispatcher;

    constructor(element: HTMLElement, config: PolyCollectionConfig, prototypeManager: PrototypeManager, view: View, translator: Translator, eventDispatcher: FrameEventDispatcher)
    {
        super(element);
        this.$container = this.$element.children('[data-poly-collection-container]');
        this.config = config;
        this.prototypeManager = prototypeManager;
        this.translator = translator;
        this.view = view;
        this.eventDispatcher = eventDispatcher;
        this.initPrototypes();
        this.initMenu();
        this.initActions();
        this.initItems();
        this.initContainer();
        this.placeholderIndex = this.$container.children('[data-poly-collection-item]').length - 1;
        this.initState()
    }

    protected init()
    {

    }

    private initState()
    {
        if (!this.isRootElement()) {
            return;
        }

        this.view.loadValue('poly-collection-expand', (value) => {
            if (value === null) {
                return;
            } else if (value) {
                this.expandAll();
            } else {
                this.collapseAll();
            }
        });
    }

    private initPrototypes()
    {
        this.prototypes = this.prototypeManager.getPrototypes(this.config.prototypeStorage);
    }

    private initItems()
    {
        let items:PolyCollectionItem[]  = [];
        let polyCollection = this;
        this.$container.children('[data-poly-collection-item]').each(function() {
            items.push(new PolyCollectionItem(this, polyCollection));
        });
        this.items = items;
    }

    private initContainer()
    {
        let polyCollection = this;

        if (typeof this.$container.attr('data-reindexable') != 'undefined') {
            // Save initial index
            this.$container.data('initial-list-index', this.$container.children('[data-poly-collection-item]').length);
        }
        this.setOrder();

        this.$container.children('[data-poly-collection-add-button]').each(function() {
            new PolyCollectionItemAddButton(this, polyCollection);
        });
    }

    private initActions()
    {
        let polyCollection = this;

        this.collapse = this.config.collapsed;
        if(this.collapse) {
            polyCollection.collapseAll();
        } else {
            polyCollection.expandAll();
        }

        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-collapse-all]').click(function() {
            polyCollection.collapseAll(true);
        });

        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-expand-all]').click(function() {
            polyCollection.expandAll(true);
        });
    }

    private isRootElement()
    {
        return this.$element.parents('[data-poly-collection]').length === 0;
    }

    public getConfig()
    {
        return this.config;
    }

    public getPrototypes(): Prototype[]
    {
        return this.prototypes;
    }

    public getEntries(): Prototype[]
    {
        let entries = [];
        for (let key of this.config.entryKeys) {
            for (let prototype of this.getPrototypes()) {
                if(key === prototype.getParameter('key')) {
                    entries.push(prototype);
                    break;
                }
            }
        }
        return entries;
    }

    public collapseAll(saveState = false)
    {
        if (saveState && this.isRootElement()) {
            this.view.storeValue('poly-collection-expand', false, () => {
                this.eventDispatcher.dispatch(new SaveStateEvent());
            });
        }

        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-collapse-all]').hide();
        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-expand-all]').show();
        this.collapse = true;
        for (let item of this.items) {
            item.collapseAll();
        }
    }

    public expandAll(saveState = false)
    {
        if (saveState && this.isRootElement()) {
            this.view.storeValue('poly-collection-expand', true, () => {
                this.eventDispatcher.dispatch(new SaveStateEvent());
            });
        }

        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-collapse-all]').show();
        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-expand-all]').hide();
        this.collapse = false;
        for (let item of this.items) {
            item.expandAll();
        }
    }

    private initMenu()
    {
        let element: HTMLElement = <HTMLElement>this.$element.children('[data-poly-collection-menu]').get(0);
        this.menu = new PolyCollectionMenu(element, this);
    }

    public getMenu()
    {
        return this.menu;
    }

    public isCollapse()
    {
        return this.collapse;
    }

    public addItem(type: string, button: PolyCollectionItemAddButton)
    {
        this.placeholderIndex++;

        let form = new FormInitializer();
        form.setElement(this.createItem(type));
        if(button && button.getElement()) {
            form.insertAfter(button.getElement());
        } else {
            form.append(<HTMLElement>this.$container.get(0));
        }

        this.items.push(new PolyCollectionItem(form.getElement(), this));
        let newButton = this.createAddButton();
        $(form.getElement()).after(newButton.getElement());
        this.setOrder();
    }

    private createItem(type : string): HTMLElement
    {
        let prototype = this.getPrototype(type);
        let typeElement = <HTMLElement>this.$element.get(0);

        let itemTemplate = this.getItemTemplate();
        itemTemplate = itemTemplate.replace('__label__', prototype.getLabel());

        let prototypeTemplate = this.prototypeManager.renderTemplate(this.placeholderIndex, prototype, typeElement);

        let $item = $($.parseHTML(itemTemplate));
        $item.find('[data-poly-collection-item-container]').html(prototypeTemplate);
        $item.find('[data-type-name]').val(type);

        return <HTMLElement>$item.get(0);
    }

    private getPrototype(type: string): Prototype
    {
        for(let prototype of this.prototypes) {
            let key = <string>prototype.getParameter('key');
            if(type == key) {
                return prototype;
            }
        }
        throw 'Can\'t find prototype "'+type+'"';
    }

    private getItemTemplate(): string
    {
        return this.$element.data('poly-collection-item-template').trim();
    }

    private createAddButton() : PolyCollectionItemAddButton
    {
        let html = this.$element.data('poly-collection-add-button-template').trim();
        let element = $.parseHTML(html)[0];
        return new PolyCollectionItemAddButton(element, this);
    }

    private setOrder()
    {
        this.$container.find('[data-position]').each(function (index:number, element:HTMLElement) {
            $(element).val(index + 1);
        });
    };

    public moveItemUp(item: PolyCollectionItem, callback: () => void = function() {})
    {
        let index = this.$container.children('[data-poly-collection-item]').index(item.getElement());
        let self = this;

        if (index > 0) { // is not first element
            let buttonToMove = $(this.$container.children('[data-poly-collection-add-button]').get(index + 1));
            let buttonTarget = $(this.$container.children('[data-poly-collection-add-button]').get(index - 1));
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

    public moveItemDown(item: PolyCollectionItem, callback: () => void = function() {})
    {
        let index = this.$container.children('[data-poly-collection-item]').index(item.getElement());
        let size = this.$container.children('[data-poly-collection-item]').length;
        let self = this;

        if (index < (size - 1)) { // is not last element
            let buttonToMove = $(this.$container.children('[data-poly-collection-add-button]').get(index + 1));
            let buttonTarget = $(this.$container.children('[data-poly-collection-add-button]').get(index + 2));
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

    public confirmDelete(callback: (confirm: boolean) => void)
    {
        if (this.config.confirmDelete) {
            this.view.confirm(new Confirm(this.translator.trans('enhavo_app.view.message.delete'), () => {
                callback(true);
            }, () => {
                callback(false);
            }, this.translator.trans('enhavo_app.view.label.cancel'), this.translator.trans('enhavo_app.view.label.ok')));
        } else {
            callback(true);
        }
    }

    public removeItem(item: PolyCollectionItem)
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
