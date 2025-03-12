
export interface ErrorContextInterface
{
    on(code: number, callback: () => Promise<void>): ErrorContextInterface;
    handleError(transport: Transport, options?: Options): Promise<void>;
}


export interface ClientInterface extends ErrorContextInterface
{
    lastRequest: {
        input: RequestInfo | URL,
        init?: RequestInit,
    }

    fetch(input: RequestInfo | URL, init?: RequestInit): Promise<Transport>;

    repeat(): Promise<Transport>;

    addErrorHandler(errorHandler: ErrorHandlerInterface, priority?: number): ClientInterface;
}

export type Options = {
    terminate?: boolean;
    repeatable?: boolean;
    confirm?: boolean;
    validation?: boolean;
}

export interface ErrorHandlerInterface
{
    handleError(transport: Transport, options: Options, client: ClientInterface): Promise<void>;

    supports(transport: Transport, options: Options, client: ClientInterface): boolean;
}

export class Transport
{
    public readonly ok: boolean;

    constructor(
        public readonly response: Response = null,
        public readonly error: Error = null,
    ) {
        this.ok = response !== null;
    }
}
