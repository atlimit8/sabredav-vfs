<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\Vfs;
use Sabre\DAV\{ICollection, INode};
use Sabre\DAV\Exception\{Forbidden};

class DirectoryLink extends Link implements ICollection
{
	protected ICollection $node;

	public function __construct(ICollection $node, ?string $name = null, ?Directory $parent) {
		parent::__construct($name ?? $node->getName(), $parent);
		$this->node = $node;
	}

	public function childExists($name) { return $this->node->childExists($name); }

	public function createDirectory($name) { return $this->node->createDirectory($name); }

	public function createFile($name, $data = null) { return $this->node->createFile($name, $data); }

	public function getChild($name) { return $this->node->getChild($name); }

	public function getChildren() { return $this->node->getChildren(); }

	public function delete() {
		$parent = $this->getParentDirectory();
		if (!$parent)
			throw new Forbidden("Virtual links cannot be deleted without a parent.");
		$parent->unlink($this);
	}

	public function getLastModified() { return $this->node->getLastModified(); }

	public function getLinkedNode(): ?INode { return $this->node; }
	}