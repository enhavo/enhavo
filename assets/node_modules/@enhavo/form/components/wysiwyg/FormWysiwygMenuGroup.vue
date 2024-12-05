<template>
    <div class="wysiwyg-menu-group">
        <template v-for="item in configuration.items">
            <component :is="item.component" :configuration="item" :form="form"></component>
        </template>
    </div>
</template>

<script setup lang="ts">
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuGroup} from "@enhavo/form/wysiwyg/WysiwygMenuGroup";

const props = defineProps<{
    configuration: WysiwygMenuGroup,
    form: WysiwygForm
}>()

</script>
