import axios, {AxiosPromise, Method} from "axios";
import {FormVisitorInterface} from "@enhavo/vue-form/form/FormVisitor";
import {Form} from "@enhavo/vue-form/model/Form";

export class RootForm extends Form
{
    method: string = null;
    action: string = null;
    visitors: FormVisitorInterface[] = [];

    public serializeForm()
    {
        return new FormData(<HTMLFormElement>this.element);
    }

    public submitAsync(): AxiosPromise
    {
        return axios({
            method: <Method>this.getElement().method,
            url: this.getElement().action,
            data: this.serializeForm(),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }

    private getElement(): HTMLFormElement
    {
        return <HTMLFormElement>this.element;
    }

    public submit()
    {
        this.getElement().submit();
    }
}
