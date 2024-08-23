<template>
    <div class="app-view">
        <div class="form-view">
            <ui-components></ui-components>
            <flash-messages></flash-messages>
            <modal-stack></modal-stack>
            <action-bar :primary="manager.actions" :secondary="manager.actionsSecondary"></action-bar>

            <div class="tab-header" v-if="manager.tabs && manager.tabs.length > 1">
                <template v-for="tab in manager.tabs">
                    <div class="tab-head" @click="manager.selectTab(tab)" :class="{'selected': tab.active, 'has-error': tab.error}">
                        {{ tab.label }}
                    </div>
                </template>
            </div>

            <div class="form-container" v-if="manager.tabs">
                <form-form v-if="manager.form" :form="manager.form">
                    <template v-for="tab of manager.tabs">
                        <div :class="{'tab-container': true, 'tab-full-width': true }">
                            <component v-show="tab.active" :tab="tab" :form="manager.form" :is="tab.component"></component>
                        </div>
                    </template>
                </form-form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import '@enhavo/app/assets/styles/view.scss'
import {inject } from "vue";
import { useRoute } from 'vue-router'
import {ResourceInputManager} from "../../manager/ResourceInputManager";
import UiComponents from "../ui/UiComponents.vue";

const manager = inject<ResourceInputManager>('resourceInputManager');
const route = useRoute();
manager.load(route.meta.api as string);

</script>
