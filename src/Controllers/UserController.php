<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $this->view('users/index');
    }

    public function getUsers()
    {
        $users = $this->userModel->getAll();
        $this->json($users);
    }

    public function create()
    {
        $data = $this->getRequestData();
        
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['position'])) {
            $this->json(['error' => 'Все поля обязательны'], 400);
            return;
        }

        if ($this->userModel->create($data)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['error' => 'Ошибка при создании пользователя'], 500);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data)) {
            $this->json(['error' => 'Данные не предоставлены'], 400);
            return;
        }

        if ($this->userModel->update($id, $data)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['error' => 'Ошибка при обновлении пользователя'], 500);
        }
    }

    public function delete($id)
    {
        if ($this->userModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['error' => 'Ошибка при удалении пользователя'], 500);
        }
    }
}
