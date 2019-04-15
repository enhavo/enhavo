import 'select2'
import 'select2/select2.css'
import FormType from "@enhavo/form/FormType";
import AutoCompleteConfig from "@enhavo/form/Type/AutoCompleteConfig";

export default class AutoCompleteType extends FormType
{
    private config: AutoCompleteConfig;

    constructor(element: HTMLElement, config: AutoCompleteConfig)
    {
        super(element);
        this.config = config;
    }

    protected init()
    {
        let data = this.$element.data('auto-complete-entity');
        let config: Select2Options = {
            minimumInputLength: data.minimum_input_length,
            ajax: {
                url: data.url,
                delay: 500,
                data: function (searchTerm: Select2QueryOptions, page: number) {
                    return {
                        q: searchTerm,
                        page: page || 1
                    };
                },
                processResults: function (data: any) {
                    return data;
                },
                cache: true
            }
        };

        if(data.multiple) {
            config.tags = true;
        }

        if(data.placeholder) {
            config.placeholder = data.placeholder;
            config.allowClear = true
        }

        let $input = this.$element.find('[data-auto-complete-input]');
        $input.select2(config);
        $input.select2('data', data.value);

        this.$element.find('[data-auto-complete-create]').click((event) => {
            let url = $(event.target).attr('href');
            if(this.config.create) {
                this.config.create(this, url);
            }
        });
    }
}