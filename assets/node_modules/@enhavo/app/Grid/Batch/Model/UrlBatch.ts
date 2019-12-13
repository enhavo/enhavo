import AbstractBatch from "@enhavo/app/Grid/Batch/Model/AbstractBatch";
import Confirm from "@enhavo/app/View/Confirm";
import axios from "axios";
import * as _ from "lodash";
import Message from "@enhavo/app/FlashMessage/Message";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import BatchDataInterface from "@enhavo/app/Grid/Batch/BatchDataInterface";

export default class UrlBatch extends AbstractBatch
{
    public route: string;
    public routeParameters: object;
    public batchData: BatchDataInterface;
    public application: ApplicationInterface;
    public confirmMessage: string;

    public constructor(application: ApplicationInterface, batchData: BatchDataInterface) {
        super();
        this.application = application;
        this.batchData = batchData;
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
                    this.application.getView().loading();
                    axios.post(uri, data)
                        .then((response) => {
                            this.getView().loaded();
                            this.application.getFlashMessenger().addMessage(new Message(
                                'success',
                                this.application.getTranslator().trans('enhavo_app.batch.message.success')
                            ));
                            resolve(true);
                        })
                        .catch((error) => {
                            this.getView().loaded();
                            this.application.getFlashMessenger().addMessage(new Message(
                                'error',
                                this.application.getTranslator().trans('enhavo_app.batch.message.error')
                            ));
                            reject();
                        })
                },
                () => {},
                this.application.getTranslator().trans('enhavo_app.view.label.cancel'),
                this.application.getTranslator().trans('enhavo_app.view.label.ok'),
            ))
        });
    }

    private getView()
    {
        return this.application.getView();
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

        return this.application.getRouter().generate(route, parameters);
    }
}