<template>
    <div v-for="row in rows" class="form-arrangement-row" :style="{'--form-arrangement-ratio': getRatio(row)}">
        <div v-for="column in row" class="form-arrangement-column" :style="{flexGrow: column.size}">
            <component :is="column.form.rowComponent" :form="column.form" />
        </div>
    </div>
    <component :is="child.rowComponent" v-for="child of remainingChildren" :form="child" />
</template>

<script setup lang="ts">
import {computed} from "vue";
import {Form, FormArrangementColumn} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()

type Column = {
    size: number,
    form: Form,
}

const arrangement = computed<FormArrangementColumn[][]>(() => props.form.arrangement ?? []);

const rows = computed<Column[][]>(() => {
    return arrangement.value
        .map(row => row
            .map(column => ({size: column.size, form: props.form.children.find(child => child.name === column.key)}))
            .filter(column => column.form !== undefined))
        .filter(row => row.length > 0);
});

// The form is submitted from the DOM, so every child has to be rendered. Only direct children can be arranged, a nested
// key like "parent.child" would hide the other children of "parent", which are rendered here with their parent instead.
const remainingChildren = computed<Form[]>(() => {
    const arranged = arrangement.value.flat().map(column => column.key);

    return props.form.children.filter(child => !arranged.includes(child.name));
});

// Row width in multiples of its smallest column
function getRatio(row: Column[]): number
{
    const sizes = row.map(column => column.size);

    return sizes.reduce((total, size) => total + size, 0) / Math.min(...sizes);
}
</script>