<template>
    <ul>
        <content-type
            v-for="contentType of contentTypes"
            :contentType="contentType"
        ></content-type>
    </ul>
</template>

<script lang="ts">
import {Options, Prop, Vue} from "vue-property-decorator";
import {ContentType} from "../Data";

@Options({})
export default class extends Vue {
    @Prop()
    contentTypes: Array<ContentType>;
}
</script>
