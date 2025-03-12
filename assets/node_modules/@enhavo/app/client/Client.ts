import {ClientInterface, ErrorHandlerInterface, Options, ErrorContextInterface, Transport} from "@enhavo/app/client/ClientInterface"

export class Client implements ClientInterface
{
    private errorHandler: ErrorHandler[] = [];

    lastRequest: {
        input: RequestInfo | URL,
        init?: RequestInit,
    } = {input: null, init: null};

    fetch(input: RequestInfo | URL, init?: RequestInit): Promise<Transport>
    {
        this.lastRequest.input = input;
        this.lastRequest.init = init;

        return new Promise(async (resolve, reject) => {
            try {
                const response = await fetch(input, init);
                resolve(new Transport(response));
            } catch (e) {
                resolve(new Transport(null, e));
            }
        });
    }

    on(code: number, callback: () => Promise<void>): ErrorContextInterface
    {
        return new ErrorContext(this.errorHandler, this);
    }

    handleError(transport: Transport, options: Options = {}): Promise<void>
    {
        for (let errorhandler of this.errorHandler) {
            if (errorhandler.errorHandler.supports(transport, options, this)) {
                return errorhandler.errorHandler.handleError(transport, options, this);
            }
        }

        throw 'Client: No error handler can handle this error';
    }

    addErrorHandler(errorHandler: ErrorHandlerInterface, priority: number = 0): ClientInterface
    {
        this.errorHandler.push(new ErrorHandler(errorHandler, priority));

        this.errorHandler.sort(function (a, b) {
            return b.priority - a.priority;
        });

        return this;
    }
}

class ErrorHandler
{
    constructor(
        public errorHandler: ErrorHandlerInterface,
        public priority: number,
    ) {
    }
}

class OnErrorHandler
{
    constructor(
        public code: number,
        public callback: () => Promise<void>,
    ) {
    }
}

class ErrorContext implements ErrorContextInterface
{
    private onErrorhandler: OnErrorHandler[] = [];

    constructor(
        private errorHandler: ErrorHandler[],
        private client: ClientInterface,
    ) {
    }

    on(code: number, callback: () => Promise<void>): ErrorContextInterface
    {
        this.onErrorhandler.push(new OnErrorHandler(code, callback));
        return this;
    }

    handleError(transport: Transport, options: Options): Promise<void>
    {
        for (let onErrorhandler of this.onErrorhandler) {
            if (transport.response?.status === onErrorhandler.code) {
                return onErrorhandler.callback();
            }
        }


        for (let errorhandler of this.errorHandler) {
            if (errorhandler.errorHandler.supports(transport, options, this)) {
                return errorhandler.errorHandler.handleError(transport, options, this);
            }
        }

        throw 'Client: No error handler can handle this error';
    }
}

