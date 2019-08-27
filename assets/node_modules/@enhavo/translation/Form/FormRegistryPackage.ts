import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import RegistryPackage from "@enhavo/app/Form/RegistryPackage";
import TranslationLoader from "@enhavo/translation/Form/Loader/TranslationLoader";

export default class FormRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface)
    {
        super();
        this.register(new TranslationLoader(application));
    }
}