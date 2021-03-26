import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import RegistryPackage from "@enhavo/app/form/RegistryPackage";
import TranslationLoader from "@enhavo/translation/form/loader/TranslationLoader";

export default class FormRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface)
    {
        super();
        this.register(new TranslationLoader(application));
    }
}