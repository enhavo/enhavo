import FormType from "@enhavo/app/form/FormType";
import 'jquery-datetimepicker'
import 'jquery-datetimepicker/build/jquery.datetimepicker.min.css'

export default class WeekendDateType extends FormType
{
    protected init()
    {
        $.datetimepicker.setLocale('de');
        this.$element.datetimepicker({
            format:'d.m.Y',
            timepicker: false,
            dayOfWeekStart: 6,
            disabledWeekDays: [0, 1, 2, 3, 4, 5],
            weeks: true,
            scrollInput: false
        }).attr('readonly', true);
    }
}
