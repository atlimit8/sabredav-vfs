<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\ForceContentType;
use Atlimit8\SabreDavVfs\ContentTypes;
use Sabre\DAV\IFile;

class File implements IFile
{

	public function __construct(protected readonly IFile $node) {}

	public function getName() { return $this->node->getName(); }

	public function setName($name) { $this->node->setName($name); }

	public function put($data) { $this->node->put($data); }

	public function get() { return $this->node->get(); }

	public function getContentType() {
		return ContentTypes::getByName($this->node->getName()) ?? $this->node->getContentType();
	}

	public function getETag() { return $this->node->getETag(); }

	public function getSize() { return $this->node->getSize(); }

	public function delete() { $this->node->delete(); }

	public function getLastModified() { return $this->node->getLastModified(); }

	public function getInnerNode(): IFile { return $this->node; }
}