import ListItem from "@enhavo/form/type/ListItem";
import FormType from "@enhavo/app/form/FormType";
import FormDispatcher from "@enhavo/app/form/FormDispatcher";
import FormInitializer from "@enhavo/app/form/FormInitializer";

export default class ListType extends FormType
{
    private items : ListItem[];
    private placeholderIndex: number;

    protected init()
    {
        this.placeholderIndex = 0;
        this.initItems();
        this.initAddButton();
    }

    private initItems()
    {
        this.items = [];
        this.$element.children('[data-list-container]').children().each((index, element) => {
            this.items.push(new  ListItem(<HTMLElement>element, this));
            this.placeholderIndex++;
        });
    }

    private initAddButton()
    {
        this.$element.children('[data-add-button]').click((event) => {
            event.preventDefault();

            let $listContainer = this.$element.children('[data-list-container]');

            // grab the prototype template
            let item = $listContainer.attr('data-prototype');
            let prototype_name = $listContainer.attr('data-prototype-name');

            let placeholder = String(this.placeholderIndex);
            this.placeholderIndex++;

            item = item.replace(new RegExp(prototype_name, 'g'), placeholder).trim();
            let initializer = new FormInitializer;
            initializer.setHtml(item);
            initializer.append(<HTMLElement>$listContainer.get(0));
            this.items.push(new ListItem(initializer.getElement(), this));
            this.updatePosition();
        })
    }

    public removeItem(item: ListItem)
    {
        FormDispatcher.dispatchRemove(item.getElement());
        let index = this.items.indexOf(item);
        this.items.splice(index, 1);
    }

    public moveItemUp(item: ListItem)
    {
        let index = this.$element.children('[data-list-container]').children().index(item.getElement());
        if (index > 0) { // is not first element
            FormDispatcher.dispatchMove(item.getElement());
            let before = this.$element.children('[data-list-container]').children().get(index - 1);
            before.before(item.getElement());
            FormDispatcher.dispatchDrop(item.getElement());
        }

        this.updatePosition();
    }

    public moveItemDown(item: ListItem)
    {
        let index = this.$element.children('[data-list-container]').children().index(item.getElement());
        let size = this.$element.children('[data-list-container]').children().length;

        if (index < (size - 1)) { // is not first element
            FormDispatcher.dispatchMove(item.getElement());
            let after = this.$element.children('[data-list-container]').children().get(index + 1);
            after.after(item.getElement());
            FormDispatcher.dispatchDrop(item.getElement());
        }

        this.updatePosition();
    }

    private updatePosition()
    {
        for(let item of this.items) {
            item.setPosition($(item.getElement()).index()+1);
        }
    }
}
