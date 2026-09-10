
export class UrlUtil
{
    // is target url in base url
    public static contains(targetUrl: string, baseUrl: string): boolean
    {
        if (baseUrl == targetUrl) {
            return true;
        }

        if (UrlUtil.getPath(targetUrl) == UrlUtil.getPath(baseUrl)) {
            const targetQuery = UrlUtil.getQuery(targetUrl);
            const baseQuery = UrlUtil.getQuery(baseUrl);

            for (const [key, value] of Object.entries(targetQuery)) {
                if (baseQuery[key] !== value) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public static getPath(url: string): string
    {
        try {
            return new URL(url).pathname;
        } catch {
            return url.split('?')[0].split('#')[0];
        }
    }

    public static getQuery(url: string): object
    {
        try {
            return Object.fromEntries(new URL(url).searchParams);
        } catch {
            const qs = url.split('?')[1]?.split('#')[0];
            return qs ? Object.fromEntries(new URLSearchParams(qs)) : {};
        }
    }
}
