<?php

/**
 * UserService for login user in session, checking if user is authenticated and to get an instance of current user as AppUser
 *
 * @version 2.0
 * @author Mick
 */
final class UserService
{
	private $repository;

	/**
	 * @param UserRepository $repository
	 */
	function __construct(UserRepository $repository = null)
	{
		if ($repository == null)
			$repository = new UserRepository();

		$this->repository = $repository;
	}

	/**
	 * @return AppUser
	 */
	public function find($id)
	{
		return $this->repository->find($id);
	}


	/**
	 * @return AppUser[]
	 */
	public function all()
	{
		return $this->repository->all();
	}

	public function emailExists(string $email)
	{
		return $this->repository->emailExists($email);
	}

	public function getByEmail(string $email)
	{
		return $this->repository->getByEmail($email);
	}

	public function create(CreateUser $user)
	{
		$user = $this->sanitize($user);
		$user->password = password_hash($user->password, PASSWORD_BCRYPT);
		return $this->repository->create($user);
	}

	public function update(UpdateUserinfo $user)
	{
		$user = $this->sanitize($user);
		return $this->repository->update($user);
	}

	private function sanitize($user)
	{
		if (isset($user->firstname))
			$user->firstname = htmlspecialchars(strip_tags($user->firstname));
		if (isset($user->lastname))
			$user->lastname = htmlspecialchars(strip_tags($user->lastname));
		if (isset($user->email))
			$user->email = htmlspecialchars(strip_tags($user->email));
		return $user;
	}
}
