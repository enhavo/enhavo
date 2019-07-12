<template>
    <div class="modal">
        Iframe
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import IframeModal from "@enhavo/app/Modal/Model/IframeModal"

    @Component({})
    export default class ModalComponent extends Vue {
        name: string = 'iframe-modal';

        @Prop()
        modal: IframeModal;
    }
</script>


