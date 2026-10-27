<template>
    <div class="app-view">
        <ui-components></ui-components>
        <flash-messages></flash-messages>
        <modal-stack></modal-stack>
        <template v-if="manager">
            <action-bar :primary="manager.actions" :secondary="manager.actionsSecondary"></action-bar>
            <div class="grid">
                <filter-bar v-if="manager.filters" :filters="manager.filters" @apply="manager.applyFilters()" @reset="manager.resetFilters()"></filter-bar>
                <component
                    v-if="manager.collection"
                    :is="manager.collection.component"
                    :collection="manager.collection">
                </component>
                <batch-dropdown
                    v-if="manager.batches && manager.batches.length > 0"
                    :ids="manager.collection.getIds()"
                    :batches="manager.batches"
                    @executed="manager.collection.load()"
                ></batch-dropdown>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import {useRoute} from 'vue-router'
import {useAsyncService, useWhenReady} from "@enhavo/framework/composables/service";
import {ResourceIndexManager} from "@enhavo/app/manager/ResourceIndexManager";
import {ExpressionLanguage} from "@enhavo/framework/expression-language/ExpressionLanguage";
import {HtmlEntities} from '@enhavo/framework/util/HtmlEntities';
import {Router} from "@enhavo/framework/routing/Router";

const manager = useAsyncService<ResourceIndexManager>('resourceIndexManager');
const expressionLanguage = useAsyncService<ExpressionLanguage>('expressionLanguage');
const router = useAsyncService<Router>('router');
const route = useRoute();

useWhenReady([manager, expressionLanguage, router], (manager, expressionLanguage, router) => {
    const parameters = expressionLanguage.evaluateObject(HtmlEntities.encodeObject(route.meta.api_parameters as Object), {
        route: route
    });

    manager.load(router.generate(route.meta.api as string, parameters));
});

</script>
