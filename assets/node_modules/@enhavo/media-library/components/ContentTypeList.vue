<template>
    <ul>
        <content-type v-for="contentType in contentTypes" :content-type="contentType"></content-type>
    </ul>
</template>

<script setup lang="ts">
import {ContentType as ContentTypeData} from "@enhavo/media-library/Data";

const props = defineProps<{
    contentTypes: Array<ContentTypeData>;
}>()
</script>
