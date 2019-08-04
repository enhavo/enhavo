<template>
    <div v-if="data.length" class="modal-container">
        <template v-for="modal in data">
            <div class="modal-component">
                <component v-bind:is="modal.component" v-bind:modal="modal"></component>
            </div>
        </template>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ApplicationBag from "@enhavo/app/ApplicationBag";
    import ModalInterface from "@enhavo/app/Modal/ModalInterface";
    let application = ApplicationBag.getApplication();

    @Component({
        components: application.getModalRegistry().getComponents()
    })
    export default class ModalComponent extends Vue {
        name: string = 'modal-component';

        @Prop()
        data: ModalInterface[];
    }
</script>
