import {Form} from "@enhavo/vue-form/form/Form";

export interface FormFactoryInterface
{
    createForm(data: object): Form;
}