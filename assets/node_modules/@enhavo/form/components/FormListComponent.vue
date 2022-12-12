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
                        <component
                            :is="form.itemComponent"
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
                    <div class="form-list-button" @click.prevent="addItem">
                        <slot name="add-button">
                            <i class="icon icon-add_box">+</i>
                        </slot>
                    </div>
                </slot>
            </div>
        </slot>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop, Inject} from "vue-property-decorator";
import {FormList} from "@enhavo/form/form/model/FormList";
import {FormPosition} from "@enhavo/form/form/model/FormPosition";
import {Form} from "@enhavo/vue-form/model/Form";
import {Util} from "@enhavo/vue-form/form/Util";
import * as draggable from 'vuedraggable'
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {RootForm} from "@enhavo/vue-form/model/RootForm";

@Options({
    components: {'draggable': draggable}
})
export default class extends Vue
{
    @Prop()
    form: FormList;

    @Inject()
    formFactory: FormFactory;

    updated()
    {
        this.form.element = this.$refs.element;
        Util.updateAttributes(<HTMLElement>this.$refs.element, this.form.attr);
    }

    mounted()
    {
        this.form.element = this.$refs.element;
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

    private deleteItem(child: Form)
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
        item = this.formFactory.create(item, (<RootForm>this.form.getRoot()).visitors, this.form);
        this.updateIndex(item, this.form.index);
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

</script>
