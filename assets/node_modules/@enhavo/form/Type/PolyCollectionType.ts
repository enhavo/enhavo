import * as $ from 'jquery'
import FormInitializer from "@enhavo/app/Form/FormInitializer";
import PolyCollectionItem from "@enhavo/form/Type/PolyCollectionItem";
import PolyCollectionMenu from "@enhavo/form/Type/PolyCollectionMenu";
import PolyCollectionConfig from "@enhavo/form/Type/PolyCollectionConfig";
import PolyCollectionItemAddButton from "@enhavo/form/Type/PolyCollectionItemAddButton";
import FormType from "@enhavo/app/Form/FormType";
import PolyCollectionPrototype from "@enhavo/form/Type/PolyCollectionPrototype";

export default class PolyCollectionType extends FormType
{
    private items: PolyCollectionItem[] = [];

    private menu: PolyCollectionMenu;

    private $container: JQuery;

    private placeholderIndex: number = -1;

    private collapse: boolean = true;

    private config: PolyCollectionConfig;

    private prototypes: PolyCollectionPrototype[] = [];

    constructor(element: HTMLElement, config: PolyCollectionConfig)
    {
        super(element);
        this.$container = this.$element.children('[data-poly-collection-container]');
        this.config = config;
        this.initPrototypes();
        this.initMenu();
        this.initActions();
        this.initItems();
        this.initContainer();
        this.placeholderIndex = this.$container.children('[data-poly-collection-item]').length - 1;
    }

    protected init()
    {

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
            polyCollection.collapseAll();
        });

        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-expand-all]').click(function() {
            polyCollection.expandAll();
        });
    }

    private initPrototypes()
    {
        for(let key of this.config.entryKeys) {
            let $prototype = null;
            if(this.config.prototypesCount > 0 && this.config.prototypeStorage !== null) {
                // nested and root element
                $prototype = this.$element.children('[data-poly-collection-prototype-storage="'+this.config.prototypeStorage+'"][data-poly-collection-prototype="'+key+'"]');
            } else if (this.config.prototypeStorage !== null) {
                // nested and somewhere inside the tree
                $prototype = $('body').find('[data-poly-collection-prototype-storage="'+this.config.prototypeStorage+'"][data-poly-collection-prototype="'+key+'"]');
            } else {
                // not nested
                $prototype = this.$element.children('[data-poly-collection-prototype="'+key+'"]');
            }

            if($prototype.length === 0) {
                throw 'Can\'t find prototype "'+key+'"';
            }

            this.prototypes.push(new PolyCollectionPrototype(
                key,
                $prototype.data('poly-collection-prototype-label').trim(),
                $prototype.data('poly-collection-prototype-content').trim()
            ));
        }
    }

    public getConfig()
    {
        return this.config;
    }

    public collapseAll()
    {
        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-collapse-all]').hide();
        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-expand-all]').show();
        this.collapse = true;
        for (let item of this.items) {
            item.collapse();
        }
    }

    public expandAll()
    {
        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-collapse-all]').show();
        this.$element.children('[data-poly-collection-action]').children('[data-poly-collection-action-expand-all]').hide();
        this.collapse = false;
        for (let item of this.items) {
            item.expand();
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

        let itemTemplate = this.getItemTemplate();
        itemTemplate = itemTemplate.replace('__label__', prototype.getLabel());

        let prototypeTemplate = prototype.getTemplate();

        prototypeTemplate = prototypeTemplate.replace(new RegExp(this.config.prototypeName, 'g'), String(this.placeholderIndex));

        let $item = $($.parseHTML(itemTemplate));
        $item.find('[data-poly-collection-item-container]').html(prototypeTemplate);
        return <HTMLElement>$item.get(0);
    }

    private getPrototype(type: string): PolyCollectionPrototype
    {
        for(let prototype of this.prototypes) {
            if(type == prototype.getKey()) {
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

    public getPrototypes(): PolyCollectionPrototype[]
    {
        return this.prototypes;
    }
}
