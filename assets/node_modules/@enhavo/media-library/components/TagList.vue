<template>
    <ul>
        <tag v-for="tag of tags" :tag="tag"></tag>
    </ul>
</template>

<script setup lang="ts">
import {Tag} from "@enhavo/media-library/Data";

const props = defineProps<{
    tags: Array<Tag>
}>()
</script>
