import 'icheck'
import FormType from "@enhavo/app/form/FormType";

export default class CheckboxType extends FormType
{
    protected init()
    {
        let iCheck = this.$element.iCheck({
            checkboxClass: 'icheckbox',
            radioClass: 'icheckbox'
        });

        let $formRow = this.$element.closest('[data-form-row]');
        let $count = $formRow.find('[data-selected-count]');

        if ($count.length) { // if there is a data-selected-count element, then multiple must have been true
            iCheck.on('ifChanged', (event: any) => {
                let checked = $formRow.find('input:checked');
                let count = checked.length;
                $count.text('(' + count + ')');
            });
        }

        if (this.$element.attr('readonly')) {
            this.$element.closest('.icheckbox').addClass('readonly');
        }
    }
}
