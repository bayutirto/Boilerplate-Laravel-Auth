<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Libraries\Access;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Validators\UserValidator;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userValidator = new UserValidator();

    }

    public function login(Request $request)
    {
        $rulesAndMessages = $this->userValidator->getGroupRule('login');
        $body = $request->only(array_keys($rulesAndMessages['rules']));

        $validator = Validator::make($body, $rulesAndMessages['rules'], $rulesAndMessages['messages']);
        if ($validator->fails()) return response()->json($this->userValidator->formatMessages($validator), 400);

        $data = $this->userModel->find($body['username']);
        if (empty($data))
        {
            $errors = ['message' => 'Username not found'];
            return response()->json($errors, 400);
        }

        if (!password_verify($body['password'], $data['password']))
        {
            $errors = ['message' => 'Password incorrect'];
            return response()->json($errors, 400);
        }

        return response()->json(['access_token' => (new Akses())->createToken($data)]);
    }
}
