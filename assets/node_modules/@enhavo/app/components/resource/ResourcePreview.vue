<template>
    <div class="app-view">
        <ui-components></ui-components>
        <flash-messages></flash-messages>
        <modal-stack></modal-stack>
        <action-bar :primary="manager.actions" :secondary="manager.actionsSecondary"></action-bar>
        <div :class="{'preview-view': true, 'tablet': manager.frameClass === 'tablet', 'mobile': manager.frameClass === 'mobile', 'desktop': manager.frameClass === 'desktop'}">
            <iframe class="iframe" name="preview" :ref="(el) => manager.iframe = el as HTMLIFrameElement" v-once></iframe>
        </div>
    </div>
</template>

<script setup lang="ts">
import '@enhavo/app/assets/styles/view.scss'
import {inject} from "vue";
import {useRoute} from 'vue-router'
import {ResourcePreviewManager} from "../../manager/ResourcePreviewManager";
import {Router} from "@enhavo/app/routing/Router";

const manager = inject<ResourcePreviewManager>('resourcePreviewManager');
const route = useRoute();
const router = inject<Router>('router');

if (!route.meta.api) {
    manager.loadDefaults();
} else {
    manager.load(router.generate(route.meta.api as string));
}

</script>
