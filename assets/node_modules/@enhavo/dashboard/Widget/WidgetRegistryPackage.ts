import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import NumberWidgetFactory from "@enhavo/dashboard/Widget/Factory/NumberWidgetFactory";

export default class WidgetRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('number-widget', () => import('@enhavo/dashboard/Widget/Components/NumberWidgetComponent.vue'), new NumberWidgetFactory(application));
    }
}