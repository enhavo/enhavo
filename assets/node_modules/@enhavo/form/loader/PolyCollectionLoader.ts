import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import PolyCollectionType from "@enhavo/form/type/PolyCollectionType";
import PolyCollectionConfig from "@enhavo/form/type/PolyCollectionConfig";
import $ from "jquery";
import prototypeManager from "@enhavo/form/prototype/PrototypeManager";
import FormRegistry from "@enhavo/app/form/FormRegistry";
import View from "@enhavo/app/view/View";
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
