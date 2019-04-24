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
import AutoCompleteLoader from "@enhavo/form/Loader/AutoCompleteLoader";

export default class FormRegistry
{
    private application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    public static INSERT = 'insert';
    public static RELEASE = 'release';

    addLoader(loader: LoaderInterface): FormRegistry
    {
        FormListener.onInsert((event: FormInsertEvent) => {
            loader.insert(event.getElement());
        });

        FormListener.onRelease((event: FormReleaseEvent) => {
            loader.release(event.getElement());
        });

        FormListener.onMove((event: FormReleaseEvent) => {
            loader.move(event.getElement());
        });

        FormListener.onDrop((event: FormReleaseEvent) => {
            loader.drop(event.getElement());
        });

        return this;
    }

    load() {
        this.addLoader(new CheckboxLoader());
        this.addLoader(new SelectLoader());
        this.addLoader(new DateTimeLoader());
        this.addLoader(new DateLoader());
        this.addLoader(new WysiwygLoader());
        this.addLoader(new ListLoader());
        this.addLoader(new DynamicFormLoader(this.application));
        this.addLoader(new MediaLoader(this.application));
        this.addLoader(new AutoCompleteLoader(this.application));
    }
}