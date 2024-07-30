import { AbstractModal } from "@enhavo/app/modal/model/AbstractModal";

export class OutputStreamModal extends AbstractModal
{
    public url: string;
    public closeLabel: string;
    public output: string = "";
    public done: boolean = false;

    init()
    {
        fetch(this.url)
            .then(response => response.body)
            .then(body => {
                const reader = body.getReader();
                const processText = ({ done, value }) => {
                    const modal = this.modalManager.modals[this.modalManager.modals.length-1];

                    if(value) {
                        modal.output += new TextDecoder("utf-8").decode(value);
                    }

                    if(!done) {
                        modal.done = true;
                        return reader.read().then(processText);
                    }
                };
                reader.read().then(processText);
            });
    }
}
