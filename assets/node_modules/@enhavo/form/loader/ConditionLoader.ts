import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import ConditionObserver from "@enhavo/form/type/ConditionObserver";
import ConditionType from "@enhavo/form/type/ConditionType";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class ConditionLoader extends AbstractLoader
{
    public release(element: HTMLElement): void
    {
        let conditionTypeElements = this.findElements(element, '[data-condition-type]');
        for(let conditionTypeElement of conditionTypeElements) {
            FormRegistry.registerType(new ConditionType(conditionTypeElement));
        }
        let conditionObserverElements = this.findElements(element, '[data-condition-type-observer]');
        for(let conditionObserverElement of conditionObserverElements) {
            new ConditionObserver(conditionObserverElement);
        }
        ConditionObserver.registerAll();
    }
}
