import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import RegistryPackage from "@enhavo/app/Form/RegistryPackage";
import CheckboxLoader from "@enhavo/form/Loader/CheckboxLoader";
import SelectLoader from "@enhavo/form/Loader/SelectLoader";
import DateTimeLoader from "@enhavo/form/Loader/DateTimeLoader";
import DateLoader from "@enhavo/form/Loader/DateLoader";
import WysiwygLoader from "@enhavo/form/Loader/WysiwygLoader";
import ListLoader from "@enhavo/form/Loader/ListLoader";
import DynamicFormLoader from "@enhavo/form/Loader/DynamicFormLoader";
import AutoCompleteLoader from "@enhavo/form/Loader/AutoCompleteLoader";

export default class FormRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface)
    {
        super();
        this.register(new CheckboxLoader());
        this.register(new SelectLoader());
        this.register(new DateTimeLoader());
        this.register(new DateLoader());
        this.register(new WysiwygLoader());
        this.register(new ListLoader());
        this.register(new DynamicFormLoader(application));
        this.register(new AutoCompleteLoader(application));
    }
}