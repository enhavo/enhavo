import FormType from "@enhavo/form/FormType";
import 'select2'
import 'select2/select2.css'

export default class SelectType extends FormType
{
    protected init()
    {
        this.$element.select2();
    }
}