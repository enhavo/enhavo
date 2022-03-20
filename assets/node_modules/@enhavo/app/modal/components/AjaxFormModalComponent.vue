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
import { Vue, Options, Prop, Watch, Inject } from "vue-property-decorator";
import AjaxFormModal from "@enhavo/app/modal/model/AjaxFormModal"
import FormInitializer from "@enhavo/app/form/FormInitializer";
import Translator from "@enhavo/core/Translator";

@Options({})
export default class extends Vue
{
    @Prop()
    modal: AjaxFormModal;

    @Inject()
    translator: Translator

    mounted() {
        this.modal.loadForm().then(() => {});
    }

    save() {
        this.modal.submit().then((close: boolean) => {
            if(close) {
                this.modal.close();
            }
        }).catch((close: boolean) => {
            if(close) {
                this.modal.close();
            }
        });
    }

    close() {
        this.modal.close();
    }

    trans(text) {
        return this.translator.trans(text);
    }

    @Watch('modal.element')
    private setElement()
    {
        let initializer = new FormInitializer();
        initializer.setElement(this.modal.element);
        $(this.$refs.container).html("");
        initializer.append(this.$refs.container);
        this.modal.form = this.$refs.container;
    }
}
</script>
