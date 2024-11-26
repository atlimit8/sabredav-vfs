<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\Vfs;
use Sabre\DAV\{IFile, INode};
use Sabre\DAV\Exception\{Forbidden};

class FileLink extends Link implements IFile
{
	protected IFile $node;

	public function __construct(IFile $node, ?string $name = null, ?Directory $parent) {
		parent::__construct($name ?? $node->getName(), $parent);
		$this->node = $node;
	}

	public function put($data) { $this->node->put($data); }

	public function get() { return $this->node->get(); }

	public function getContentType() { return $this->node->getContentType(); }

	public function getETag() { return $this->node->getETag(); }

	public function getSize() { return $this->node->getSize(); }

	public function delete() {
		$parent = $this->getParentDirectory();
		if (!$parent)
			throw new Forbidden("Virtual links cannot be deleted without a parent.");
		$parent->unlink($this);
	}

	public function getLastModified() { return $this->node->getLastModified(); }

	public function getLinkedNode(): ?INode { return $this->node; }
}