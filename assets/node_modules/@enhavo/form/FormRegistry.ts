import FormListener from "@enhavo/form/FormListener";
import FormInsertEvent from "@enhavo/form/Event/FormInsertEvent";
import LoaderInterface from "@enhavo/form/LoaderInterface";
import CheckboxLoader from "@enhavo/form/Loader/CheckboxLoader";
import FormReleaseEvent from "@enhavo/form/Event/FormReleaseEvent";
import SelectLoader from "@enhavo/form/Loader/SelectLoader";
import DateTimeLoader from "@enhavo/form/Loader/DateTimeLoader";
import DateLoader from "@enhavo/form/Loader/DateLoader";
import WysiwygLoader from "@enhavo/form/Loader/WysiwygLoader";
import ListLoader from "@enhavo/form/Loader/ListLoader";
import DynamicFormLoader from "@enhavo/form/Loader/DynamicFormLoader";
import MediaLoader from "@enhavo/media/Loader/MediaLoader";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default class FormRegistry
{
    private application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    public static INSERT = 'insert';
    public static RELEASE = 'release';

    addType(loader: LoaderInterface, selector: string, on: string = FormRegistry.INSERT)
    {
        if (on == FormRegistry.INSERT) {
            FormListener.onInsert((event: FormInsertEvent) => {
                loader.load(event.getElement(), selector);
            });
        } else if (on == FormRegistry.RELEASE) {
            FormListener.onRelease((event: FormReleaseEvent) => {
                loader.load(event.getElement(), selector);
            });
        }
    }

    load() {
        this.addType(new CheckboxLoader(), 'input[type=radio],input[type=checkbox]', FormRegistry.INSERT);
        this.addType(new SelectLoader(), 'select', FormRegistry.INSERT);
        this.addType(new DateTimeLoader(), '[data-date-time-picker]', FormRegistry.INSERT);
        this.addType(new DateLoader(), '[data-date-picker]', FormRegistry.INSERT);
        this.addType(new WysiwygLoader(), '[data-wysiwyg]', FormRegistry.RELEASE);
        this.addType(new ListLoader(), '[data-list]', FormRegistry.INSERT);
        this.addType(new DynamicFormLoader(this.application), '[data-dynamic-form]', FormRegistry.INSERT);
        this.addType(new MediaLoader(this.application), '[data-media-type]', FormRegistry.INSERT);
    }
}