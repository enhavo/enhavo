<template>
    <div class="modal">
        Iframe
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import IframeModal from "@enhavo/app/modal/model/IframeModal"

@Options({})
export default class extends Vue
{
    @Prop()
    modal: IframeModal;
}
</script>


