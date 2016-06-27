<?php

namespace Thunbolt\User;

use Kdyby\Doctrine\EntityManager;
use Nette\Security;
use Nette\Security\IAuthenticator;
use Nette\Security\IAuthorizator;
use Nette\Security\IUserStorage;
use Thunbolt\User\Interfaces\IUser;

/**
 * @property Identity|IUser|\Entity\User$identity
 * @property string $avatar
 * @property string $name
 * @property string $primary
 * @property string $roleName
 * @method Identity|IUser|\Entity\User getIdentity()
 */
class User extends Security\User {

	/** @var EntityManager */
	private $entityManager;

	public function __construct(IUserStorage $storage, EntityManager $entityManager, IAuthenticator $authenticator = NULL,
								IAuthorizator $authorizator = NULL) {
		parent::__construct($storage, $authenticator, $authorizator);

		$this->entityManager = $entityManager;
	}

	/************************* Own properties and methods **************************/

	/**
	 * @return bool
	 */
	public function hasRole() {
		return $this->isLoggedIn() && $this->getIdentity()->getEntity()->getRole();
	}

	/**
	 * @return bool
	 */
	public function isAdmin() {
		if (!$this->hasRole()) {
			return FALSE;
		}

		return $this->getIdentity()->getEntity()->getRole()->isAdmin();
	}

	/**
	 * @return bool
	 */
	public function isSuperAdmin() {
		if (!$this->hasRole()) {
			return FALSE;
		}

		return $this->getIdentity()->getEntity()->getRole()->isSuperAdmin();
	}

	/**
	 * @return string
	 */
	public function getAvatar() {
		return $this->getIdentity()->getEntity()->getAvatar();
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->getIdentity()->getEntity()->getUserName();
	}

	/**
	 * @return bool
	 */
	public function isMonitoring() {
		return $this->getIdentity()->getEntity()->isMonitoring();
	}

	/**
	 * @return string
	 */
	public function getRoleName() {
		if (!$this->hasRole()) {
			return NULL;
		}

		return $this->getIdentity()->getEntity()->getRole()->getName();
	}

	/************************* User methods **************************/

	public function merge() {
		$this->entityManager->merge($this->getIdentity()->getEntity());
		$this->entityManager->flush();
	}

	/**
	 * @param string $resource
	 * @param string $privilege
	 * @return bool
	 */
	public function isAllowed($resource = IAuthorizator::ALL, $privilege = IAuthorizator::ALL) {
		if (!$this->isLoggedIn()) {
			return FALSE;
		}
		if (strpos($resource, ':')) {
			list($resource, $privilege) = explode(':', $resource);
		}

		return $this->getAuthorizator()->isAllowed($this->getIdentity()->getEntity()->getRole(), $resource, $privilege);
	}

}
