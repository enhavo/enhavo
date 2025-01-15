<template>
    <div class="app-view">
        <div class="form-view">
            <ui-components></ui-components>
            <flash-messages></flash-messages>
            <modal-stack></modal-stack>
            <action-bar :primary="manager.actions" :secondary="manager.actionsSecondary"></action-bar>

            <div class="tab-header" v-if="manager.tabs && manager.tabs.length > 1">
                <template v-for="tab in manager.tabs">
                    <div class="tab-head" @click="selectTab(tab)" :class="{'selected': tab.active, 'has-error': tab.error}">
                        {{ tab.label }}
                    </div>
                </template>
            </div>

            <div class="form-container" v-if="manager.tabs">
                <form-form v-if="manager.form" :form="manager.form">
                    <div class="form-errors-global">
                        <form-errors :form="manager.form"></form-errors>
                    </div>
                    <template v-for="tab of manager.tabs">
                        <component v-show="tab.active" :tab="tab" :form="manager.form" :is="tab.component"></component>
                    </template>
                    <form-widget v-if="manager.form.has('_token')" :form="manager.form.get('_token')" />
                </form-form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import '@enhavo/app/assets/styles/view.scss'
import {inject, onMounted} from "vue";
import {useRoute} from 'vue-router'
import {ResourceInputManager} from "../../manager/ResourceInputManager";
import {TabInterface} from "../../tab/TabInterface";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Frame} from "@enhavo/app/frame/Frame";
import {Router} from "@enhavo/app/routing/Router";

const manager = inject<ResourceInputManager>('resourceInputManager');
const frameManager = inject<FrameManager>('frameManager');
const router = inject<Router>('router');
const route = useRoute();


manager.load(generateUrl(route.meta.api as string, route.params.id as number));

async function selectTab(tab: TabInterface)
{
    await manager.selectTab(tab.key);
}

onMounted( async () => {
    let frame = await frameManager.getFrame();
    if (frame === null) {
        return;
    }
    let url = frame.url;
    frameManager.onChange((frame: Frame) => {
        if (url !== frame.url) {
            url = frame.url;
            manager.initTab(frame.url);
        }
    });
})

function generateUrl(route: string, id: number = null): string
{
    let parameters = {};
    if (route) {
        parameters['id'] = id;
    }
    return router.generate(route, parameters);
}

</script>
