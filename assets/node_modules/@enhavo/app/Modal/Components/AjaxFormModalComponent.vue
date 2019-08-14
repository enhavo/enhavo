<template>
    <div class="modal">
        <form>
            <div v-once ref="container"></div>
        </form>
        <button @click="save">{{ modal.saveLabel }}</button>
        <button @click="close">{{ modal.closeLabel }}</button>
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
