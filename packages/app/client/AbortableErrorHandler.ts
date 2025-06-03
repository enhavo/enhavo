import {ClientInterface, ErrorHandlerInterface, Options, Transport} from "@enhavo/app/client/ClientInterface"

export class AbortableErrorHandler implements ErrorHandlerInterface
{
    handleError(transport: Transport, options: Options, client: ClientInterface): Promise<void>
    {
        // nothing to do here
        return null;
    }

    supports(transport: Transport, options: Options, client: ClientInterface): boolean
    {
        return !transport.ok && options.abortable && transport.error.name === 'AbortError';
    }
}
