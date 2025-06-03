import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {MediaItemForm} from "@enhavo/media/form/model/MediaItemForm";
import {ActionMediaItemInterface} from "@enhavo/media/action/ActionMediaItemInterface";
import {FileUrlResolverInterface} from "@enhavo/media/resolver/FileUrlResolverInterface";

export class MediaDownloadAction extends AbstractAction implements ActionMediaItemInterface
{
    form: MediaItemForm;

    constructor(
        private fileResolver: FileUrlResolverInterface,
    ) {
        super();
    }

    execute(): void
    {
        const uri = this.fileResolver.resolve(this.form.file, null, true);
        let url = new URL(uri);
        let params = new URLSearchParams(url.search);
        params.append("disposition", 'download');

        const link = document.createElement("a");
        link.setAttribute('download', 'download');
        link.href = url.toString();
        document.body.appendChild(link);
        link.click();
        link.remove();
    }
}
