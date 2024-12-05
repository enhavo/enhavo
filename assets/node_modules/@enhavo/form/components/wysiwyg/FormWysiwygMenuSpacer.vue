<template>
    <div class="wysiwyg-spacer"></div>
</template>

<script setup lang="ts">
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuSpacer} from "@enhavo/form/wysiwyg/WysiwygMenuSpacer";

const props = defineProps<{
    configuration: WysiwygMenuSpacer,
    form: WysiwygForm
}>()

</script>
