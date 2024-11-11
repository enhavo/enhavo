import {FileUrlResolverInterface} from "@enhavo/media/resolver/FileUrlResolverInterface";
import {File} from '@enhavo/media/model/File';
import {Router} from '@enhavo/app/routing/Router';

export class FileResolver implements FileUrlResolverInterface
{
    constructor(
        private readonly router: Router,
        private readonly fileCandidates: string[] = [],
        private readonly formatCandidate: string[] = [],
    )
    {
    }

    resolve(file: File, format: string = null, absolute: boolean = false): string
    {
        if (format) {
            for (let name of this.formatCandidate) {
                if (this.router.hasRoute(name)) {
                    return this.generate(name, file, format, absolute);
                }
            }
        } else {
            for (let name of this.fileCandidates) {
                if (this.router.hasRoute(name)) {
                    return this.generate(name, file, null, absolute);
                }
            }
        }

        throw 'No route found';
    }

    private generate(name: string, file: File, format: string = null, absolute: boolean = false): string
    {
        let variables = this.router.getRoute(name).path.match(/{(!)?([\w\x80-\xFF]+)}/g);

        let parameters = {};
        for (let variable of variables) {
            let key = variable.slice(1, -1);
            if (key === 'format') {
                parameters['format'] = format;
            } else if (!file.hasOwnProperty(key)) {
                throw 'Can\'t generate url for media route "'+name+'", because the file object has no "'+key+'" property';
            } else {
                parameters[key] = file[key];
            }
        }

        return this.router.generate(name, parameters, absolute);
    }
}
