<template>
    <div class="view-container" :style="{width: width}">
        <div class="toolbar">
            <i @click="close()">X</i>
            <i @click="minimize()"><</i>
        </div>
        <component v-bind:is="data.component" v-bind:data="data"></component>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import eventDispatcher from  '../event-dispatcher';
    import { Event } from  '../Event/Event';

    @Component
    export default class ViewContainer extends Vue {
        name: 'view-container';
        @Prop()
        data: array;

        close()
        {
            console.log('close');
            let event = new Event;
            event.name = 'close-view';
            eventDispatcher.dispatch(event);
        }

        minimize()
        {
            console.log('minimize');
        }

        get width(): string
        {
            console.log(this.data);
            if(!this.data.width) {
                this.data.width = 200;
            }
            return this.data.width + 'px'
        }
    }

</script>

<style lang="scss" scoped>
    .view-container {
        height: 100%; background-color: yellow;
        .toolbar { height: 20px; background-color: blue }
    }
</style>
