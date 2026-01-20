<?php

namespace Src\Models;

class Wallet
{
    private $id;
    private $user_id;
    private $budget;
    private $created_at;
    private $updated_at;

    /**
     * Summary of __construct
     * @param mixed $id
     * @param mixed $user_id
     * @param mixed $budget
     * @param mixed $created_at
     * @param mixed $updated_at
     */
    public function __construct(
        ?int $id,
        int $user_id,
        float $budget,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->budget = $budget;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /*
     * Getters of Wallet Model.
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getBudget(): float
    {
        return $this->budget;
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
     * Setters of Wallet Model.
     */

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setBudget(float $budget): void
    {
        $this->budget = $budget;
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