import FormType from "@enhavo/app/form/FormType";
import * as $ from "jquery";
import 'select2'
import 'select2/select2_locale_de.js'
import 'select2/select2_locale_en.js.template'
import Sortable from 'sortablejs';

export default class SelectType extends FormType
{
    protected init()
    {
        let locale = $('html').attr('lang');
        if ($.fn.select2.locales.hasOwnProperty(locale)) {
            $.extend($.fn.select2.defaults, $.fn.select2.locales[locale]);
        }

        let data = this.$element.data('select2-options');
        this.$element.select2();

        let $count = this.$element.closest('[data-form-row]').find('[data-selected-count]');
        if (this.$element.attr('multiple') && $count.length) {
            this.$element.on("change", (event: Select2JQueryEventObject) => {
                let count = event.val.length;
                $count.text('(' + count + ')');
            });
        }

        let $list = this.$element.parent().find('ul.select2-choices');

        if($list.get(0) && data && data.sortable && data.multiple) {
            let listElement = <HTMLElement>$list.get(0);
            Sortable.create(listElement, {
                draggable: ".select2-search-choice",
                animation: 150,
                onUpdate: () => {
                    let data = <Array<any>>this.$element.select2('data');
                    let list: Array<string> = [];
                    for(let element of data) {
                        list.push(element.id);
                    }
                    this.$element.children().each((index, element) => {
                        let value = $(element).val();
                        let newIndex = list.indexOf(value);
                        let beforeElement = this.$element.children().get(newIndex);
                        if(beforeElement != element) {
                            $(beforeElement).after(element);
                        }
                    });
                },
                onEnd: () => {
                    $list.find('.select2-search-field').appendTo($list.get(0));
                },
            });
        }
    }
}
