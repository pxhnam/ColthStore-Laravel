<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        return view('admin.pages.user.index');
    }

    public function create()
    {
        $data = [
            'title' => 'Create User',
            'sub' => 'Add new user',
            'id' => null
        ];
        return view('admin.pages.user.update', $data);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Update User',
            'sub' => 'edit info user',
            'id' => $id
        ];
        return view('admin.pages.user.update', $data);
    }
}
