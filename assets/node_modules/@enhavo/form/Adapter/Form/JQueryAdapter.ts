import * as jQuery from 'jquery'
import { FormInsertEvent, FormInitializer, FormListener } from "app/Form/Form";
import { DatePickerType, ListType, DateTimePickerType, CheckboxType, SelectType, WysiwygType } from "app/Form/FormType";

(function ($) {

    FormListener.onInsert(function(event: FormInsertEvent) {
        DatePickerType.apply(event.getElement());
        DateTimePickerType.apply(event.getElement());
        CheckboxType.apply(event.getElement());
        SelectType.apply(event.getElement());
        ListType.apply(event.getElement());
    });

    FormListener.onRelease(function(event: FormInsertEvent) {
        WysiwygType.apply(event.getElement());
    });

    $.fn.form = function ()
    {
        let formInitializer = new FormInitializer();
        formInitializer.setElement(this);
        formInitializer.init();

    }
})(jQuery);

export default jQuery;