<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs;

class ContentTypes
{
	public static function getByExtension(string $extension, ?string $default = null): string {
		return match($extension) {
			'3gp'         => 'audio/3gpp', // TODO: audio/video see https://developer.mozilla.org/en-US/docs/Web/Media/Formats/Containers#3gp
			'7z'          => 'application/x-7z-compressed',
			'aac'         => 'audio/aac',
			'abw'         => 'application/x-abiword',
			'apng'        => 'image/apng',
			'arc'         => 'application/x-freearc',
			'avif'        => 'image/avif',
			'azw'         => 'application/vnd.amazon.ebook',
			'bin'         => 'application/octet-stream',
			'bmp'         => 'image/bmp',
			'cda'         => 'application/x-cdf',
			'css'         => 'text/css',
			'csv'         => 'text/csv',
			'doc'         => 'application/msword',
			'docx'        => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'eot'         => 'application/vnd.ms-fontobject',
			'epub'        => 'application/epub+zip',
			'flac'        => 'audio/flac',
			'gif'         => 'image/gif',
			'gz'          => 'application/gzip',
			'htm', 'html' => 'text/html',
			'ico'         => 'image/vnd.microsoft.icon',
			'ics'         => 'text/calendar',
			'jar'         => 'application/java-archive',
			'jpg', 'jpeg' => 'image/jpeg',
			'js'          => 'text/javascript',
			'json'        => 'application/json',
			'jsonld'      => 'application/ld+json',
			'md'          => 'text/markdown',
			'mid', 'midi' => 'audio/midi',
			'mjs'         => 'text/javascript',
			'mov'         => 'video/quicktime',
			'mp3'         => 'audio/mp3',
			'mp4'         => 'video/mp4',
			'mpg', 'mpeg' => 'video/mpeg',
			'odp'         => 'application/vnd.oasis.opendocument.presentation',
			'ods'         => 'application/vnd.oasis.opendocument.spreadsheet',
			'odt'         => 'application/vnd.oasis.opendocument.text',
			'oga', 'ogg'  => 'audio/ogg',
			'ogv'         => 'video/ogg',
			'ogx'         => 'application/ogg',
			'opus'        => 'audio/ogg',
			'otf'         => 'font/otf',
			'p10'         => 'application/pkcs10',
			'pdf'         => 'application/pdf',
			'php'         => 'application/x-httpd-php',
			'plist'       => 'application/x-plist',
			'png'         => 'image/png',
			'ppt'         => 'application/vnd.ms-powerpoint',
			'pptx'        => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'rar'         => 'application/vnd.rar',
			'rng'         => 'application/xml',
			'rst'         => 'text/prs.fallenstein.rst',
			'rtf'         => 'application/rtf',
			'sh'          => 'application/x-sh',
			'svg'         => 'image/svg+xml',
			'tar'         => 'application/x-tar',
			'tif', 'tiff' => 'image/tiff',
			'ts'          => 'video/mp2t',
			'ttf'         => 'font/ttf',
			'txt'         => 'text/plain',
			'vp8'         => 'video/vp8',
			'vp9'         => 'video/vp9',
			'vsd'         => 'application/vnd.visio',
			'wav'         => 'audio/wav',
			'weba'        => 'audio/webm',
			'webm'        => 'video/webm',
			'webp'        => 'image/webp',
			'woff'        => 'font/woff',
			'woff2'       => 'font/woff2',
			'xhtml'       => 'application/xhtml+xml',
			'xls'         => 'application/vnd.ms-excel',
			'xlsx'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'xml'         => 'application/xml',
			'yml', 'yaml' => 'application/yaml',
			'zip'         => 'application/zip',
			default       => $default ?? 'application/octet-stream',
		};
	}

	public static function getByName($name): ?string {
		$lname = strtolower($name);
		$n = strrpos($lname, '.');
		if ($n === false) {
			return match ($lname) {
				'changelog', 'code_of_conduct', 'license', 'notice', 'readme' => 'text/plain',
				default => null,
			};
		}

		$extension = substr($lname, $n + 1);
		return self::getByExtension($extension, $n === 0 ? 'text/plain' : null);
	}
}