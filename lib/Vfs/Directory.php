<?php
declare(strict_types=1);
namespace Atlimit8\SabreDavVfs\Vfs;
use Sabre\DAV\{ICollection, IFile, INode, IQuota};
use Sabre\DAV\Exception\{Conflict, Forbidden, NotFound};

class Directory extends Node implements ICollection
{
	/** @var array<string, Node */
	protected array $children = [];

	public function link(INode $node, ?string $name = null, bool $internal = false) {
		$name ??= $node->getName();
		if ($this->children[$name] ?? null)
			throw new Conflict('A child already exists for the name: ' . $name);
		$link = null;
		if ($node instanceof Node) {
			$link = $node;
		} elseif ($node instanceof IFile) {
			$link = new FileLink($node, name: $name, parent: $this);
		} elseif ($node instanceof ICollection) {
			$link = $node instanceof IQuota
				? new DirectoryLinkWithQuota($node, name: $name, parent: $this)
				: new DirectoryLink($node, name: $name, parent: $this);
		} else {
			throw new Forbidden('Child is not a known type.');
		}
		$parent = $link->getParentDirectory();
		if ($parent !== null && !$internal && $parent !== $this) {
			$link->setParentDirectory($this);
		}
		$this->children[$name] = $link;
	}

	public function unlink(Node $node, ?string $name = null, bool $internal = false) {
		if (!$internal && $node->getParentDirectory() !== $this)
			return;
		$name ??= $node->getName();
		if (($this->children[$name] ?? null) !== $node)
			return;
		unset($this->children[$name]);
		if (!$internal)
			$node->setParentDirectory(null);
	}

	/**
	 * Sets up the node, expects a full path name.
	 *
	 * @param array<string, INode> $children
	 */
	public function __construct(string $name = '', ?INode $parent = null, array $children = []) {
		parent::__construct($name, $parent);
		foreach ($children as $nameOrInt => $child)
			if (!is_object($child))
				throw new \InvalidArgumentException('Child is not an object: ' . var_export($child. true));
			elseif (!($child instanceof INode))
				throw new \InvalidArgumentException('Child is not a Sabre\\DAV\\INode: ' . var_export($child. true));
			else
				$this->link($child, name: is_string($nameOrInt) ? $nameOrInt : null);
	}

	/**
	 * Returns the last modification time, as a unix timestamp.
	 *
	 * @return int
	 */
	public function getLastModified() {
		$time = false;
		foreach ($this->children as $child) {
			$ctime = $child->getLastModified();
			if ($ctime !== false && ($time === false || $ctime < $time))
				$time = $ctime;
		}
		return $time;
	}

	/**
	 * Creates a new file in the directory.
	 *
	 * @param string          $name Name of the file
	 * @param resource|string $data Initial payload
	 *
	 * @return string|null
	 */
	public function createFile($name, $data = null) {
		throw new Forbidden('Permission denied to create file (filename '.$name.')');
	}

	public function createDirectory($name)
	{
		$this->link(new self($name, $this));
	}

	public function delete()
	{
		$parent = $this->parent;
		if (!($parent instanceof self))
			throw new Forbidden();
		$parent->unlink($this);
	}

	/**
	 * Returns a specific child node, referenced by its name.
	 *
	 * This method must throw Sabre\DAV\Exception\NotFound if the node does not
	 * exist.
	 *
	 * @param string $name
	 *
	 * @return INode
	 */
	public function getChild($name) {
		$child = $this->children[$name] ?? null;
		if ($child)
			return $child;
		throw new NotFound('File not found: '.$name);
	}

	/**
	 * Returns an array with all the child nodes.
	 *
	 * @return INode[]
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * Checks if a child-node with the specified name exists.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function childExists($name) {
		return ($this->children[$name] ?? null) !== null;
	}
}