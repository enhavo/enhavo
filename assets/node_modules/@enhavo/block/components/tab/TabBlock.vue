<template>
    <div class="tab-container">

        <div class="poly-collection-actions" data-poly-collection-action="">
            <div class="button" data-poly-collection-action-collapse-all="">
                <i class="icon icon-unfold_less"></i>
            </div>
            <div class="button" data-poly-collection-action-expand-all="" style="display: none;">
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
