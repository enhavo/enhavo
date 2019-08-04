<template>
    <div class="modal">
        <div class="title">Newsletter Test</div>
        <div class="form">
            <input type="text" v-model="modal.email" placeholder="E-Mail" />
            <div class="buttons">
                <button @click="modal.send()" :disabled="!modal.email" class="modal-btn primary">Senden</button>
                <button @click="modal.close()" class="modal-btn">Close</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import NewsletterTestModal from "@enhavo/newsletter/Modal/Model/NewsletterTestModal"

    @Component({})
    export default class ModalComponent extends Vue {
        name: string = 'newsletter-test-modal';

        @Prop()
        modal: NewsletterTestModal;
    }
</script>
