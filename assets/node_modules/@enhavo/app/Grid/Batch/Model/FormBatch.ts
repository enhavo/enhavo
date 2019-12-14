import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import UrlBatch from "@enhavo/app/Grid/Batch/Model/UrlBatch";
import AjaxFormModal from "@enhavo/app/Modal/Model/AjaxFormModal";
import Message from "@enhavo/app/FlashMessage/Message";
import {IndexApplication} from "@enhavo/app/Index/IndexApplication";

export default class FormBatch extends UrlBatch
{
    public application: ApplicationInterface;
    public modal: any;

    async execute(ids: number[]): Promise<boolean>
    {
        this.modal.data = {
            ids: ids,
            type: this.key
        };
        this.modal.actionUrl = this.getUrl();
        this.modal.actionHandler = (modal: AjaxFormModal, data: any, error: string) => {
            return new Promise((resolve, reject) => {
                if(data.status == 400) {
                    this.application.getFlashMessenger().addMessage(new Message(Message.ERROR, data.data));
                    resolve(true);
                    return;
                } else if(error) {
                    this.application.getFlashMessenger().addMessage(new Message(Message.ERROR, this.application.getTranslator().trans(error)));
                    resolve(true);
                    return;
                }

                this.application.getFlashMessenger().addMessage(new Message(Message.SUCCESS, this.application.getTranslator().trans('enhavo_app.batch.message.success')));
                (<IndexApplication>this.application).getGrid().loadTable();
                resolve(true);
            })
        };
        this.application.getModalManager().push(this.modal);
        return false;
    }
}