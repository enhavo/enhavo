import {Form} from "@enhavo/vue-form/model/Form";
import {FileUrlResolverInterface} from "@enhavo/media/resolver/FileUrlResolverInterface";
import {PrivateFile} from '@enhavo/media/model/PrivateFile';

export class MediaItemForm extends Form
{
    public file: PrivateFile;

    constructor(
        private fileResolver: FileUrlResolverInterface
    ) {
        super();
    }

    path(format: string = null)
    {
        return this.fileResolver.resolve(this.file, format);
    }
}
