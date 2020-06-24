import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import RegistryPackage from "@enhavo/app/Form/RegistryPackage";
import CheckboxLoader from "@enhavo/form/Loader/CheckboxLoader";
import SelectLoader from "@enhavo/form/Loader/SelectLoader";
import DateTimeLoader from "@enhavo/form/Loader/DateTimeLoader";
import DateLoader from "@enhavo/form/Loader/DateLoader";
import WysiwygLoader from "@enhavo/form/Loader/WysiwygLoader";
import ListLoader from "@enhavo/form/Loader/ListLoader";
import AutoCompleteLoader from "@enhavo/form/Loader/AutoCompleteLoader";
import WeekendDateLoader from "@enhavo/form/Loader/WeekendDateLoader";
import PolyCollectionLoader from "@enhavo/form/Loader/PolyCollectionLoader";
import PrototypeLoader from "@enhavo/form/Loader/PrototypeLoader";

export default class FormRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface)
    {
        super();
        this.register(new CheckboxLoader());
        this.register(new SelectLoader());
        this.register(new DateTimeLoader());
        this.register(new WeekendDateLoader());
        this.register(new DateLoader());
        this.register(new WysiwygLoader());
        this.register(new ListLoader());
        this.register(new PrototypeLoader());
        this.register(new PolyCollectionLoader());
        this.register(new AutoCompleteLoader(application));
    }
}