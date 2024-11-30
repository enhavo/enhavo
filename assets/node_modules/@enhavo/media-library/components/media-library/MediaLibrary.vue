<template>
    <div class="app-view"
         @dragover.prevent="manager.dragover"
         @dragleave.prevent="manager.dragleave"
         @dragdrop.prevent="manager.dragdrop"
         @drop.prevent="manager.drop"
    >
        <ui-components></ui-components>
        <flash-messages></flash-messages>
        <modal-stack></modal-stack>
        <action-bar :primary="manager.actions" :secondary="manager.actionsSecondary"></action-bar>

        <input v-show="false" v-once :ref="(el) => manager.uploadElement = el as HTMLElement" multiple type="file" @change.prevent="change"/>

        <div class="media-library-overlay" v-if="!manager.loading">
            <div class="inner-content">
                <div class="media-library-progress" style="width: 0;"></div>
                <div class="tag-list">
                    <component
                        :is="getFilter('content_type').component"
                        :data="getFilter('content_type')"
                        @apply="applyFilter()"
                    ></component>

                    <component
                        :is="getFilter('tags').component"
                        :data="getFilter('tags')"
                        @apply="applyFilter()"
                    ></component>

                    <div class="headline"><i class="icon icon-filter_list"></i>
                        Attributes
                    </div>
                    <ul>
                        <component
                            :is="getFilter('unusedFile').component"
                            :data="getFilter('unusedFile')"
                            @apply="applyFilter()"
                        ></component>
                    </ul>
                </div>

                <div class="result-search">
                    <component
                        :is="getFilter('filename').component"
                        :data="getFilter('filename')"
                        @apply="applyFilter()"
                    ></component>

                    <component
                        v-if="manager.collection"
                        :is="manager.collection.component"
                        :collection="manager.collection">
                    </component>

                    <batch-dropdown
                        v-if="manager.batches"
                        :ids="manager.collection.getIds()"
                        :batches="manager.batches"
                        @executed="batchExecuted"
                    ></batch-dropdown>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import {Translator} from "@enhavo/app/translation/Translator";
import {MediaLibraryManager} from "@enhavo/media-library/manager/MediaLibraryManager";
import '@enhavo/media-library/assets/styles.scss';
import {useRoute} from "vue-router";
import {HtmlEntities} from "@enhavo/app/util/HtmlEntities";
import {ExpressionLanguage} from "@enhavo/app/expression-language/ExpressionLanguage";
import {FilterInterface} from "@enhavo/app/filter/FilterInterface";

const translator = inject<Translator>('translator');
const manager = inject<MediaLibraryManager>('mediaLibraryManager');
const expressionLanguage = inject<ExpressionLanguage>('expressionLanguage');
const route = useRoute();
const parameters = expressionLanguage.evaluateObject(HtmlEntities.encodeObject(route.meta.api_parameters as Object));

manager.addListener();
manager.load(route.meta.api as string, parameters);


function getFilter(key: string): FilterInterface
{
    for (let filter of manager.filters) {
        if (filter.key === key) {
            return filter;
        }
    }

    return null;
}

function applyFilter()
{
    manager.collection.load();
}

function change(event)
{
    manager.change(event);
}

function batchExecuted()
{
    manager.collection.load()
    manager.dispatchCollectionUpdate();
}

</script>
