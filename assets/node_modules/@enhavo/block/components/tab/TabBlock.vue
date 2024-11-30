<template>
    <div class="tab-container">

        <div class="poly-collection-actions" v-if="tab.actions">
            <template v-for="action in tab.actions">
                <component :is="action.component" :data="action"></component>
            </template>
        </div>

        <form-widget :form="form.get(tab.property)" />
    </div>
</template>

<script setup lang="ts">
import {BlockTab} from "@enhavo/block/tab/BlockTab";
import {Form} from "@enhavo/vue-form/model/Form";
import {onMounted} from "vue";

const props = defineProps<{
    tab: BlockTab,
    form: Form,
}>()

onMounted(() => {
    props.tab.init();
})

</script>
