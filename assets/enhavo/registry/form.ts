import RegistryPackage from "@enhavo/app/Form/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import FormFormRegistryPackage from "@enhavo/form/FormRegistryPackage";
import MediaFormRegistryPackage from "@enhavo/media/FormRegistryPackage";
import TranslationFormRegistryPackage from "@enhavo/translation/Form/FormRegistryPackage";

export default class FormRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.registerPackage(new FormFormRegistryPackage(application));
        this.registerPackage(new MediaFormRegistryPackage(application));
        this.registerPackage(new TranslationFormRegistryPackage(application));
    }
}