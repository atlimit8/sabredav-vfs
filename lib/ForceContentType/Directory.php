<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\ForceContentType;
use Atlimit8\SabreDavVfs\AutoContentType\Util;
use Sabre\DAV\ICollection;

class Directory implements ICollection
{

	public function __construct(protected readonly ICollection $node) {}

	public function getName() { return $this->node->getName(); }

	public function setName($name) { $this->node->setName($name); }

	public function childExists($name) { return $this->node->childExists($name); }

	public function createDirectory($name) { return $this->node->createDirectory($name); }

	public function createFile($name, $data = null) { return $this->node->createFile($name, $data); }

	public function getChild($name) { return Util::wrap($this->node->getChild($name)); }

	public function getChildren() {
		return array_map(Util::wrap(...), $this->node->getChildren());
	}

	public function delete() { $this->node->delete(); }

	public function getLastModified() { return $this->node->getLastModified(); }
}