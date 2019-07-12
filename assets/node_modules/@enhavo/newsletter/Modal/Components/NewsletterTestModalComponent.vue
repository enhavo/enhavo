<template>
    <div class="modal">
        Newsletter Test
        <input type="text" v-model="modal.email" />
        <button @click="modal.send()" :disabled="!modal.email">Senden</button>
        <button @click="modal.close()">Close</button>
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
