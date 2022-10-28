<template>
    <form-list :form="form">
        <template v-slot:item-down-button>

        </template>
    </form-list>
</template>

<script lang="ts">
import {Vue, Options, Inject, Prop} from "vue-property-decorator";
import FormListComponent from "@enhavo/form/components/FormListComponent.vue";

@Options({
    components: {'form-list': FormListComponent},
})
export default class extends Vue
{
    @Prop()
    public form: FormData
}
</script>
