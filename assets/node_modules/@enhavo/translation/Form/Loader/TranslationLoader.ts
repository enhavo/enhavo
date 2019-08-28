import TranslationType from "@enhavo/translation/Form/Type/TranslationType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import "@enhavo/translation/assets/styles/style.scss";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default class TranslationLoader extends AbstractLoader
{
    private application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        super();
        this.application = application;
    }

    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-translation-type]');
        for(element of elements) {
            new TranslationType(element);
        }
    }
}