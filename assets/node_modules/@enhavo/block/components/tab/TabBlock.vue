<template>
    <div class="tab-container">

        <div class="poly-collection-actions">
            <!-- TODO: Replace true/false with condition for all collapsed -->
            <div class="button" v-if="true">
                <i class="icon icon-unfold_less"></i>
            </div>
            <div class="button" v-if="false">
                <i class="icon icon-unfold_more"></i>
            </div>
        </div>

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
