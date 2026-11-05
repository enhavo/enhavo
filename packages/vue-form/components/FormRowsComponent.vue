<template>
    <div v-for="row in rows" class="form-arrangement-row" :style="{'--form-arrangement-columns': getColumns(row)}">
        <component :is="column.form.rowComponent" v-for="column in row" :form="column.form" />
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

function getColumns(row: Column[]): string
{
    return row.map(column => `minmax(0, ${column.size}fr)`).join(' ');
}
</script>