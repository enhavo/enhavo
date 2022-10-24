<template>
    <div class="form-list" ref="element">
        <slot name="list">
            <ul>
                <component :is="form.itemComponent"
                       v-for="child of form.children"
                       :form="child"
                       :deletable="form.allowDelete"
                       :sortable="form.sortable"
                       @delete="deleteItem"
                       @up="moveItemUp"
                       @down="moveItemDown">
                    <template v-slot>
                        <slot name="item"></slot>
                    </template>
                    <template v-slot:buttons><slot name="item-buttons"></slot></template>
                    <template v-slot:down-button><slot name="item-down-button"></slot></template>
                    <template v-slot:up-button><slot name="item-up-button"></slot></template>
                    <template v-slot:delete-button><slot name="item-delete-button"></slot></template>
                </component>
            </ul>
        </slot>

        <slot name="button-row">
            <div class="form-list-button-row" v-if="form.allowAdd">
                <slot name="buttons">
                    <div class="form-list-button" @click.prevent="addItem"><i class="icon icon-add_box">+</i></div>
                </slot>
            </div>
        </slot>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop} from "vue-property-decorator";
import {Form} from "@enhavo/vue-form/form/Form";
import ListItem from "@enhavo/form/type/ListItem";
import {Util} from "@enhavo/vue-form/form/Util";

@Options({})
export default class extends Vue
{
    @Prop()
    form: FormListData;

    updated()
    {
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }

    mounted()
    {
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }

    created()
    {
        /**
         * If the data we get is not an array, we have to convert it into an array to take care of the order
         */
        if (!Array.isArray(this.form.children)) {
            this.form.children = Object.values(this.form.children);
        }
    }

    private deleteItem(child: FormData)
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
    public itemComponent: string;
    public onDelete: (form: FormData) => boolean|Promise<boolean>;
}

</script>
