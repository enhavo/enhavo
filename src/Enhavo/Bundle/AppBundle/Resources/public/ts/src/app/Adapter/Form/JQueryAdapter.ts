import * as jQuery from 'jquery'
import { FormListener } from "app/Form/Form";
import { FormInitializer } from "app/Form/Form";
import { FormInsertEvent } from "app/Form/Form";
import { DatePickerType } from "app/Form/FormType";
import { DateTimePickerType } from "app/Form/FormType";
import { CheckboxType } from "app/Form/FormType";
import { SelectType } from "app/Form/FormType";
import { WysiwygType } from "app/Form/FormType";

(function ($) {
    let listener = new FormListener();

    listener.onInsert(function(event: FormInsertEvent) {
        DatePickerType.apply(event.getElement());
        DateTimePickerType.apply(event.getElement());
        CheckboxType.apply(event.getElement());
        SelectType.apply(event.getElement());
    });

    listener.onRelease(function(event: FormInsertEvent) {
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