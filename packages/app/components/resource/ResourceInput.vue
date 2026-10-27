<template>
    <div class="app-view">
        <div class="form-view">
            <ui-components></ui-components>
            <flash-messages></flash-messages>
            <modal-stack></modal-stack>
            <template v-if="manager">
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
                        <div class="form-errors-global" v-if="manager.form.errors && manager.form.errors.length > 0">
                            <form-errors :form="manager.form"></form-errors>
                        </div>
                        <template v-for="tab of manager.tabs">
                            <component v-show="tab.active" :tab="tab" :form="manager.form" :is="tab.component"></component>
                        </template>
                        <form-widget v-if="manager.form.has('_token')" :form="manager.form.get('_token')" />
                    </form-form>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {useRoute} from 'vue-router'
import {useAsyncService, useWhenReady} from "@enhavo/framework/composables/service";
import {ResourceInputManager} from "../../manager/ResourceInputManager";
import {TabInterface} from "../../tab/TabInterface";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Frame} from "@enhavo/app/frame/Frame";
import {Router} from "@enhavo/framework/routing/Router";
import {HtmlEntities} from "@enhavo/framework/util/HtmlEntities";
import {ExpressionLanguage} from "@enhavo/framework/expression-language/ExpressionLanguage";

const manager = useAsyncService<ResourceInputManager>('resourceInputManager');
const frameManager = useAsyncService<FrameManager>('frameManager');
const router = useAsyncService<Router>('router');
const route = useRoute();
const expressionLanguage = useAsyncService<ExpressionLanguage>('expressionLanguage');

useWhenReady([manager, router, expressionLanguage], (manager, router, expressionLanguage) => {
    let parameters = { id: route.params.id as number };
    if (route.meta.api_parameters) {
        parameters = expressionLanguage.evaluateObject(HtmlEntities.encodeObject(route.meta.api_parameters as Object), {
            route: route
        });
    }

    manager.load(router.generate(route.meta.api as string, parameters));
});

useWhenReady([manager, frameManager], async (manager, frameManager) => {
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
});

async function selectTab(tab: TabInterface)
{
    await manager.value.selectTab(tab.key);
}

</script>
