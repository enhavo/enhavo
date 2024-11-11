import TranslationType from "@enhavo/translation/form/type/TranslationType";
import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import "@enhavo/translation/assets/styles/style.scss";
import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";

export default class TranslationLoader extends AbstractLoader
{
    private readonly eventDispatcher: FrameEventDispatcher;

    constructor(eventDispatcher: FrameEventDispatcher)
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