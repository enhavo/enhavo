<template>
    <div class="e-app">
        <div class="e-toolbar">
            <div class="toolbar-brand">Brand</div>
            <div class="toolbar-toggle"></div>
            <div class="toolbar-left">
                <!-- Breadcrumb -->
                <div class="e-breadcrumb">
                    <div class="breadcrumb-path"></div>
                    <div class="breadcrumb-divider"></div>
                    <div class="breadcrumb-path"></div>
                </div>
            </div>
            <div class="toolbar-right">
                <button class="toolbar-btn">Translate <i class="e-icon e-icon-zoom_out_map"></i></button>
                <button class="toolbar-btn">Magic <i class="icon-3d_rotation"></i></button>
                <div class="toolbar-divider"></div>
                <button class="toolbar-btn">DE <i class="e-icon"></i></button>
                <div class="toolbar-divider"></div>
                <button class="toolbar-btn">Settings <i class="e-icon"></i></button>
                <button class="toolbar-btn">Johannes <i class="e-icon"></i></button>
            </div>
        </div>

        <div class="e-menubar">
            <div class="menubar-main">
                <div class="menu-item-list">
                    <div class="e-menu-item">Dashboard</div>
                    <div class="e-menu-item-list">
                        <div class="menu-item">Articles</div>
                        <div class="menu-item-list-container is-expanded">
                            <div class="e-menu-item">Tag</div>
                            <div class="e-menu-item">Category</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-bottom"></div>
        </div>

        <div class="e-frames">
            <div class="frame"></div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { inject, ref, reactive } from 'vue'
import { MainManager } from "@enhavo/app/manager/MainManager"
import { MenuManager } from "@enhavo/app/menu/MenuManager"

const mainManager = ref<MainManager | null>(null)
const menuManager = ref<MenuManager | null>(null)

const mainManagerPromise = inject<Promise<MainManager>>('mainManager')
const menuManagerPromise = inject<Promise<MenuManager>>('menuManager')

;(async () => {
    mainManager.value = await mainManagerPromise
    await mainManager.value.load()

    menuManager.value = await menuManagerPromise
})()


// import {MainManager} from "@enhavo/app/manager/MainManager";
// import {MenuManager} from "../../menu/MenuManager";
// import FrameStackMobileDropdown from "../frame/FrameStackMobileDropdown.vue";
// import FlashMessages from "@enhavo/app/components/flash-message/FlashMessages.vue";
//
// const mainManager = inject<MainManager>('mainManager');
// const menuManager = inject<MenuManager>('menuManager');
//
// mainManager.load();
//
// function getBrandingImageStyles()
// {
//     if (mainManager.branding && mainManager.branding.logo) {
//         return {
//             backgroundImage: 'url(' + mainManager.branding.logo + ')',
//         }
//     }
//     return {};
// }
//
// function home()
// {
//     window.location.href = '/admin/'
// }

const menuCollapsed = ref(false)

const menuPage = reactive({
    "key": "page",
    "label": "Page",
    "component": "e-menu-item",
    "open": () => {
        console.log('open page');
    },
});

const menuArticle = reactive({
    "key": "article",
    "label": "Article",
    "component": "e-menu-item",
    "expanded": false,
    "open": function() {
        this.expanded = !this.expanded;
    },
    children: [
        {
            "key": "category",
            "label": "Category",
            "component": "e-menu-item",
            "open": () => {
                console.log('open category');
            }
        }
    ]
});

const menuUser = reactive({
    "key": "user",
    "label": "User",
    "component": "e-menu-item",
    "open": () => {
        console.log('open user');
    }
});

const frameOne = reactive({
    "id": "b9f3cbd2-b7b8-4f08-afb2-3799bfa7a07f",
    "component": "e-frame-iframe",
    "url": "/admin/dashboard/dashboard",
    "parent": null,
    "label": "Dashboard",
    "wait": true,
    "keepAlive": false,
    "display": true,
    "closeable": true,
    "minimize": false,
    "position": 0,
    "width": 30,
    "loaded": true,
    "keepMinimized": false,
    "focus": false,
    "removed": false
});

const frameTwo = reactive({
    "id": "cb028e50-0555-4753-b5ca-bfba6e79fdbb",
    "component": "e-frame",
    "parent": null,
    "label": "cb028e50-0555-4753-b5ca-bfba6e79fdbb",
    "wait": true,
    "keepAlive": false,
    "display": true,
    "closeable": true,
    "minimize": false,
    "position": 1,
    "width": null,
    "loaded": true,
    "keepMinimized": false,
    "focus": true,
    "removed": false
});

</script>
