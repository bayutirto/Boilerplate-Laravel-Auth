<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Validators\UserValidator;
use Illuminate\Support\Facades\DB;
use App\Validators\UserFormValidator;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->user = new UserModel();
        $this->userValidator = new UserValidator();
        $this->userFormValidator = new UserFormValidator();
    }

    public function find()
    {
        $data = $this->user->findAll();
        return response()->json($data);
    }

    public function insert(Request $request)
    {
        $body = $request->all();
        $rulesAndMessages = $this->userFormValidator->getGroupRule('insert');
        $validator = Validator::make($body, $rulesAndMessages['rules'], $rulesAndMessages['messages']);
        if ($validator->fails()) return response()->json($this->userFormValidator->formatMessages($validator), 400);

        try {
            DB::beginTransaction();
            $map = [
                'id_user' => Uuid::uuid4()->toString(),
                'username' => $body['username'],
                'password' => PASSWORD_HASH($body['password'], PASSWORD_DEFAULT),
                'id_user_group' => $body['id_user_group'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $this->user->insertData($map);

            DB::commit();
            return response()->json([
                'message' => 'Data added successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Data failed to add'
            ], 200);
        }
    }

    public function update (Request $request, $id_user)
    {
        $body = $request->all();
        $rulesAndMessages = $this->userValidator->getGroupRule('password');
        $validator = Validator::make($body, $rulesAndMessages['rules'], $rulesAndMessages['messages']);
        if ($validator->fails()) return response()->json($this->userValidator->formatMessages($validator), 400);

        $map = [
            'password' => PASSWORD_HASH($body['new_password'], PASSWORD_DEFAULT),
            'id_user_group' => $body['id_user_group'],
            'updated_at' => Carbon::now(),
        ];

        $this->user->updateData($map, $id_user);
        return response()->json([
            'message' => 'Data has been changed successfully'
        ]);
    }

    public function delete($id_user)
    {
        $this->user->deleteData($id_user);
        return response()->json([
            'message' => 'Data deleted successfully'
        ], 200);
    }
}
