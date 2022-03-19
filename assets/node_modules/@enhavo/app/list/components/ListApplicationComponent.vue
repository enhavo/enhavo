<template>
    <div class="app-view">
        <view-view></view-view>
        <flash-messages></flash-messages>
        <action-bar></action-bar>
        <list-list></list-list>
    </div>
</template>

<script lang="ts">
import { Vue, Options } from "vue-property-decorator";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/view.scss'

@Options({})
export default class extends Vue
{

}
</script>
