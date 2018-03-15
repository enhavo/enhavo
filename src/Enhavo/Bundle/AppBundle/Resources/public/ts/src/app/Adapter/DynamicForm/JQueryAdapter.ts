import * as jQuery from 'jquery'
import { DynamicForm } from "app/app/DynamicForm";

(function ($) {
    $.fn.dynamicForm = function ()
    {
        DynamicForm.apply(this);
    }
})(jQuery);

export default jQuery;