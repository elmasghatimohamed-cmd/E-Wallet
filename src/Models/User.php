<?php

namespace Src\Models;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $created_at;
    private $updated_at;

    /**
     * Summary of __construct
     * @param mixed $id
     * @param mixed $name
     * @param mixed $email
     * @param mixed $password
     * @param mixed $created_at
     * @param mixed $updated_at
     */
    public function __construct($id, $name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /*
     *getters of User Model.
     */

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }


    /*
     *setters of User Model.
     */

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}

