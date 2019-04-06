import FormType from "@enhavo/form/FormType";

export default interface LoaderInterface
{
    load(element: HTMLElement, selector: string): FormType[]
}