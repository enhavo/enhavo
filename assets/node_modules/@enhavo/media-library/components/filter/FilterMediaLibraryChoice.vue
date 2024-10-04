<template>
    <div class="headline"><i class="icon icon-filter_list"></i>
        {{ data.label }}
    </div>
    <ul>
        <li v-for="choice in data.choices" :class="{'active': isSelected(choice)}">
            <div class="tag-name" @click="select(choice)">{{ choice.label }}</div>
        </li>
    </ul>
</template>

<script setup lang="ts">
import {AbstractFilter} from "@enhavo/app/filter/model/AbstractFilter";

const emit = defineEmits(['apply']);

const props = defineProps<{
    data: AbstractFilter
}>()

function isSelected(choice)
{
    return false;
}

function select(choice)
{
    emit('apply');
}

</script>
