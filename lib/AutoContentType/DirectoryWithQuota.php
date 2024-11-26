<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\AutoContentType;
use Sabre\DAV\{ICollection, IQuota};

class DirectoryWithQuota extends Directory implements IQuota
{
	public function __construct(ICollection&IQuota $node) {
		parent::__construct($node);
	}

	public function getQuotaInfo() {
		$node = $this->node;
		return $node instanceof IQuota ? $node->getQuotaInfo() : [null, null];
	}

}