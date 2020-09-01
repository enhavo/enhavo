import 'select2'
import FormType from "@enhavo/app/Form/FormType";
import AutoCompleteConfig from "@enhavo/form/Type/AutoCompleteConfig";
import Sortable from 'sortablejs';

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
            initSelection: ($element: JQuery) => {
                if(!data.sortable) {
                    return;
                }
                let $list = $element.parent().find('ul.select2-choices');
                let listElement = <HTMLElement>$list.get(0);
                Sortable.create(listElement, {
                    draggable: ".select2-search-choice",
                    animation: 150,
                    onUpdate: () => {
                        let data = <Array<any>>$element.select2('data');
                        let list = [];
                        for(let element of data) {
                            list.push(element.id);
                        }
                        $element.val(list.join(','));
                    },
                    onEnd: () => {
                        $list.find('.select2-search-field').appendTo($list.get(0));
                    },
                });
            },
            formatSelection: function (state:any) {
                if(data.multiple && data.editable){
                    return "<span class=\"icon icon-edit\" data-auto-complete-edit=\"" + state.id + "\"></span> " +state.text;
                } else {
                    return state.text;
                }
            },
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
                cache: true,
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

        if (data.multiple && data.count) {
            $input.on("change", (event: Select2JQueryEventObject) => {
                this.updateCount();
            });
        }

        if(data.multiple && data.editable){
            $input.on("change", (event: Select2JQueryEventObject) => {
                this.initEditFields(data.edit_route);
            });
            this.initEditFields(data.edit_route);
        }

        let $elements = this.$element.find('[data-auto-complete-action]');
        $elements.click((event) => {
            event.preventDefault();
            let url = $(event.target).closest('[data-auto-complete-action]').attr('href');
            if(this.config.executeAction) {
                this.config.executeAction(this, url);
            }
        });
    }

    private initEditFields(route:string)
    {
        let $items = this.$element.find('[data-auto-complete-edit]');

        let self = this;
        $items.each(function() {
            if(typeof $(this).data('initialized') == "undefined"){
                $(this).data('initialized', 1);
                $(this).click((event:any) => {
                    event.preventDefault();
                    let id = $(event.target).data('auto-complete-edit');
                    if(self.config.edit) {
                        self.config.edit(self, route, id.toString());
                    }
                });
            }
        })
    }

    public addElement(data: any)
    {
        if(this.idProperty && this.labelProperty) {
            let id = data[this.idProperty];
            let label = data[this.labelProperty];
            let $input = this.$element.find('[data-auto-complete-input]');
            let formTypeConfiguration = this.$element.data('auto-complete-entity');
            if (formTypeConfiguration.multiple) {
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
            } else {
                $input.select2('data', {
                    id: id,
                    text: label
                });
            }

            let config = this.$element.data('auto-complete-entity');
            if (config.multiple && config.count) {
                this.updateCount();
            }
        }
    }

    private updateCount()
    {
        let $input = this.$element.find('[data-auto-complete-input]');
        let count = $input.select2('data').length;
        let $count = $input.parents('[data-form-row]').find('[data-selected-count]');
        $count.text('(' + count + ')');
    }
}
