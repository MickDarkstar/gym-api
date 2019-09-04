<?php

/**
 *
 * @version 2.0
 * @author Micke@tempory.org
 */
final class UserRepository extends BaseRepository
{
    const DB_TABLE = "users";

    private $db;

    public function __construct(PDO $pdo = null)
    {
        parent::__construct();

        if (isset($pdo))
            $this->db = $pdo;
        else
            $this->db = parent::$dbHandle;
    }

    public function all()
    {
        $list = [];
        $req = $this->db->query("SELECT * FROM `" . self::DB_TABLE . "` ORDER BY created");
        $req->execute();
        foreach ($req->fetchAll() as $result) {
            $list[] = self::mapToObject($result);
        }
        return $list;
    }

    /**
     * Returns true if email is in use
     * @param string $email
     * @return boolean
     */
    public function emailExists($email)
    {
        $query = "SELECT id, firstname, lastname, password
                    FROM " . self::DB_TABLE . "
                    WHERE email = ?
                    LIMIT 0,1";

        $stmt = $this->db->prepare($query);
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            return true;
        }
        return false;
    }

    /**
     * Returns AppUser by email
     * @param string $email
     * @return AppUser
     */
    public function getByEmail($email)
    {
        $query = "SELECT id, firstname, lastname, password
                    FROM `" . self::DB_TABLE . "`
                    WHERE email = ?
                    LIMIT 0,1";

        $stmt = $this->db->prepare($query);
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new AppUser(
            $row['id'],
            $row['firstname'],
            $row['lastname'],
            $email,
            $row['password']
        );
    }

    /**
     * Returns AppUser by id
     * @param int $id
     * @return AppUser
     */
    public function find($id)
    {
        $id = intval($id);
        $req = $this->db->prepare("SELECT * FROM `" . self::DB_TABLE . "` WHERE id = :id");
        $req->execute(array('id' => $id));
        $result = $req->fetch();

        return self::mapToObject($result);
    }

    /**
     * Saves a new AppUser in database and return is on success
     * @param AppUser $user
     * @return AppUser
     */
    public function create(CreateUser $user)
    {
        $now = date("Y-m-d");
        $req = $this->db->prepare("INSERT INTO `" . self::DB_TABLE . "`(firstname, lastname, password, email, created) VALUES(:firstname, :lastname, :password, :email, :created)");
        $req->bindParam(':firstname', $user->firstname, PDO::PARAM_STR);
        $req->bindParam(':lastname', $user->lastname, PDO::PARAM_STR);
        $req->bindParam(':password', $user->password);
        $req->bindParam(':email', $user->email, PDO::PARAM_STR);
        $req->bindParam(':created', $now, PDO::PARAM_STR);

        if ($req->execute() == false) {
            return false;
        }

        return self::find($this->db->lastInsertId());
    }

    /**
     * Saves an existing AppUser in database and return is on success
     * @param AppUser $user
     */
    public function update(UpdateUserinfo $user)
    {
        $req = $this->db->prepare("UPDATE `" . self::DB_TABLE . "` SET firstname=:firstname, lastname=:lastname, email=:email WHERE :id=id");
        $req->bindParam(':id', $user->id, PDO::PARAM_INT);
        $req->bindParam(':firstname', $user->firstname, PDO::PARAM_STR);
        $req->bindParam(':lastname', $user->lastname, PDO::PARAM_STR);
        $req->bindParam(':email', $user->email, PDO::PARAM_STR);
        $req->execute();
    }

    /**
     * Maps data from current table to an object
     * @param array[] $result
     * @return AppUser $user
     */
    private function mapToObject($result)
    {
        return new AppUser(
            $result['id'],
            $result['firstname'],
            $result['lastname'],
            $result['email'],
            $result['password']
        );
    }

    private function mapToObjects($rows)
    {
        if ($rows == null) {
            return null;
        }
        $result = [];
        foreach ($rows as $row) {
            $object = self::mapToObject($row);
            array_push($result, $object);
        }
        return $result;
    }
}
