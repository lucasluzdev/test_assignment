<?php

namespace app\dto;

class SubscriptionDTO
{
    private string $id;
    private string $first_name;
    private string $last_name;
    private string $email;
    private int $status;

    public function __construct($id, $first_name, $last_name, $email, $status)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->status = $status;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name)
    {
        $this->last_name = $last_name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}
