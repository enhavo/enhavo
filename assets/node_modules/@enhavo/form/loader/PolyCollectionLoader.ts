import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import PolyCollectionType from "@enhavo/form/type/PolyCollectionType";
import PolyCollectionConfig from "@enhavo/form/type/PolyCollectionConfig";
import * as $ from "jquery";
import prototypeManager from "@enhavo/form/prototype/PrototypeManager";
import FormRegistry from "@enhavo/app/form/FormRegistry";

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
