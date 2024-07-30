<template>
    <div class="app-view">
        <view-view></view-view>
        <flash-messages></flash-messages>
        <modal-stack></modal-stack>
        <action-bar :primary="manager.actions" :secondary="manager.actionsSecondary"></action-bar>
        <div>
            <filter-bar v-if="manager.filters" :filters="manager.filters"></filter-bar>
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
import {inject } from "vue";
import { useRoute } from 'vue-router'
import {ResourceIndexManager} from "../../manager/ResourceIndexManager";

const manager = inject<ResourceIndexManager>('resourceIndexManager');
const route = useRoute();
manager.loadIndex(route.meta.api as string);

</script>
