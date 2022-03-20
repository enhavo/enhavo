<template>
    <div class="app-view">
        <div v-bind:class="['form-view', form.data.cssClass]">
            <view-view></view-view>
            <modal-component></modal-component>
            <flash-messages></flash-messages>
            <action-bar></action-bar>

            <div class="tab-header" v-if="form.hasTabs()">
                <template v-for="tab in form.tabs">
                    <form-tab-head v-bind:tab="tab"></form-tab-head>
                </template>
            </div>

            <div class="form-container">
                <form method="POST">
                    <template v-for="tab in form.tabs">
                        <form-tab-container v-show="tab.active" v-bind:tab="tab"></form-tab-container>
                    </template>
                </form>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Vue, Options, Inject } from "vue-property-decorator";
import '@enhavo/app/assets/fonts/enhavo-icons.font'
import '@enhavo/app/assets/styles/base.scss'
import '@enhavo/app/assets/styles/form.scss'
import '@enhavo/app/assets/styles/view.scss';
import Form from "@enhavo/app/form/Form";

@Options({})
export default class extends Vue
{
    @Inject()
    form: Form

    mounted() {
        $(document).on('change', ':input', () => {
            this.form.changeForm();
        });

        $(document).on('focus','[data-field-with-label] input[type="text"],[data-field-with-label] textarea', function() {
            $(this).parents('[data-field-with-label]').addClass('focused');
        }).on('blur','[data-field-with-label] input[type="text"],[data-field-with-label] textarea', function() {
            $(this).parents('[data-field-with-label]').removeClass('focused');
        });

        $(document).on('keyup','[data-field-with-label] input[type="text"],[data-field-with-label] textarea', function() {
            if($(this).val().length > 0) {
                $(this).parents('[data-field-with-label]').addClass('filled');
            } else {
                $(this).parents('[data-field-with-label]').removeClass('filled');
            }
        });
    }
}
</script>


