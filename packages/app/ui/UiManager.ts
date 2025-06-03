
export class UiManager
{
    confirmData: Confirm = null;
    alertData: Alert = null;
    loadingData: boolean = false;

    confirm(options: object): Promise<boolean>
    {
        return new Promise((resolve, reject) => {
            this.confirmData = new Confirm(
                options,
                () => {
                    this.confirmData = null;
                    resolve(false);
                },
                () => {
                    this.confirmData = null;
                    resolve(true);
                }
            )
        });
    }

    alert(options: object): Promise<void>
    {
        return new Promise((resolve, reject) => {
            this.alertData = new Alert(
                options,
                () => {
                    this.alertData = null;
                    resolve();
                }
            );
        });
    }

    loading(value: boolean): void
    {
        this.loadingData = value;
    }
}


export class Confirm
{
    public message: string;
    public denyLabel: string;
    public acceptLabel: string;

    constructor(
        options: object,
        private onDeny: () => void,
        private onAccept: () => void,
    )
    {
        Object.assign(this, options);
    }

    public deny()
    {
        if (this.onDeny) {
            this.onDeny();
        }
    }

    public accept()
    {
        if (this.onAccept) {
            this.onAccept();
        }
    }
}

export class Alert
{
    public message: string;
    public acceptLabel: string;
    public onAccept: () => Promise<void> =  async () => {};

    constructor(
        options: object,
        private close: () => void,
    )
    {
        Object.assign(this, options);
    }

    public accept()
    {
        this.onAccept().then(() => {
            this.close();
        });
    }
}
