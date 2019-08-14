<template>
    <div class="modal">
        <div class="modal-form-container" v-if="!modal.loading">
            <form v-once ref="container">
            </form>
            <div class="buttons">
                <button @click="save" class="modal-btn primary">{{ modal.saveLabel }}</button>
                <button @click="close" class="modal-btn">{{ modal.closeLabel }}</button>
            </div>
        </div>
        <loading-screen v-if="modal.loading"></loading-screen>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import AjaxFormModal from "@enhavo/app/Modal/Model/AjaxFormModal"
    import FormInitializer from "@enhavo/app/Form/FormInitializer";

    @Component()
    export default class ModalComponent extends Vue {
        name: string = 'ajax-form-modal';

        @Prop()
        modal: AjaxFormModal;

        mounted() {
            this.modal.loadForm().then(() => {
                this.setElement();
                this.modal.updateHandler = this.setElement;
            });
        }

        save() {
            this.modal.sendForm().then((close: boolean) => {
                if(close) {
                    this.modal.close();
                }
            }) ;
        }

        close() {
            this.modal.close();
        }

        private setElement()
        {
            let initializer = new FormInitializer();
            initializer.setElement(this.modal.element);
            $(this.$refs.container).html("");
            initializer.append(this.$refs.container);
        }
    }
</script>
