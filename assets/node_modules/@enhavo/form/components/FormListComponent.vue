<template>
    <div data-list class="list-type">
        <ul>
            <form-list-item
                v-for="child of form.children"
                :form="child"
                :deletable="form.allowDelete"
                :sortable="form.sortable"
                @delete="deleteItem"
                @up="moveItemUp"
                @down="moveItemDown">
            </form-list-item>
        </ul>

        <div class="list-add-button-row" v-if="form.allowAdd" @click.prevent="addItem">
            <div class="list-add-button"><i class="icon icon-add_box">+</i></div>
        </div>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";
import ListItem from "@enhavo/form/type/ListItem";

@Options({})
export default class extends Vue
{
    @Prop()
    form: FormListData;

    private created()
    {
        /**
         * If the data we get is not an array, we have to convert it into an array to take care of the order
         */
        if (!Array.isArray(this.form.children)) {
            this.form.children = Object.values(this.form.children);
        }
    }

    private deleteItem(child: Form)
    {
        this.form.children.splice(this.form.children.indexOf(child), 1);
    }

    private addItem()
    {
        this.form.children.push(this.createItem())
        this.updatePosition();
    }

    private createItem()
    {
        let item = JSON.parse(JSON.stringify(this.form.prototype));
        this.updateIndex(item, this.form.index);
        this.form.index++;
        return item;
    }

    private updateIndex(item: any, index: number)
    {
        for (const key in item) {
            if (item.hasOwnProperty(key)) {
                if (typeof item[key] === "string") {
                    item[key] = item[key].replace(new RegExp(this.form.prototypeName, 'g'), index);
                } else if (typeof item[key] === "object") {
                    this.updateIndex(item[key], index);
                }
            }
        }
    }

    public moveItemUp(item: ListItem)
    {
        const fromIndex = this.form.children.indexOf(item);
        if (fromIndex === 0) {
            return;
        }

        const toIndex = fromIndex - 1;
        const element = this.form.children.splice(fromIndex, 1)[0];

        this.form.children.splice(toIndex, 0, element);

        this.updatePosition();
    }

    public moveItemDown(item: ListItem)
    {
        const fromIndex = this.form.children.indexOf(item);
        if (fromIndex === this.form.children.length - 1) {
            return;
        }

        const toIndex = fromIndex + 1;
        const element = this.form.children.splice(fromIndex, 1)[0];

        this.form.children.splice(toIndex, 0, element);

        this.updatePosition();
    }

    private updatePosition()
    {
        let i = 0;
        for (let child of this.form.children) {
            let grandChildren = child.children;
            for (let formKey in grandChildren) {
                if (grandChildren.hasOwnProperty(formKey) && grandChildren[formKey].position === true) {
                    grandChildren[formKey].value = i;
                    i++;
                }
            }
        }
    }
}

class FormListData extends Form
{
    public border: boolean;
    public sortable: boolean;
    public allowDelete: boolean;
    public allowAdd: boolean;
    public blockName: string;
    public prototype: Form;
    public prototypeName: string;
    public index: number;
}

</script>
