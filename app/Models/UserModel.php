<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $table = 'users';

    public function find($username)
    {
        return UserModel::where('username', $username)->first();
    }

    public function findAll()
    {
        return DB::table('users AS u')
            ->select(
                'id_user',
                'username',
                'password',
                'ug.id_user_group',
                'ug.description'
            )
            ->join('user_groups AS ug','u.id_user_group','=','ug.id_user_group')
            ->orderBy('created_at')
            ->get();
    }

    public function insertData($data)
    {
        return UserModel::insert($data);
    }

    public function updateData($data, $idUser)
    {
        return UserModel::where('id_user', $idUser)->update($data);
    }

    public function deleteData($idUser)
    {
        return UserModel::where('id_user', $idUser)->delete();
    }
}
