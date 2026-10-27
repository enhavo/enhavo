<template>
    <div class="app-view">
        <ui-components></ui-components>
        <flash-messages></flash-messages>
        <modal-stack></modal-stack>
        <template v-if="manager">
            <action-bar :primary="manager.actions" :secondary="manager.actionsSecondary"></action-bar>
            <div :class="{'preview-view': true, 'tablet': manager.frameClass === 'tablet', 'mobile': manager.frameClass === 'mobile', 'desktop': manager.frameClass === 'desktop'}">
                <iframe class="iframe" name="_preview" :ref="(el) => manager.iframe = el as HTMLIFrameElement" v-once></iframe>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import {useRoute} from 'vue-router'
import {useAsyncService, useWhenReady} from "@enhavo/framework/composables/service";
import {ResourcePreviewManager} from "../../manager/ResourcePreviewManager";
import {Router} from "@enhavo/framework/routing/Router";

const manager = useAsyncService<ResourcePreviewManager>('resourcePreviewManager');
const route = useRoute();
const router = useAsyncService<Router>('router');

useWhenReady([manager, router], (manager, router) => {
    if (!route.meta.api) {
        manager.loadDefaults();
    } else {
        manager.load(router.generate(route.meta.api as string));
    }
});
</script>
