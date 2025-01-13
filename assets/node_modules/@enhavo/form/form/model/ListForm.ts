import {Form} from "@enhavo/vue-form/model/Form";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {PositionForm} from "@enhavo/form/form/model/PositionForm";
import {MoveEvent} from "@enhavo/form/form/event/MoveEvent";
import {DeleteEvent} from "@enhavo/form/form/event/DeleteEvent";
import {CreateEvent} from "@enhavo/form/form/event/CreateEvent";
import {ChangeEvent} from "@enhavo/vue-form/event/ChangeEvent";

export class ListForm extends Form
{
    public border: boolean;
    public sortable: boolean;
    public allowDelete: boolean;
    public allowAdd: boolean;
    public blockName: string;
    public prototype: Form;
    public prototypeName: string;
    public index: number;
    public itemComponent: string;
    public draggableGroup: string;
    public draggableHandle: string;

    constructor(
        protected formFactory: FormFactory,
    ) {
        super();
    }

    init()
    {
        if (this.index === null) {
            let index = -1;
            for (let child of this.children) {
                let childIndex = parseInt(child.name);
                if (childIndex > index) {
                    index = childIndex;
                }
            }
            this.index = index+1;
        }

        this.children.sort((a: Form, b: Form) => {
            let positionFormA = this.getPositionForm(a)
            let positionFormB = this.getPositionForm(b)

            if (positionFormA && positionFormB) {
                return parseInt(positionFormA.value) - parseInt(positionFormB.value)
            }

            return 0;
        });
    }

    public deleteItem(child: Form)
    {
        let event = new DeleteEvent(child);
        this.eventDispatcher.dispatchEvent(event, 'delete')

        if (!event.isStopped()) {
            this.eventDispatcher.dispatchEvent(new ChangeEvent(this, {
                action: 'delete',
                item: child,
                origin: this
            }), 'change');
            this.children.splice(this.children.indexOf(child), 1);
        }
    }

    public addItem(): Form
    {
        let item = this.createItem(this.getPrototype(), this.getPrototypeName());
        this.children.push(item);
        this.updatePosition();
        return item;
    }

    protected createItem(prototype: Form, prototypeName: string)
    {
        let item = JSON.parse(JSON.stringify(prototype));
        item = this.formFactory.create(item, this.getRoot().visitors, this);
        item.name = this.index.toString();
        item.update();
        this.index++;

        let event = new CreateEvent(item)
        this.eventDispatcher.dispatchEvent(event, 'create')
        this.eventDispatcher.dispatchEvent(new ChangeEvent(this, {
            action: 'create',
            item: item,
            origin: this
        }), 'change');
        return event.form;
    }

    private getPrototype(): Form
    {
        if(this.prototype) {
            return this.prototype;
        }

        for (let parent of this.getParents()) {
            let form = <ListForm>parent;
            if (form.prototype) {
                return form.prototype;
            }
        }

        throw 'Can\'t find prototype';
    }

    private getPrototypeName(): string
    {
        if(this.prototypeName) {
            return this.prototypeName;
        }

        for (let parent of this.getParents()) {
            let form = <ListForm>parent;
            if (form.prototypeName) {
                return form.prototypeName;
            }
        }

        throw 'Can\'t find prototype name';
    }

    public moveItemUp(item: Form)
    {
        this.eventDispatcher.dispatchEvent(new MoveEvent(item), 'beforeMove')

        const fromIndex = this.children.indexOf(item);
        if (fromIndex === 0) {
            return;
        }

        const toIndex = fromIndex - 1;
        const element = this.children.splice(fromIndex, 1)[0];

        this.children.splice(toIndex, 0, element);

        this.updatePosition();

        this.eventDispatcher.dispatchEvent(new MoveEvent(item), 'move')
        this.eventDispatcher.dispatchEvent(new ChangeEvent(this, {
            action: 'move',
            item: item,
            origin: this
        }), 'change');
    }

    public moveItemDown(item: Form)
    {
        this.eventDispatcher.dispatchEvent(new MoveEvent(item), 'beforeMove')

        const fromIndex = this.children.indexOf(item);
        if (fromIndex === this.children.length - 1) {
            return;
        }

        const toIndex = fromIndex + 1;
        const element = this.children.splice(fromIndex, 1)[0];

        this.children.splice(toIndex, 0, element);

        this.updatePosition();

        this.eventDispatcher.dispatchEvent(new MoveEvent(item), 'move')
        this.eventDispatcher.dispatchEvent(new ChangeEvent(this, {
            action: 'move',
            item: item,
            origin: this
        }), 'change');
    }

    protected updatePosition()
    {
        let i = 0;
        for (let child of this.children) {
            let positionForm = this.getPositionForm(child);
            if (positionForm) {
                positionForm.value = i.toString();
                i++;
            }
        }
    }

    private getPositionForm(form: Form)
    {
        for (let child of form.children) {
            if ((<PositionForm>child).position === true) {
                return child;
            }
        }

        return null;
    }

    public dragStart(event: any)
    {
        let item = null;
        for (let child of this.children) {
            if (child.element == event.item) {
                item = child;
                break;
            }
        }

        this.eventDispatcher.dispatchEvent(new MoveEvent(item), 'beforeMove')
    }

    public dragEnd(event: any)
    {
        let item = null;
        for (let child of this.children) {
            if (child.element == event.item) {
                item = child;
                break;
            }
        }

        this.eventDispatcher.dispatchEvent(new MoveEvent(item), 'move')
    }

    public changeOrder(event: any)
    {
        if (event.added) {
            this.updateChangeIndex(event.added.element);
            this.index++;
        }

        this.updatePosition();
        let targetItem = null;

        if (event.moved) {
            targetItem = event.moved.element;
        } else if (event.added) {
            targetItem = event.added.element;
        }

        if (targetItem) {
            this.eventDispatcher.dispatchEvent(new ChangeEvent(this, {
                action: 'move',
                item: targetItem,
                origin: this
            }), 'change');
        }
    }

    private updateChangeIndex(form: ListForm)
    {
        form.name = this.index.toString();
        form.fullName = this.fullName + '[' + form.name + ']';
        form.parent = this;

        for (let child of form.children) {
            child.update();
        }
    }

    protected morphChildren(form: Form)
    {
        let index = 0;
        for (let child of this.children) {
            child.name = index.toString();
            index++;
        }
    }
}
