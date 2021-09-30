import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import PolyCollectionType from "@enhavo/form/Type/PolyCollectionType";
import PolyCollectionConfig from "@enhavo/form/Type/PolyCollectionConfig";
import * as $ from "jquery";
import prototypeManager from "@enhavo/form/Prototype/PrototypeManager";
import FormRegistry from "@enhavo/app/Form/FormRegistry";
import View from "@enhavo/app/View/View";
import Translator from "@enhavo/core/Translator";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";

export default class PolyCollectionLoader extends AbstractLoader
{
    private view: View;
    private translator: Translator;
    private eventDispatcher: EventDispatcher;

    constructor(view: View, translator: Translator, eventDispatcher: EventDispatcher) {
        super();
        this.view = view;
        this.translator = translator;
        this.eventDispatcher = eventDispatcher;
    }

    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-poly-collection]');
        for(element of elements) {
            let config = <PolyCollectionConfig>$(element).data('poly-collection-config');
            FormRegistry.registerType(new PolyCollectionType(element, config, prototypeManager, this.view, this.translator, this.eventDispatcher));
        }
    }
}
