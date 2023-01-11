import {GenericEvent} from "@enhavo/vue-form/event/GenericEvent";

export class DeleteEvent extends GenericEvent
{
    private stopFlag = false;

    public stop()
    {
        this.stopFlag = true;
    }

    public continue()
    {
        this.stopFlag = false;
    }

    public isStopped()
    {
        return this.stopFlag;
    }
}