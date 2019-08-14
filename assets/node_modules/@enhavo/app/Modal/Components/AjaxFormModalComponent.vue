<template>
    <div class="modal">

    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import AjaxFormModal from "@enhavo/app/Modal/Model/AjaxFormModal"

    @Component({})
    export default class ModalComponent extends Vue {
        name: string = 'ajax-form-modal';

        @Prop()
        modal: AjaxFormModal;
    }
</script>
