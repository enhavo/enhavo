<template>
    <div class="form-poly-collection" :ref="(el) => form.setElement(el as HTMLElement)" v-show="form.isVisible()">
        <slot name="list">
            <draggable
                v-model="form.children"
                item-key="name"
                @change="event => { form.changeOrder(event) }"
                @start="event => { form.dragStart(event) }"
                @end="event => { form.dragEnd(event) }"
                :group="form.draggableGroup"
                :handle="form.draggableHandle"
                :disabled="!form.sortable"
                class="form-poly-collection-items"
            >
                <template #item="{ element }">
                    <component
                        :is="form.itemComponent"
                        :form="element"
                        :deletable="form.allowDelete"
                        :sortable="form.sortable"
                        :collapsable="form.collapsable"
                        :collapsed="form.isCollapsed(element)"
                        :block-name="form.blockName"
                        @delete="event => { form.deleteItem(event)} "
                        @up="event => { form.moveItemUp(event) }"
                        @down="event => { form.moveItemDown(event) }"
                        @uncollapse="event => { form.uncollapse(element) }"
                        @collapse="event => { form.collapse(element) }"
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
        </slot>

        <slot name="button-row">
            <div class="form-poly-collection-add-button-row" v-if="form.allowAdd">
                <slot name="buttons">
                    <div class="form-poly-collection-add-button" @click.prevent="form.toggleMenu()">
                        <slot name="add-button">
                            <i class="icon icon-add"></i>
                        </slot>
                    </div>
                </slot>
                <div v-if="form.isOpen" class="form-poly-collection-add-menu">
                    <template v-for="entryLabel of form.entryLabels">
                        <div v-if="isAllowed(entryLabel.key)" @click.prevent="form.addItem(entryLabel.key); form.toggleMenu()" class="add-menu-item">{{ entryLabel.label }}</div>
                    </template>
                </div>
            </div>
        </slot>
    </div>
</template>

<script setup lang="ts">
import {PolyCollectionForm} from "@enhavo/form/form/model/PolyCollectionForm";
import * as draggableComponent from 'vuedraggable'

const draggable = draggableComponent;
const props = defineProps<{
    form: PolyCollectionForm
}>()

function isAllowed(key: string): boolean
{
    return props.form.entryKeys.indexOf(key) >= 0;
}

</script>
