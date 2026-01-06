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
    public function __construct(
        ?int $id,
        string $name,
        string $email,
        string $password,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }


    /*
     *getters of User Model.
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt(?string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}

