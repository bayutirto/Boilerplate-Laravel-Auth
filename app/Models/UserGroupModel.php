<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroupModel extends Model
{
    protected $table = 'user_groups';

    public function find()
    {
        return UserGroupModel::get();
    }
}
