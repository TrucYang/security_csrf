<?php
require_once 'BaseModel.php';

class UserModel extends BaseModel
{

    // protected static $_connection;

    public static function getConnection()
    {
        return self::$_connection;
    }


    public function findUserById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = ' . $id;
        $user = $this->select($sql);

        return $user;

        // $stmt = self::$_connection->prepare('SELECT * FROM users WHERE id = ?');
        // $stmt->bind_param('i', $id);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findUser($keyword)
    {
        $sql = 'SELECT * FROM users WHERE user_name LIKE %' . $keyword . '%' . ' OR user_email LIKE %' . $keyword . '%';
        $user = $this->select($sql);

        return $user;
    }

    /**
     * Authentication user
     * @param $userName
     * @param $password
     * @return array
     */

    //public function auth($userName, $password)
    // {
    //     $md5Password = md5($password);
    //     $sql = 'SELECT * FROM users WHERE name = "' . $userName . '" AND password = "' . $md5Password . '"';

    //     $user = $this->select($sql);
    //     return $user;
    // }


    public function auth($userName, $password)
    {
        $md5Password = md5($password);
        $stmt = self::$_connection->prepare('SELECT * FROM users WHERE name = ? AND password = ?');
        $stmt->bind_param('ss', $userName, $md5Password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Delete user by id
     * @param $id
     * @return mixed
     */
    public function deleteUserById($id)
    {
        $sql = 'DELETE FROM users WHERE id = ' . $id;
        //$sql = 'DELETE FROM users WHERE id = ' . '199 OR 1 = 1';
        return $this->delete($sql);
    }

    /**
     * Update user
     * @param $input
     * @return mixed
     */
    // public function updateUser($input) {
    //     $sql = 'UPDATE users SET 
    //              name = "' . mysqli_real_escape_string(self::$_connection, $input['name']) .'", 
    //              password="'. md5($input['password']) .'"
    //             WHERE id = ' . $input['id'];

    //     $user = $this->update($sql);

    //     return $user;
    // }

    public function updateUser($input)
    {
        session_start();
        if (!isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF token không hợp lệ!");
        }

        $id = intval($input['id']);

        $name = mysqli_real_escape_string(self::$_connection, $input['name']);
        $password = md5($input['password']);

        $sql = "UPDATE users 
            SET name = '{$name}', password = '{$password}'
            WHERE id = {$id}";

        $user = $this->update($sql);
        return $user;
    }


    /**
     * Insert user
     * @param $input
     * @return mixed
     */
    // public function insertUser($input)
    // {
    //     $sql = "INSERT INTO `app_web1`.`users` (`name`, `password`) VALUES (" .
    //         "'" . $input['name'] . "', '" . md5($input['password']) . "')";

    //     $user = $this->insert($sql);

    //     return $user;
    // }

    public function insertUser($input)
    {
        session_start();
        if (!isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF token không hợp lệ!");
        }

        $name = mysqli_real_escape_string(self::$_connection, $input['name']);
        $password = md5($input['password']);

        $sql = "INSERT INTO `app_web1`.`users` (`name`, `password`) 
            VALUES ('{$name}', '{$password}')";

        $user = $this->insert($sql);
        return $user;
    }


    /**
     * Search users
     * @param array $params
     * @return array
     */
    // public function getUsers($params = [])
    // {
    //     //Keyword
    //     if (!empty($params['keyword'])) {
    //         $sql = 'SELECT * FROM users WHERE name LIKE "%' . $params['keyword'] . '%"';

    //         //Keep this line to use Sql Injection
    //         //Don't change
    //         //Example keyword: abcef%";TRUNCATE banks;##
    //         //$users = self::$_connection->multi_query($sql);

    //         //Get data
    //         $users = $this->query($sql);
    //     } else {
    //         $sql = 'SELECT * FROM users';
    //         $users = $this->select($sql);
    //     }

    //     return $users;
    // }

    public function getUsers($params = [])
    {
        if (!empty($params['keyword'])) {
            $keyword = '%' . $params['keyword'] . '%';
            $stmt = self::$_connection->prepare('SELECT * FROM users WHERE name LIKE ?');
            $stmt->bind_param('s', $keyword);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $sql = 'SELECT * FROM users';
            $users = $this->select($sql);
            return $users;
        }
    }


    // public function increaseVersion($userId)
    // {
    //     $stmt = self::$_connection->prepare('UPDATE users SET version = version + 1 WHERE id = ?');
    //     $stmt->bind_param('i', $userId);
    //     $stmt->execute();
    // }

    // public function getVersion($userId)
    // {
    //     $stmt = self::$_connection->prepare('SELECT version FROM users WHERE id = ?');
    //     $stmt->bind_param('i', $userId);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $row = $result->fetch_assoc();
    //     return $row ? $row['version'] : null;
    // }

    public function insertPost($content)
    {
        $stmt = self::$_connection->prepare('INSERT INTO post (post_title) VALUES (?)');
        $stmt->bind_param('s', $content);
        return $stmt->execute();
    }
}
