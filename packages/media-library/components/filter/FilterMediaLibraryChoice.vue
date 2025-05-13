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
import {OptionFilter, Choice} from "@enhavo/app/filter/model/OptionFilter";

const emit = defineEmits(['apply']);

const props = defineProps<{
    data: OptionFilter
}>()

function isSelected(choice: Choice)
{
    return props.data.selected === choice;
}

function select(choice: Choice)
{
    if (props.data.selected == choice) {
        props.data.value = null;
        props.data.selected = null;
    } else {
        props.data.value = choice.code;
        props.data.selected = choice;
    }
    emit('apply');
}

</script>
