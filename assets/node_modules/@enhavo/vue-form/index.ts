import _Vue, {PluginObject} from "vue";
import FormChoiceComponent from '@enhavo/vue-form/components/FormChoiceComponent.vue';
import FormChoiceExpandedComponent from '@enhavo/vue-form/components/FormChoiceExpandedComponent.vue';
import FormChoiceCollapsedComponent from '@enhavo/vue-form/components/FormChoiceCollapsedComponent.vue';
import FormChoiceOptionComponent from '@enhavo/vue-form/components/FormChoiceOptionComponent.vue';
import FormChoiceOptgroupComponent from '@enhavo/vue-form/components/FormChoiceOptgroupComponent.vue';
import FormErrorsComponent from '@enhavo/vue-form/components/FormErrorsComponent.vue';
import FormFormComponent from '@enhavo/vue-form/components/FormFormComponent.vue';
import FormHelpComponent from '@enhavo/vue-form/components/FormHelpComponent.vue';
import FormHiddenComponent from '@enhavo/vue-form/components/FormHiddenComponent.vue';
import FormLabelComponent from '@enhavo/vue-form/components/FormLabelComponent.vue';
import FormRestComponent from '@enhavo/vue-form/components/FormRestComponent.vue';
import FormRowComponent from '@enhavo/vue-form/components/FormRowComponent.vue';
import FormRowsComponent from '@enhavo/vue-form/components/FormRowsComponent.vue';
import FormTextComponent from '@enhavo/vue-form/components/FormTextComponent.vue';
import FormTextareaComponent from '@enhavo/vue-form/components/FormTextareaComponent.vue';
import FormWidgetComponent from '@enhavo/vue-form/components/FormWidgetComponent.vue';
import FormSimpleComponent from '@enhavo/vue-form/components/FormSimpleComponent.vue';
import FormRadioComponent from '@enhavo/vue-form/components/FormRadioComponent.vue';
import FormCheckboxComponent from '@enhavo/vue-form/components/FormCheckboxComponent.vue';
import FormButtonComponent from '@enhavo/vue-form/components/FormButtonComponent.vue';
import FormSubmitComponent from '@enhavo/vue-form/components/FormSubmitComponent.vue';
import FormButtonRowComponent from '@enhavo/vue-form/components/FormButtonRowComponent.vue';
import FormHiddenRowComponent from '@enhavo/vue-form/components/FormHiddenRowComponent.vue';

class Plugin implements PluginObject<Config>
{
    install(Vue: typeof _Vue, options?: Config): void
    {
        Vue.component('form-widget', FormWidgetComponent);
        Vue.component('form-choice', FormChoiceComponent);
        Vue.component('form-choice-expanded', FormChoiceExpandedComponent);
        Vue.component('form-choice-collapsed', FormChoiceCollapsedComponent);
        Vue.component('form-choice-option', FormChoiceOptionComponent);
        Vue.component('form-choice-optgroup', FormChoiceOptgroupComponent);
        Vue.component('form-errors', FormErrorsComponent);
        Vue.component('form-row', FormRowComponent)
        Vue.component('form-rows', FormRowsComponent)
        Vue.component('form-form', FormFormComponent);
        Vue.component('form-help', FormHelpComponent);
        Vue.component('form-simple', FormSimpleComponent);
        Vue.component('form-hidden', FormHiddenComponent);
        Vue.component('form-label', FormLabelComponent);
        Vue.component('form-rest', FormRestComponent);
        Vue.component('form-text', FormTextComponent);
        Vue.component('form-textarea', FormTextareaComponent);
        Vue.component('form-widget', FormWidgetComponent);
        Vue.component('form-radio', FormRadioComponent);
        Vue.component('form-checkbox', FormCheckboxComponent);
        Vue.component('form-button', FormButtonComponent);
        Vue.component('form-submit', FormSubmitComponent);
        Vue.component('form-button-row', FormButtonRowComponent);
        Vue.component('form-hidden-row', FormHiddenRowComponent);
    }
}

class Config
{
    components: object;
}

export default new Plugin();