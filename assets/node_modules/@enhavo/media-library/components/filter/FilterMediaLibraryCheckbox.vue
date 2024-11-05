<template>
    <li :class="{'active': data.value}">
        <div class="tag-name" @click="select()">{{ data.label }}</div>
    </li>
</template>

<script setup lang="ts">
import {BooleanFilter} from "@enhavo/app/filter/model/BooleanFilter";

const emit = defineEmits(['apply']);

const props = defineProps<{
    data: BooleanFilter
}>()

function select()
{
    props.data.value = !props.data.value
    emit('apply');
}
</script>
