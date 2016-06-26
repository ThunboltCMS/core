<?php

namespace Thunbolt\User;

use Nette;
use Thunbolt\User\Interfaces\IUser;

class Identity extends Nette\Object implements Nette\Security\IIdentity {

	/** @var IUser */
	private $entity;

	/** @var int */
	private $id;

	/**
	 * @param IUser $entity
	 */
	public function __construct($id, IUser $entity = NULL) {
		$this->setId($id);
		$this->entity = $entity;
	}

	/**
	 * Sets the ID of user.
	 * @param  mixed
	 * @return self
	 */
	public function setId($id) {
		$this->id = is_numeric($id) && !is_float($tmp = $id * 1) ? $tmp : $id;
		return $this;
	}

	/**
	 * Returns the ID of user.
	 *
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Returns a list of roles that the user is a member of.
	 *
	 * @return array
	 */
	public function getRoles() {
		return [$this->entity->getRole()];
	}

	/**
	 * @return IUser|\Entity\User
	 */
	public function getEntity() {
		return $this->entity;
	}

	/**
	 * Sets user data value.
	 *
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 */
	public function __set($key, $value) {
		$this->entity->$key = $value;
	}

	/**
	 * Returns user data value.
	 *
	 * @param  string  property name
	 * @return mixed
	 */
	public function &__get($key) {
		$get = $this->entity->$key;

		return $get;
	}

	/**
	 * Is property defined?
	 *
	 * @param  string  property name
	 * @return bool
	 */
	public function __isset($key) {
		return isset($this->entity->$key);
	}

	/**
	 * Removes property.
	 *
	 * @param  string  property name
	 * @return void
	 * @throws Nette\MemberAccessException
	 */
	public function __unset($name) {
		unset($this->entity->$name);
	}

	/**
	 * Calls method from entity
	 *
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 */
	public function __call($name, $args) {
		if ($this->entity) {
			return call_user_func_array([$this->entity, $name], $args);
		}
	}

	/**
	 * @return array
	 */
	public function __sleep() {
		return ['id'];
	}

}