import {AbstractBatch} from "@enhavo/app/batch/model/AbstractBatch";
import axios from "axios";
import { FlashMessenger, FlashMessage } from "@enhavo/app/flash-message/FlashMessenger";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/view/View";

export class UrlBatch extends AbstractBatch
{
    public url: string;
    public confirmMessage: string;

    public constructor(
        protected readonly translator: Translator,
        protected readonly view: View,
        protected readonly flashMessenger: FlashMessenger,
    ) {
        super();

    }

    async execute(ids: number[]): Promise<boolean>
    {
        let data = {
            type: this.key,
            ids: ids
        };

        return new Promise((resolve, reject) => {
            this.getView().confirm(new Confirm(
                this.confirmMessage,
                () => {
                    this.view.loading();
                    axios.post(this.url, data)
                        .then((response) => {
                            this.getView().loaded();
                            this.flashMessenger.add(this.translator.trans('enhavo_app.batch.message.success'));
                            resolve(true);
                        })
                        .catch((error) => {
                            this.getView().loaded();
                            this.flashMessenger.add(this.translator.trans('enhavo_app.batch.message.error'), FlashMessage.ERROR);
                            reject();
                        })
                },
                () => {},
                this.translator.trans('enhavo_app.view.label.cancel'),
                this.translator.trans('enhavo_app.view.label.ok'),
            ))
        });
    }

    private getView()
    {
        return this.view;
    }
}
