<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * [index Return all users]
     * @return [json] [description]
     */
    public function index()
    {
        return User::get();
    }
}
