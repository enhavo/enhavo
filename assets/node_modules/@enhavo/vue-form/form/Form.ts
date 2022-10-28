import {RootFormData} from "@enhavo/vue-form/data/RootFormData";
import {Util} from "@enhavo/vue-form/form/Util";
import axios, {AxiosPromise,Method} from "axios";

export class Form extends RootFormData
{
    public element: HTMLFormElement;

    public serializeForm()
    {
        return Util.serializeForm(this.element);
    }

    public submitAsync(): AxiosPromise
    {
        return axios({
            method: <Method>this.element.method,
            url: this.element.action,
            data: this.serializeForm(),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }

    public submit()
    {
        this.element.submit();
    }
}
