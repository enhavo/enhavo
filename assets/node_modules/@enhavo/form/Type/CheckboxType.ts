import 'icheck'
import FormType from "@enhavo/app/Form/FormType";

export default class CheckboxType extends FormType
{
    protected init()
    {
        this.$element.iCheck({
            checkboxClass: 'icheckbox',
            radioClass: 'icheckbox'
        })
    }
}