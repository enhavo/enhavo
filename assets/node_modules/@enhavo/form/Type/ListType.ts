import ListItem from "@enhavo/form/Type/ListItem";
import FormType from "@enhavo/form/FormType";
import FormDispatcher from "@enhavo/form/FormDispatcher";
import FormInitializer from "@enhavo/form/FormInitializer";

export default class ListType extends FormType
{
    private items : ListItem[];

    private placeholderIndex : number = 0;

    protected init()
    {
        this.initItems();
        this.initAddButton();
    }

    private initItems()
    {
        let self = this;
        this.items = [];
        this.$element.children('[data-list-container]').children().each(function(index,element) {
            self.items.push(new  ListItem(<HTMLElement>element, self));
        });
    }

    private initAddButton()
    {
        let self = this;
        this.$element.children('[data-add-button]').click(function (event) {
            event.preventDefault();

            let $listContainer = self.$element.children('[data-list-container]');

            // grab the prototype template
            let item = $listContainer.attr('data-prototype');
            let prototype_name = $listContainer.attr('data-prototype-name');

            // generate unique placeholder for reindexing service
            let placeholder = '__name' + self.placeholderIndex + '__';
            self.placeholderIndex++;

            item = item.replace(new RegExp(prototype_name, 'g'), placeholder).trim();
            let initializer = new FormInitializer;
            initializer.setHtml(item);
            initializer.append(<HTMLElement>$listContainer.get(0));
            self.items.push(new ListItem(initializer.getElement(), self));
            self.updatePosition();
        })
    }

    public removeItem(item: ListItem)
    {
        FormDispatcher.dispatchRemove(item.getElement());
        let index = this.items.indexOf(item);
        this.items.splice(index, index);
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
        let i = 0;
        for(let item of this.items) {
            i++;
            item.setPosition(i);
        }
    }
}