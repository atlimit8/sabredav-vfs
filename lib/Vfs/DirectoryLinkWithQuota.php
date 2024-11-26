<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\Vfs;
use Sabre\DAV\{ICollection, IQuota};

class DirectoryLinkWithQuota extends DirectoryLink implements IQuota
{
	public function __construct(ICollection&IQuota $node, ?string $name = null, ?Directory $parent) {
		parent::__construct($node, $name ?? $node->getName(), $parent);
	}

	/**
	 * Returns the quota information.
	 *
	 * This method MUST return an array with 2 values, the first being the total used space,
	 * the second the available space (in bytes)
	 */
	public function getQuotaInfo() {
		$node = $this->node;
		if ($node instanceof IQuota)
			return $node->getQuotaInfo();
		return [null, null];
	}
}