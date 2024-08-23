import Confirm from "./Confirm";
import Alert from "./Alert";

export class UiManager
{
    confirm: Confirm = null;
    alert: Alert = null;
    loading: boolean = false;

    setConfirm(confirm: Confirm)
    {
        if(confirm == null) {
            this.confirm = null;
        } else if(this.confirm == null) {
            confirm.setView(this);
            this.confirm = confirm;
        }
    }

    setAlert(message: string)
    {
        if (this.alert == null) {
            this.alert = message;
        }
    }
}
