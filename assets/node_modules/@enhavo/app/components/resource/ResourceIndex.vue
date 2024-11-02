<template>
    <div class="app-view">
        <ui-components></ui-components>
        <flash-messages></flash-messages>
        <modal-stack></modal-stack>
        <action-bar :primary="manager.actions" :secondary="manager.actionsSecondary"></action-bar>
        <div class="grid">
            <filter-bar v-if="manager.filters" :filters="manager.filters" @apply="manager.applyFilters()" @reset="manager.resetFilters()"></filter-bar>
            <component
                v-if="manager.collection"
                :is="manager.collection.component"
                :collection="manager.collection">
            </component>
            <batch-dropdown
                v-if="manager.batches"
                :ids="manager.collection.getIds()"
                :batches="manager.batches"
                @executed="manager.collection.load()"
            ></batch-dropdown>
        </div>
    </div>
</template>

<script setup lang="ts">
import '@enhavo/app/assets/styles/view.scss'
import {inject} from "vue";
import {useRoute} from 'vue-router'
import {ResourceIndexManager} from "@enhavo/app/manager/ResourceIndexManager";
import {ExpressionLanguage} from "@enhavo/app/expression-language/ExpressionLanguage";
import {HtmlEntities} from '@enhavo/app/util/HtmlEntities';
import {Router} from "@enhavo/app/routing/Router";

const manager = inject<ResourceIndexManager>('resourceIndexManager');
const expressionLanguage = inject<ExpressionLanguage>('expressionLanguage');
const route = useRoute();
const parameters = expressionLanguage.evaluateObject(HtmlEntities.encodeObject(route.meta.api_parameters as Object));
const router = inject<Router>('router');

manager.load(router.generate(route.meta.api as string, parameters));

</script>
