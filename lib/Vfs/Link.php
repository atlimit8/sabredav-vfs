<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\Vfs;
use Sabre\DAV\{ICollection, INode, IQuota};

abstract class Link extends Node
{
	abstract public function getLinkedNode(): ?INode;

	public static function make(ICollection $node, ?string $name = null, ?Directory $parent) {
		return $node instanceof IQuota
			? new DirectoryLinkWithQuota($node, $name, $parent)
			: new DirectoryLink($node, $name, $parent);
	}
}