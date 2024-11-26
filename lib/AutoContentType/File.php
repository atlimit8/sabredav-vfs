<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\AutoContentType;
use Atlimit8\ContentTypes\Util as ContentTypes;
use Sabre\DAV\IFile;

class File implements IFile
{
	public function __construct(protected readonly IFile $node) {}

	public function getName() { return $this->node->getName(); }

	public function setName($name) { $this->node->setName($name); }

	public function put($data) { $this->node->put($data); }

	public function get() { return $this->node->get(); }

	public function getContentType() {
		$contentType = $this->node->getContentType();
		if ($contentType && $contentType  != 'application/octet-stream')
			return $contentType;
		return ContentTypes::getByName($this->node->getName()) ?? $contentType;
	}

	public function getETag() { return $this->node->getETag(); }

	public function getSize() { return $this->node->getSize(); }

	public function delete() { $this->node->delete(); }

	public function getLastModified() { return $this->node->getLastModified(); }

	public function getInnerNode(): IFile { return $this->node; }
}