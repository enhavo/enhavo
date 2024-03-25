<template>
    <a :href="getAnker()">{{ name }}</a><span v-if="required">*</span>
</template>

<script setup lang="ts">
const props = defineProps<{
    name: string
    type: string
    required: boolean
}>()

function getAnker(): string {
    let name = props.name.replace('_', '-');
    let type = props.type ? props.type.replace('_', '-') : null;

    if (type) {
        return '#' + name + '_' + type;
    }

    return '#' + name;
}
</script>
