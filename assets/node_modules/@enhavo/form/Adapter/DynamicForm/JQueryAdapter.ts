import * as jQuery from 'jquery'
import { DynamicForm } from "app/DynamicForm";
import { FormListener } from "app/Form/Form";
import { FormInsertEvent } from "app/Form/Form";

(function ($) {
    $.fn.dynamicForm = function ()
    {
        DynamicForm.apply(this);

        let listener = new FormListener();
        listener.onInsert(function(event: FormInsertEvent) {
            DynamicForm.apply(event.getElement());
        });
    }
})(jQuery);

export default jQuery;