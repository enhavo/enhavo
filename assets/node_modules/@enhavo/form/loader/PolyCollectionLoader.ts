import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import PolyCollectionType from "@enhavo/form/Type/PolyCollectionType";
import PolyCollectionConfig from "@enhavo/form/Type/PolyCollectionConfig";
import * as $ from "jquery";
import prototypeManager from "@enhavo/form/Prototype/PrototypeManager";
import FormRegistry from "@enhavo/app/Form/FormRegistry";

export default class PolyCollectionLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-poly-collection]');
        for(element of elements) {
            let config = <PolyCollectionConfig>$(element).data('poly-collection-config');
            FormRegistry.registerType(new PolyCollectionType(element, config, prototypeManager));
        }
    }
}
