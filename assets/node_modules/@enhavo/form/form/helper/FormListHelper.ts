import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {FormList} from "@enhavo/form/form/model/FormList";
import {Form} from "@enhavo/vue-form/model/Form";
import {RootForm} from "@enhavo/vue-form/model/RootForm";
import {FormPosition} from "@enhavo/form/form/model/FormPosition";

export class FormListHelper
{
    constructor(
        protected form: FormList,
        protected formFactory: FormFactory
    ) {
        if (this.form.index === null) {
            let index = -1;
            for (let child of this.form.children) {
                let childIndex = parseInt(child.name);
                if (childIndex > index) {
                    index = childIndex;
                }
            }
            this.form.index = index+1;
        }
    }

    public deleteItem(child: Form)
    {
        let shouldDelete = true;
        if (this.form.onDelete) {
            let shouldDelete = this.form.onDelete(child);
            if (typeof shouldDelete === 'object' && typeof shouldDelete.then === 'function') {
                shouldDelete.then((deleteFlag: boolean) => {
                    if (deleteFlag) {
                        this.form.children.splice(this.form.children.indexOf(child), 1);
                    }
                })
            }
        }

        if (shouldDelete) {
            this.form.children.splice(this.form.children.indexOf(child), 1);
        }
    }

    public addItem()
    {
        let item = this.createItem();
        this.form.children.push(item);
        this.updatePosition();
    }

    private createItem()
    {
        let item = JSON.parse(JSON.stringify(this.form.prototype));
        item = this.formFactory.create(item, (<RootForm>this.form.getRoot()).visitors, this.form);
        this.updateIndex(item, this.form.index);
        this.updateFullName(item);
        this.form.index++;

        if (this.form.onCreate) {
            let returnItem = this.form.onCreate(item);
            if (returnItem) {
                return returnItem;
            }
        }

        return item;
    }

    private updateIndex(item: any, index: number)
    {
        for (const key in item) {
            if (item.hasOwnProperty(key)) {
                if (typeof item[key] === "string") {
                    item[key] = item[key].replace(new RegExp(this.form.prototypeName, 'g'), index);
                } else if (typeof item[key] === "object" && key !== 'parent') {
                    this.updateIndex(item[key], index);
                }
            }
        }
    }

    public moveItemUp(item: Form)
    {
        const fromIndex = this.form.children.indexOf(item);
        if (fromIndex === 0) {
            return;
        }

        const toIndex = fromIndex - 1;
        const element = this.form.children.splice(fromIndex, 1)[0];

        this.form.children.splice(toIndex, 0, element);

        this.updatePosition();

        if (this.form.onMove) {
            this.form.onMove(item);
        }
    }

    public moveItemDown(item: Form)
    {
        const fromIndex = this.form.children.indexOf(item);
        if (fromIndex === this.form.children.length - 1) {
            return;
        }

        const toIndex = fromIndex + 1;
        const element = this.form.children.splice(fromIndex, 1)[0];

        this.form.children.splice(toIndex, 0, element);

        this.updatePosition();

        if (this.form.onMove) {
            this.form.onMove(item);
        }
    }

    private updatePosition()
    {
        let i = 0;
        for (let child of this.form.children) {
            let grandChildren = child.children;
            for (let grandChild of grandChildren) {
                if ((<FormPosition>grandChild).position === true) {
                    grandChild.value = i.toString();
                    i++;
                }
            }
        }
    }

    public changeOrder(event: any)
    {
        if (event.added) {
            this.updateChangeIndex(event.added.element);
            this.form.index++;
        }

        this.updatePosition();

        if (event.moved && this.form.onMove) {
            this.form.onMove(event.moved.element);
        }
    }

    private updateChangeIndex(form: FormList)
    {
        form.name = this.form.index.toString();
        form.fullName = this.form.fullName + '[' + form.name + ']';
        form.parent = this.form;

        for (let child of form.children) {
            this.updateFullName(child);
        }
    }

    private updateFullName(form: Form)
    {
        form.fullName = form.parent.fullName + '[' + form.name + ']';
        for (let child of form.children) {
            this.updateFullName(child);
        }
    }
}
