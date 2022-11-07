<template>
    <div class="form-list" ref="element">
        <slot name="list">
            <ul>
                <draggable
                    v-model="form.children"
                    item-key="name"
                    @change="changeOrder"
                    :group="form.draggableGroup"
                    :handle="form.draggableHandle"
                >
                    <template #item="{ element }">
                        <component :is="form.itemComponent"
                                   :form="element"
                                   :deletable="form.allowDelete"
                                   :sortable="form.sortable"

                                   @delete="deleteItem"
                                   @up="moveItemUp"
                                   @down="moveItemDown"
                        >
                            <template v-slot>
                                <slot name="item"></slot>
                            </template>
                            <template v-slot:buttons><slot name="item-buttons"></slot></template>
                            <template v-slot:down-button><slot name="item-down-button"></slot></template>
                            <template v-slot:up-button><slot name="item-up-button"></slot></template>
                            <template v-slot:delete-button><slot name="item-delete-button"></slot></template>
                            <template v-slot:drag-button><slot name="item-drag-button"></slot></template>
                        </component>
                    </template>
                </draggable>
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
import {FormListData} from "@enhavo/form/form/data/FormListData";
import {FormData} from "@enhavo/vue-form/data/FormData";
import ListItem from "@enhavo/form/type/ListItem";
import {Util} from "@enhavo/vue-form/form/Util";
import * as draggable from 'vuedraggable'

@Options({
    components: {'draggable': draggable}
})
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
        let item = this.createItem();
        this.form.children.push(item);
        this.updatePosition();
    }

    private createItem()
    {
        let item = JSON.parse(JSON.stringify(this.form.prototype));
        this.updateIndex(item, this.form.index);
        this.updateParents(item, this.form);
        this.updateFullName(item);
        this.form.index++;
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

    private updateParents(form: FormData, parent: FormData)
    {
        form.parent = parent;
        for (let child of form.children) {
            this.updateParents(child, form);
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

        if (this.form.onMove) {
            this.form.onMove(item);
        }
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
                if (grandChild.position === true) {
                    grandChild.value = i;
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

    private updateChangeIndex(form: FormListData)
    {
        form.name = this.form.index.toString();
        form.fullName = this.form.fullName + '[' + form.name + ']';
        form.parent = this.form;

        for (let child of form.children) {
            this.updateFullName(child);
        }
    }

    private updateFullName(form: FormData)
    {
        form.fullName = form.parent.fullName + '[' + form.name + ']';
        for (let child of form.children) {
            this.updateFullName(child);
        }
    }
}


</script>
