<template>
    <ul>
        <tag
            v-for="tag of tags"
            :tag="tag"
        ></tag>
    </ul>
</template>

<script lang="ts">
import {Options, Prop, Vue} from "vue-property-decorator";
import {Tag} from "../Data";

@Options({})
export default class extends Vue {
    @Prop()
    tags: Array<Tag>;
}
</script>
