<template>
    <div class="frame">
        <div class="view-container" :class="{'has-viewstack-dropdown': getHasMoreThanOneView()}">
            <template v-for="frame in frameStack.getRenderFrames()">
                <frame-stack-frame v-show="frame.display" :frame="frame" v-if="!frame.removed" :key="frame.id"></frame-stack-frame>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject} from "vue";
import {FrameStack} from "@enhavo/app/frame/FrameStack";

const frameStack = inject<FrameStack>('frameStack');

function getHasMoreThanOneView() 
{
    return frameStack.getFrames().length > 0;
}

</script>

<style lang="scss" scoped>
.frame {width:100%;flex:1 0 0;
    .view-container {height:100%;display:flex;}
}
</style>
