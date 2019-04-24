import 'select2'
import 'select2/select2.css'
import FormType from "@enhavo/form/FormType";
import AutoCompleteConfig from "@enhavo/form/Type/AutoCompleteConfig";

export default class AutoCompleteType extends FormType
{
    private config: AutoCompleteConfig;
    private idProperty: string;
    private labelProperty: string;

    constructor(element: HTMLElement, config: AutoCompleteConfig)
    {
        super(element);
        this.config = config;
    }

    protected init()
    {
        let data = this.$element.data('auto-complete-entity');
        this.idProperty = data.id_property;
        this.labelProperty = data.label_property;
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
                results: function (data: any, page: any) {
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
            event.preventDefault();
            let url = $(event.target).attr('href');
            if(this.config.create) {
                this.config.create(this, url);
            }
        });
    }

    public addElement(data: any)
    {
        if(this.idProperty && this.labelProperty) {
            let id = data[this.idProperty];
            let label = data[this.labelProperty];
            let $input = this.$element.find('[data-auto-complete-input]');
            let currentData = $input.select2('data');
            let exists = false;
            for(let item of currentData) {
                if(item.id == id) {
                    exists = true;
                    item.text = label;
                    break;
                }
            }
            currentData.push({
                id: id,
                text: label
            });
            $input.select2('data', currentData);
        }
    }
}