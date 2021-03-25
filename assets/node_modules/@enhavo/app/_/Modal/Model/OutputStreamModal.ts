import AbstractModal from "@enhavo/app/Modal/Model/AbstractModal";

export default class OutputStreamModal extends AbstractModal
{
    public url: string;
    public closeLabel: string;
    public output: string = "";
    public done: boolean = false;

    init() {
        let modal = this;
        fetch(this.url)
            .then(response => response.body)
            .then(body => {
                const reader = body.getReader();
                reader.read().then(function processText({ done, value }): any {
                    if(value) {
                        modal.output += new TextDecoder("utf-8").decode(value);
                    }

                    if(!done) {
                        modal.done = true;
                        return reader.read().then(processText);
                    }
                })
            });
    }
}