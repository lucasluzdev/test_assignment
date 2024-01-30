<?php

namespace app\controllers;

use app\models\SubscriptionModel;
use \Exception;
use app\dto\SubscriptionDTO;
use app\helpers\UUID;
use app\helpers\Validation;

class SubscriptionController
{

    private $model;

    public function __construct()
    {
        $this->model = new SubscriptionModel();
    }

    public function create()
    {
        try {
            $validation = new Validation();

            $uuid = new UUID();
            $input = file_get_contents('php://input');
            $json = json_decode($input, true);

            $id = $uuid->guidv4();
            $first_name = $validation->validateStringInput($json['first_name']);
            if (empty($first_name)) {
                echo json_encode(['status' => 400, 'message' => 'Please, provide a valid first name.']);
                return false;
            }
            $last_name = $validation->validateStringInput($json['last_name']);
            if (empty($last_name)) {
                echo json_encode(['status' => 400, 'message' => 'Please, provide a valid last name.']);
                return false;
            }
            $email = $validation->validateEmail($json['email']);
            if (empty($email)) {
                echo json_encode(['status' => 400, 'message' => 'Please, provide a valid email.']);
                return false;
            }
            $status = $validation->validateStatus($json['status']);
            if ($status == 0) {
                echo json_encode(['status' => 400, 'message' => 'Please, provide a valid status.']);
                return false;
            }

            $subscriptionDTO = new SubscriptionDTO($id, $first_name, $last_name, $email, $status);

            $response = $this->model->create($subscriptionDTO);
            echo json_encode($response);
            return true;
        } catch (Exception $e) {
            echo json_encode(['status' => 400, 'message' => 'Error while trying to store new user: ' . $e->getMessage()]);
            return false;
        }
    }

    public function readOne()
    {
        try {
            $search = $_GET['search'];
            $validation = new Validation();

            $pageSize = $_GET['pageSize'];
            $currentPage = $_GET['currentPage'] ? $_GET['currentPage'] : 1;
            $sort = $_GET['sort'] ? $_GET['sort'] : 'created-desc';

            $sortBy = $validation->getOrdenation($sort);

            if (!$validation->isValidPageSizeOption($pageSize)) {
                $pageSize = 10;
            }
            $result = null;
            $validSearch = $validation->validateStringInput($search, 1);

            if (!empty($validSearch)) {

                $result = $this->model->readOne($validSearch, $pageSize, $currentPage, $sortBy);
            } else {
                $result = $this->model->readMany($pageSize, $currentPage, $sortBy);
            }
            echo json_encode($result);
            return true;
        } catch (Exception $e) {
            echo json_encode(['status' => 400, 'message' => 'Error while trying to search users: ' . $e->getMessage()]);
            return false;
        }
    }

    public function readOneByID()
    {
        try {
            $id = $_GET['id'];

            $result = $this->model->readOneByID($id);
            echo json_encode($result);
            return true;
        } catch (Exception $e) {
            echo json_encode(['status' => 400, 'message' => 'Error while trying to search users by id: ' . $e->getMessage()]);
            return false;
        }
    }

    public function readMany()
    {
        try {
            $validation = new Validation();

            $pageSize = $_GET['pageSize'];
            $currentPage = $_GET['currentPage'] ? $_GET['currentPage'] : 1;
            $sort = $_GET['sort'] ? $_GET['sort'] : 'created-desc';
            $sortBy = $validation->getOrdenation($sort);

            if (!$validation->isValidPageSizeOption($pageSize)) {
                $pageSize = 10;
            }

            $result = $this->model->readMany($pageSize, $currentPage, $sortBy);
            echo json_encode($result);
            return true;
        } catch (Exception $e) {
            echo json_encode(['status' => 400, 'message' => 'Error while trying to search users: ' . $e->getMessage()]);
            return false;
        }
    }

    public function update()
    {
        try {
            $input = file_get_contents('php://input');
            $json = json_decode($input, true);
            $validation = new Validation();

            $id = trim($json['id']);

            if (strlen($id) != 36) {
                echo json_encode(['status' => 400, 'message' => 'User ID does not exist.']);
                return false;
            }
            $first_name = $validation->validateStringInput($json['first_name']);
            if (empty($first_name)) {
                echo json_encode(['status' => 400, 'message' => 'Please, provide a valid first name.']);
                return false;
            }
            $last_name = $validation->validateStringInput($json['last_name']);
            if (empty($last_name)) {
                echo json_encode(['status' => 400, 'message' => 'Please, provide a valid last name.']);
                return false;
            }
            $email = $validation->validateEmail($json['email']);
            if (empty($email)) {
                echo json_encode(['status' => 400, 'message' => 'Please, provide a valid email.']);
                return false;
            }
            $status = $validation->validateStatus($json['status']);
            if ($status == 0) {
                echo json_encode(['status' => 400, 'message' => 'Please, provide a valid status.']);
                return false;
            }

            $subscriptionDTO = new SubscriptionDTO($id, $first_name, $last_name, $email, $status);
            $response = $this->model->update($subscriptionDTO);
            echo json_encode($response);
            return true;
        } catch (Exception $e) {
            echo json_encode(['status' => 400, 'message' => 'Error while tring to update user: ' . $e->getMessage()]);
            return false;
        }
    }

    public function delete()
    {
        try {
            $uri = parse_url($_SERVER["REQUEST_URI"])["path"];
            $id = trim(explode("/", $uri)[2]);

            if (strlen($id) != 36) {
                echo json_encode(['status' => 400, 'message' => 'Invalid User ID.']);
                return false;
            }

            $response = $this->model->delete($id);
            echo json_encode($response);
            return true;
        } catch (Exception $e) {
            echo json_encode(['status' => 400, 'message' => 'Error while trying to remove user: ' . $e->getMessage()]);
            return false;
        }
    }
}
