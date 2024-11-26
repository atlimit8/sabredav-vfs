<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\ProtectServerFiles;
use Sabre\DAV\{ICollection, INode, IQuota};

class Util
{
	public static function isProtectedByExtension(string $extension) {
		return match(strtolower($extension)) {
			'bat', 'cert', 'class', 'crt', 'csh', 'der', 'exe', 'key', 'p12', 'php', 'pl', 'py', 'rb', 'sh', 'env' => true,
			default => false,
		};
	}

	public static function isProtectedByExtensionWeak(string $extension) {
		return match(strtolower($extension)) {
			'bat', 'cert', 'crt', 'csh', 'der', 'key', 'p12', 'php', 'pl', 'py', 'rb', 'sh', 'env' => true,
			default => false,
		};
	}

	public static function isNotProtectedByExtension($extension): bool {
		return !self::isProtectedByExtension($extension);
	}

	public static function isProtectedByName($name): bool {
		if (str_starts_with($name, '.'))
			return true;
		$lname = strtolower($name);
		if (in_array($lname, ['composer.json', 'package.json', 'web.config']))
			return true;
		$n = strrpos($lname, '.');
		if ($n === false)
			return false;
		$extension = substr($lname, $n + 1);
		return self::isProtectedByExtension($extension);
	}

	public static function isNotProtectedByName($name): bool {
		return !self::isProtectedByName($name);
	}

	public static function isProtectedByNameWeak($name): bool {
		$lname = strtolower($name);
		if (in_array($lname, [
			'composer.json',
			'package.json',
			'web.config',
		]))
			return true;
		$n = strrpos($lname, '.');
		if ($n === false)
			return true;
		$extension = substr($lname, $n + 1);
		return self::isProtectedByExtension($extension);
	}

	public static function wrap(INode $node): INode {
		if ($node instanceof ICollection)
			if ($node instanceof IQuota)
				return new DirectoryWithQuota($node);
			else
				return new Directory($node);
		return $node;
	}

	public static function nullableWrap(?INode $node): ?INode {
		return $node ? self::wrap($node) : null;
	}

	/**
	 * @param INode[] $nodes
	 * @return INode[]
	 */
	public static function wrapAll(array $nodes): array {
		return array_map(self::wrap(...), $nodes);
	}
}