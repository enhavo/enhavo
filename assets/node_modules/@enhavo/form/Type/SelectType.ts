import FormType from "@enhavo/form/FormType";
import * as $ from "jquery";
import 'select2'
import 'select2/select2.css'
import 'select2/select2_locale_de.js'
import 'select2/select2_locale_en.js.template'

export default class SelectType extends FormType
{
    protected init()
    {
        let locale = $('html').attr('lang');
        if ($.fn.select2.locales.hasOwnProperty(locale)) {
            $.extend($.fn.select2.defaults, $.fn.select2.locales[locale]);
        }
        this.$element.select2();
    }
}