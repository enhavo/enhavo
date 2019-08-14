import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import RegistryPackage from "@enhavo/app/Form/RegistryPackage";
import MediaLoader from "@enhavo/media/Loader/MediaLoader";

export default class FormRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface)
    {
        super();
        this.register(new MediaLoader(application));
    }
}