<template>
    <div class="tab-container">
        <form-widget :form="form.get(tab.property)" />
    </div>
</template>

<script setup lang="ts">
import {BlockTab} from "@enhavo/block/tab/BlockTab";
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    tab: BlockTab,
    form: Form,
}>()

</script>
