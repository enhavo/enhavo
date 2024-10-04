<template>
    <div class="search">
        <input v-model="data.value" type="text"  @keyup="keyup" />
        <span class="media-library-search-reset" @click="data.value = ''"><i class="icon icon-close"></i></span>
        <span class="media-library-search-submit" @click="emit('apply')"><i class="icon icon-search"></i></span>
    </div>
</template>

<script setup lang="ts">
import {AbstractFilter} from "@enhavo/app/filter/model/AbstractFilter";

const props = defineProps<{
    data: AbstractFilter
}>()

const data = props.data;

const emit = defineEmits(['apply']);

function getHasValue(): boolean
{
    if (data.value == "") {
        return false;
    } else if (data.value == null) {
        return false;
    }

    return true;
}

function keyup(event: KeyboardEvent)
{
    if (event.keyCode == 13) {
        emit('apply')
    }
}
</script>
