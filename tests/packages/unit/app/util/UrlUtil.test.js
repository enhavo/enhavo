import { UrlUtil } from '@enhavo/app/util/UrlUtil';
import { expect, test, describe } from 'vitest'

describe('UrlUtil', () => {
    describe('contains', () => {
        test('returns true for exact match', () => {
            expect(UrlUtil.contains('/foo/bar', '/foo/bar')).toBe(true);
            expect(UrlUtil.contains('/foo?a=1', '/foo?a=1')).toBe(true);
            expect(UrlUtil.contains('https://example.com/path', 'https://example.com/path')).toBe(true);
        });

        test('returns true when paths match and target has no query params', () => {
            expect(UrlUtil.contains('/foo', '/foo?a=1')).toBe(true);
            expect(UrlUtil.contains('/foo/bar', '/foo/bar?x=1&y=2')).toBe(true);
        });

        test('returns true when paths match and all target query params are in base', () => {
            expect(UrlUtil.contains('/foo?a=1', '/foo?a=1&b=2')).toBe(true);
            expect(UrlUtil.contains('/foo?a=1&b=2', '/foo?a=1&b=2&c=3')).toBe(true);
        });

        test('returns false when paths match but target has query params not in base', () => {
            expect(UrlUtil.contains('/foo?a=1&b=2', '/foo?a=1')).toBe(false);
            expect(UrlUtil.contains('/foo?a=1', '/foo?b=1')).toBe(false);
        });

        test('returns false when paths match but query param values differ', () => {
            expect(UrlUtil.contains('/foo?a=1', '/foo?a=2')).toBe(false);
        });

        test('returns false when paths do not match', () => {
            expect(UrlUtil.contains('/foo', '/bar')).toBe(false);
            expect(UrlUtil.contains('/foo/bar', '/foo/baz')).toBe(false);
        });
    });

    describe('getPath', () => {
        test('extracts path from full URL', () => {
            expect(UrlUtil.getPath('https://example.com/foo/bar')).toBe('/foo/bar');
            expect(UrlUtil.getPath('https://example.com/foo?query=1')).toBe('/foo');
        });

        test('extracts path from relative URL', () => {
            expect(UrlUtil.getPath('/foo/bar')).toBe('/foo/bar');
            expect(UrlUtil.getPath('/foo?query=1')).toBe('/foo');
            expect(UrlUtil.getPath('/foo#hash')).toBe('/foo');
            expect(UrlUtil.getPath('/foo?query=1#hash')).toBe('/foo');
        });
    });

    describe('getQuery', () => {
        test('extracts query params from full URL', () => {
            expect(UrlUtil.getQuery('https://example.com/foo?a=1&b=2')).toEqual({ a: '1', b: '2' });
        });

        test('extracts query params from relative URL', () => {
            expect(UrlUtil.getQuery('/foo?a=1&b=2')).toEqual({ a: '1', b: '2' });
            expect(UrlUtil.getQuery('/foo?a=1#hash')).toEqual({ a: '1' });
        });

        test('returns empty object when no query params', () => {
            expect(UrlUtil.getQuery('/foo')).toEqual({});
            expect(UrlUtil.getQuery('https://example.com/foo')).toEqual({});
        });
    });
});
