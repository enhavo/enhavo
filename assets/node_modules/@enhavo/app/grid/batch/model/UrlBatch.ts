import AbstractBatch from "@enhavo/app/grid/batch/model/AbstractBatch";
import Confirm from "@enhavo/app/view/Confirm";
import axios from "axios";
import * as _ from "lodash";
import Message from "@enhavo/app/flash-message/Message";
import BatchDataInterface from "@enhavo/app/grid/batch/BatchDataInterface";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import Router from "@enhavo/core/Router";
import Translator from "@enhavo/core/Translator";
import View from "@enhavo/app/view/View";

export default class UrlBatch extends AbstractBatch
{
    public route: string;
    public routeParameters: object;
    public confirmMessage: string;

    protected readonly batchData: BatchDataInterface;
    protected readonly translator: Translator;
    protected readonly view: View;
    protected readonly flashMessenger: FlashMessenger;
    protected readonly router: Router;

    public constructor(
        batchData: BatchDataInterface,
        translator: Translator,
        view: View,
        flashMessenger: FlashMessenger,
        router: Router,
    ) {
        super();
        this.batchData = batchData;
        this.translator = translator;
        this.view = view;
        this.flashMessenger = flashMessenger;
        this.router = router;
    }

    async execute(ids: number[]): Promise<boolean>
    {
        let uri = this.getUrl();

        let data = {
            type: this.key,
            ids: ids
        };

        return new Promise((resolve, reject) => {
            this.getView().confirm(new Confirm(
                this.confirmMessage,
                () => {
                    this.view.loading();
                    axios.post(uri, data)
                        .then((response) => {
                            this.getView().loaded();
                            this.flashMessenger.addMessage(new Message(
                                'success',
                                this.translator.trans('enhavo_app.batch.message.success')
                            ));
                            resolve(true);
                        })
                        .catch((error) => {
                            this.getView().loaded();
                            this.flashMessenger.addMessage(new Message(
                                'error',
                                this.translator.trans('enhavo_app.batch.message.error')
                            ));
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

    protected getUrl()
    {
        let route = this.route;
        if(!route) {
            route = this.batchData.batchRoute;
        }

        let parameters = this.routeParameters;
        if(!parameters) {
            parameters = {};
        }

        if(this.batchData.batchRouteParameters) {
            parameters = _.extend(parameters, this.batchData.batchRouteParameters);
        }

        return this.router.generate(route, parameters);
    }
}
