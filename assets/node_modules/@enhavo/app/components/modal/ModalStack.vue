<template>
    <div v-if="modalManager.modals.length" class="modal-container">
        <template v-for="modal in modalManager.modals">
            <div class="modal-component">
                <component :is="modal.component" :modal="modal"></component>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import { ModalManager } from "@enhavo/app/modal/ModalManager";

const modalManager = inject<ModalManager>('modalManager');
</script>
