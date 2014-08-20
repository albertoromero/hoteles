<?php defined('BASEPATH') or exit('No direct script access allowed');

class User extends AppModel
{
    protected $table = 'users';

    /**
     * Verifies if a user exists
     *
     */
    public function checkIsValid($username, $password)
    {
        $this->db->select(
            array(
                'id',
                'email',
                'username'
            )
        );
        $this->db->where(
            $this->table.(preg_match('/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/', $username) ? '.email' : '.username'),
            $username
        );
        return $this->getBy($this->table.'.password', $this->encript($password));
    }

    public function checkUser($user, $pass)
    {
        return $this->checkIsValid($user, $pass);
    }

    /**
     * Criptografa uma string
     *
     * @param string $string
     * @return string
     */
    public function encript($string)
    {
        return md5($string);
    }
}

/* End of file user.php */
/* Location: ./application/models/user.php */
