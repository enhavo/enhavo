import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Router} from "@enhavo/app/routing/Router";
import {MediaItemForm} from "@enhavo/media/form/model/MediaItemForm";
import {ActionMediaItemInterface} from "@enhavo/media/action/ActionMediaItemInterface";

export class MediaFormatAction extends AbstractAction implements ActionMediaItemInterface
{
    public form: MediaItemForm;
    public formats: string[];
    public open: boolean = false;

    constructor(
        private frameManager: FrameManager,
        private router: Router,
    ) {
        super();
    }

    execute(): void
    {
        this.open = !this.open;
    }

    getFormats(): Format[]
    {
        let formats = [];
        for (const [key, value] of Object.entries(this.form.formats)) {
            formats.push(new Format(value, key))
        }
        return formats;
    }

    hasFormats(): boolean
    {
        return Object.keys(this.form.formats).length > 0;
    }

    openFormat(key: string, label: string)
    {
        let url = this.router.generate('enhavo_media_admin_image_cropper', {
            format: key,
            token: this.form.file.token,
        })
        this.frameManager.openFrame({
            url: url,
            key: 'image_cropper',
            label: label
        })
    }
}

export class Format
{
    constructor(
        public label: string,
        public key: string
    ) {
    }
}
