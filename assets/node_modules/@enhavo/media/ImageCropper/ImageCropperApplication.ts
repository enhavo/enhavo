import ImageCropperApp from "@enhavo/media/ImageCropper/ImageCropperApp";

import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";

export class ImageCropperApplication extends AbstractApplication implements ActionAwareApplication
{
    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new ImageCropperApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager(), this.getFlashMessenger());
        }
        return this.app;
    }
}

let application = new ImageCropperApplication();
export default application;