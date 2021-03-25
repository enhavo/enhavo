<template>
    <div v-if="$modalManager.modals.length" class="modal-container">
        <template v-for="modal in $modalManager.modals">
            <div class="modal-component">
                <component v-bind:is="modal.component" v-bind:modal="modal"></component>
            </div>
        </template>
    </div>
</template>

<script lang="ts">
    import { Vue, Component } from "vue-property-decorator";

    @Component()
    export default class ModalComponent extends Vue {

    }
</script>
