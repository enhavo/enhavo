import FormType from "@enhavo/app/form/FormType";
import 'jquery-datetimepicker'
import 'jquery-datetimepicker/build/jquery.datetimepicker.min.css'
import * as $ from "jquery";

export default class DateType extends FormType
{
    protected init()
    {
        let options = {
            'format': 'd.m.Y',
            'timepicker': false,
            'dayOfWeekStart': 1,
            'scrollInput': false
        };

        let configType = this.$element.data('date-picker');
        $(document).trigger('datePickerInit', {
            configType: configType,
            options: options
        });

        $.datetimepicker.setLocale('de');
        this.$element.datetimepicker(options);

        this.$element.parent().find('[data-clear-date-button]').on('click', () => {
            this.$element.val('');
        });
    }
}