<template>
    <div class="form-list" ref="element">
        <slot name="list">
            <ul>
                <draggable
                    v-model="form.children"
                    item-key="name"
                    @change="event => { form.changeOrder(event) }"
                    @start="event => { form.dragStart(event) }"
                    @end="event => { form.dragEnd(event) }"
                    :group="form.draggableGroup"
                    :handle="form.draggableHandle"
                    tag="li"
                >
                    <template #item="{ element }">
                        <component
                            :is="form.itemComponent"
                            :form="element"
                            :deletable="form.allowDelete"
                            :sortable="form.sortable"
                            @delete="event => { form.deleteItem(event) }"
                            @up="event => { form.moveItemUp(event) }"
                            @down="event => { form.moveItemDown(event) }"
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
                    <div class="form-list-button" @click.prevent="form.addItem()">
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
import {Util} from "@enhavo/vue-form/form/Util";
import * as draggable from 'vuedraggable'

@Options({
    components: {'draggable': draggable}
})
export default class extends Vue
{
    @Prop()
    form: FormList;

    updated()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        Util.updateAttributes(this.form.element, this.form.attr);
    }

    mounted()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        Util.updateAttributes(this.form.element, this.form.attr);
    }
}

</script>
