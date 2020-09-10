<?php

namespace App\Http\Controllers;

use App\Models\UserGroupModel;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function find(UserGroupModel $userGroup)
    {
        $data = $userGroup->find();
        return response()->json($data);
    }
}
