<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\ProtectServerFiles;
use Sabre\DAV\ICollection;
use Sabre\DAV\Exception\{Forbidden, NotFound};

class Directory implements ICollection
{
	protected ICollection $node;

	public function __construct(ICollection $node) {
		$this->node = $node;
	}

	private function getFilter(): callable {
		return Util::isNotProtectedByName(...);
	}

	public function getName() { return $this->node->getName(); }

	public function setName($name) { $this->node->setName($name); }

	public function childExists($name) {
		return $this->getFilter()($name) && $this->node->childExists($name);
	}

	public function createDirectory($name) {
		if (!$this->getFilter()($name))
			throw new Forbidden('Unable to create directory: '.$name);
		return $this->node->createDirectory($name);
	}

	public function createFile($name, $data = null) {
		if (!$this->getFilter()($name))
			throw new Forbidden('Unable to create file: '.$name);
		return $this->node->createFile($name, $data);
	}

	public function getChild($name) {
		if (!$this->getFilter()($name))
			throw new NotFound('Node with name \''.$name.'\' could not be found');
		return Util::wrap($this->node->getChild($name));
	}

	public function getChildren() {
		$children = [];
		$filter = $this->getFilter();
		foreach ($this->node->getChildren() as $child)
			if ($filter($child->getName()))
				$children[] = Util::wrap($child);
		return $children;
	}

	public function canDelete() {
		$filter = $this->getFilter();
		foreach ($this->node->getChildren() as $child)
			if (!$filter($child->getName()))
				return false;
			elseif ($child instanceof ICollection && !(new self($child))->canDelete())
				return false;
		return true;
	}

	public function delete() {
		if (!$this->canDelete())
			throw new Forbidden('Directory cannot be deleted: ' . $this->node->getName());
		$this->node->delete();
	}

	public function getLastModified() { return$this->node->getLastModified(); }
}