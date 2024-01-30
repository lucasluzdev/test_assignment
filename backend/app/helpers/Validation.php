<?php

namespace app\helpers;

class Validation
{

    public function isValidSortOption($option)
    {
        $options = [
            "created-desc",
            "created-asc",
            "user-asc",
            "user-desc",
            "email-asc",
            "email-desc",
            "status-asc",
        ];

        return in_array($option, $options);
    }

    public function isValidPageSizeOption($option)
    {
        if (filter_var($option, FILTER_VALIDATE_INT)) {
            $options = [
                10,
                30,
                50
            ];

            return in_array($option, $options);
        }
        return false;
    }

    public function validateStringInput($input, $strlength=3)
    {
        if (strlen($input) < $strlength) {
            return null;
        }
        $trimmed = trim($input);
        $filtered = htmlspecialchars(strip_tags($trimmed), ENT_QUOTES, 'UTF-8');
        return $filtered;
    }

    public function validateStatus($status)
    {
        if (filter_var($status, FILTER_VALIDATE_INT) && ($status == 1 || $status == 2)) {
            return $status;
        }
        return 0;
    }

    public function validateEmail($email)
    {
        if (strlen($email) < 1 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }
        $trimmed = trim($email);
        $filteredEmail = filter_var($trimmed, FILTER_SANITIZE_EMAIL);
        return $filteredEmail;
    }


    public function getOrdenation($sort)
    {

        $response = null;
        switch ($sort) {
            case 'created-desc':
                $response = [
                    'column' => 'created_at',
                    'ordenation' => 'DESC'
                ];
                break;

            case 'created-asc':
                $response = [
                    'column' => 'created_at',
                    'ordenation' => 'ASC'
                ];
                break;
            case 'user-asc':
                $response = [
                    'column' => 'first_name',
                    'ordenation' => 'ASC'
                ];
                break;
            case 'user-desc':
                $response = [
                    'column' => 'first_name',
                    'ordenation' => 'DESC'
                ];
                break;
            case 'email-asc':
                $response = [
                    'column' => 'email',
                    'ordenation' => 'ASC'
                ];
                break;
            case 'email-desc':
                $response = [
                    'column' => 'email',
                    'ordenation' => 'DESC'
                ];
                break;
            case 'status-asc':
                $response = [
                    'column' => 'status',
                    'ordenation' => 'ASC'
                ];
                break;
            default:
                $response = [
                    'column' => 'created_at',
                    'ordenation' => 'DESC'
                ];
                break;
        }
        return $response;
    }
}
