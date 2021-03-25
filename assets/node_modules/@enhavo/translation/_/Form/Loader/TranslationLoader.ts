import TranslationType from "@enhavo/translation/Form/Type/TranslationType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import "@enhavo/translation/assets/styles/style.scss";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export default class TranslationLoader extends AbstractLoader
{
    private readonly eventDispatcher: EventDispatcher;

    constructor(eventDispatcher: EventDispatcher)
    {
        super();
        this.eventDispatcher = eventDispatcher;
    }

    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-translation-type]');
        for(element of elements) {
            new TranslationType(element, this.eventDispatcher);
        }
    }
}