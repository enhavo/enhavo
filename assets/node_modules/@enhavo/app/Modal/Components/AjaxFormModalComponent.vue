<template>
    <div class="modal">
        <div class="modal-form-container" v-show="!modal.loading">
            <form v-once ref="container"></form>
            <div class="buttons">
                <button @click="save" class="modal-btn primary">{{ trans(modal.saveLabel) }}</button>
                <button @click="close" class="modal-btn">{{ trans(modal.closeLabel) }}</button>
            </div>
        </div>
        <loading-screen v-if="modal.loading"></loading-screen>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop, Watch } from "vue-property-decorator";
    import AjaxFormModal from "@enhavo/app/Modal/Model/AjaxFormModal"
    import FormInitializer from "@enhavo/app/Form/FormInitializer";

    @Component()
    export default class ModalComponent extends Vue {
        name: string = 'ajax-form-modal';

        @Prop()
        modal: AjaxFormModal;

        mounted() {
            this.modal.loadForm().then(() => {});
        }

        save() {
            let data = this.getFormData();
            this.modal.sendForm(data).then((close: boolean) => {
                if(close) {
                    this.modal.close();
                }
            }).catch((close: boolean) => {
                if(close) {
                    this.modal.close();
                }
            });
        }

        private getFormData(): object
        {
            let formData = {};
            let data = $(this.$refs.container).serializeArray();
            for(let row of data) {
                formData[row.name] = row.value;
            }
            return formData;
        }

        close() {
            this.modal.close();
        }

        trans(text) {
            return this.$translator.trans(text);
        }

        @Watch('modal.element')
        private setElement()
        {
            let initializer = new FormInitializer();
            initializer.setElement(this.modal.element);
            $(this.$refs.container).html("");
            initializer.append(this.$refs.container);
        }
    }
</script>
