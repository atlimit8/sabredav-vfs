<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\Vfs;
use Sabre\DAV\{INode};

abstract class Node implements INode
{
	protected string $name;
	protected ?Directory $parent;

	public function __construct(string $name, ?Directory $parent = null) {
		$this->name = $name;
		$this->parent = $parent;
	}

	public function getParentDirectory(): ?Directory {
		return $this->parent;
	}


	public function setParentDirectory(?Directory $parent) {
		if ($this->parent) {
			if ($this->parent === $parent)
				return;
			$this->parent->unlink($this);
		}
		if ($parent)
			$parent->link($this, internal: true);
		$this->parent = $parent;
	}

	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$oldName = $this->name;
		$parent = $this->parent;
		if ($parent)
			$parent->link($this, $name, true);
		$this->name = $name;
		if ($parent)
			$parent->unlink($this, $oldName, true);
	}
}