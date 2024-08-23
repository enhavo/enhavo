import select2 from "select2";
import $ from 'jquery';
import FormType from "@enhavo/app/form/FormType";
import Sortable from 'sortablejs';
import UpdatedEvent from "@enhavo/app/frame/event/UpdatedEvent";
import LoadDataEvent from "@enhavo/app/frame/event/LoadDataEvent";
import DataStorageEntry from "@enhavo/app/frame/DataStorageEntry";
import EventDispatcher from "@enhavo/app/frame/EventDispatcher";
import Router from "@enhavo/core/Router";
import View from "@enhavo/app/view/View";

select2($);

export default class AutoCompleteType extends FormType
{
    private idProperty: string;
    private labelProperty: string;
    private viewKey: string;
    private eventDispatcher: EventDispatcher;
    private router: Router;
    private view: View;

    constructor(element: HTMLElement, eventDispatcher: EventDispatcher, router: Router, view: View)
    {
        super(element);
        this.eventDispatcher = eventDispatcher;
        this.router = router;
        this.view = view;
        //this.initListener(this.viewKey);
    }

    private initListener(viewKey: string)
    {
        this.eventDispatcher.on('updated', (event: UpdatedEvent) => {
            this.eventDispatcher.dispatch(new LoadDataEvent(viewKey))
                .then((data: DataStorageEntry) => {
                    if(data) {
                        if(event.id == data.value && event.data != null) {
                            this.addElement(event.data)
                        }
                    }
                });
        });
    }

    protected init()
    {
        return;
        let data = this.$element.data('auto-complete-entity');
        this.idProperty = data.id_property;
        this.labelProperty = data.label_property;
        this.viewKey = data.view_key;

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

        if(data.multiple && data.editable) {
            $input.on("change", (event: Select2JQueryEventObject) => {
                this.initEditFields(data.edit_route);
            });
            this.initEditFields(data.edit_route);
        }
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
                    // open edit view
                    let url = self.router.generate(route, {id: id});
                    self.view.open(url, self.viewKey);
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
