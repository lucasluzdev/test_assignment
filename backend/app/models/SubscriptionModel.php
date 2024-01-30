<?php

namespace app\models;

session_start();

use app\config\database\ConnectionPool;
use PDOException;
use app\helpers\Validation;

class SubscriptionModel
{

    private $table;
    private $connection_pool;

    public function __construct()
    {
        $this->connection_pool = new ConnectionPool();
        $this->table = 'subscriptions';
    }

    public function create($subscriptionDTO)
    {
        $response = [
            'data' => null,
            'status' => 400,
            'message' => ''
        ];

        try {

            $connection = $this->connection_pool->getConnection();
            $query = "INSERT INTO {$this->table} (id, first_name, last_name, email, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($query);
            $stmt->bindValue(1, $subscriptionDTO->getId());
            $stmt->bindValue(2, $subscriptionDTO->getFirstName());
            $stmt->bindValue(3, $subscriptionDTO->getLastName());
            $stmt->bindValue(4, $subscriptionDTO->getEmail());
            $stmt->bindValue(5, $subscriptionDTO->getStatus());
            $result = $stmt->execute();
            if ($result) {
                $response["status"] = 201;
                $response["message"] = "Subscription went successfully. Welcome {$subscriptionDTO->getFirstName()} :)";
            } else {
                $response["message"] = 'Error while trying to store new subscription. Check your information.';
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $response["message"] = "The email {$subscriptionDTO->getEmail()} is already being used by another user. Try another!";
                $response["status"] = 1062;
            } else {
                $response["message"] = 'Error while trying to store new subscription: ' . $e->getMessage();
            }
        }
        return $response;
    }

    public function readOneByID($id)
    {
        $response = [
            'data' => null,
            'status' => 400,
            'message' => ''
        ];

        try {
            

            $connection = $this->connection_pool->getConnection();
            $query = "SELECT id, first_name, last_name, email, status FROM {$this->table} WHERE id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $response['data'] = $stmt->fetchAll();
            $response['status'] = 200;
        } catch (PDOException $e) {
            $response["message"] = 'Error while trying to store new subscription: ' . $e->getMessage();
        }
        return $response;
    }

    public function readOne($search, $pageSize, $currentPage, $sortBy)
    {
        $response = [
            'data' => null,
            'status' => 400,
            'message' => '',
            'page' => intval($currentPage),
            'pageSize' => intval($pageSize),
            'totalItems' => 0
        ];

        try {

            $connection = $this->connection_pool->getConnection();
            $offset = ($currentPage - 1) * $pageSize;
            
            $query = "SELECT id, first_name, last_name, email, status FROM {$this->table} WHERE searchable like ? ORDER BY {$sortBy['column']} {$sortBy['ordenation']} LIMIT ? OFFSET ?";
            $stmt = $connection->prepare($query);
            $stmt->bindValue(1, "%{$search}%");
            $stmt->bindValue(2, $pageSize);
            $stmt->bindValue(3, $offset);
            $stmt->execute();
            $response['data'] = $stmt->fetchAll();
            $response['status'] = 200;
        } catch (PDOException $e) {
            $response["message"] = 'Error while trying to search subscription: ' . $e->getMessage();
        }
        return $response;
    }

    public function readMany($pageSize, $currentPage, $sortBy)
    {

        $response = [
            'data' => null,
            'status' => 400,
            'message' => '',
            'currentPage' => intval($currentPage),
            'totalItems' => 0,
            'pageSize' => intval($pageSize),
        ];

        try {

            $connection = $this->connection_pool->getConnection();
            $offset = ($currentPage - 1) * $pageSize;

            $response['totalItems'] = $connection->query("SELECT count(id) as totalItems FROM {$this->table}")->fetchColumn();

            $query = "SELECT id, first_name, last_name, email, status FROM {$this->table} ORDER BY {$sortBy['column']} {$sortBy['ordenation']} LIMIT ? OFFSET ?";
            
            $stmt = $connection->prepare($query);
            $stmt->bindValue(1, $pageSize);
            $stmt->bindValue(2, $offset);
            $stmt->execute();
            $response['data'] = $stmt->fetchAll();
            $response['status'] = 200;
        } catch (PDOException $e) {
            $response["message"] = 'Error while trying to store new subscription: ' . $e->getMessage();
        }
        return $response;
    }

    public function update($subscriptionDTO)
    {
        $response = ["status" => 400, "message" => "", "data" => null];
        try {
            $connection = $this->connection_pool->getConnection();
            $query = "UPDATE {$this->table} SET first_name = ?, last_name = ?, email = ?, status = ? WHERE id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bindValue(1, $subscriptionDTO->getFirstName());
            $stmt->bindValue(2, $subscriptionDTO->getLastName());
            $stmt->bindValue(3, $subscriptionDTO->getEmail());
            $stmt->bindValue(4, $subscriptionDTO->getStatus());
            $stmt->bindValue(5, $subscriptionDTO->getId());
            $result = $stmt->execute();
            if ($result) {
                $response["status"] = 200;
                $response["message"] = "Subscription update went successfully.";
                $response["data"] = $subscriptionDTO;
            } else {
                $response['message'] = 'Error while trying to update subscription. Check your information.';
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $response["message"] = "The email {$subscriptionDTO->getEmail()} is already being used by another user. Try another!";
                $response["status"] = 1062;
            } else {
                $response["message"] = 'Error while trying to update subscription: ' . $e->getMessage();
            }
        }
        return $response;
    }

    public function delete($id)
    {
        $response = ["status" => 400, "message" => "", "data" => null];
        try {

            $connection = $this->connection_pool->getConnection();
            $query = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bindValue(1, $id);
            $result = $stmt->execute();
            if ($result) {
                $response["status"] = 200;
                $response["message"] = "Subscription removal went successfully.";
                $response["data"] = $id;
            } else {
                $response["message"] = 'Error while trying to delete subscription. Try again later';
            }
        } catch (PDOException $e) {
            $response["message"] = 'Error while trying to store new subscription: ' . $e->getMessage();
        }
        return $response;
    }
}
