<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\ForceContentType;
use Sabre\DAV\{ICollection, IFile, INode, IQuota};

class Util
{
	public static function wrap(INode $node): INode {
		if ($node instanceof IFile)
			return new File($node);
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