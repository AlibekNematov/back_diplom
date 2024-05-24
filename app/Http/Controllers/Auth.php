<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use Illuminate\Http\Request;

class Auth extends Controller
{
    public function login(Request $request) {
        $queryBody = $request->all();
        $user = AppUser::query()->where('login', $queryBody['login'])->where('password', $queryBody['password'])->first();

        if (!@$queryBody['login'] || !@$queryBody['password']) {
            return response([
                'error_text' => 'Не заполнены обязательные поля',
            ], 400);
        }

        if ($user) return $user;
        else return response([
            'error_text' => 'Пользователь не найден',
        ], 404);
    }

    public function register(Request $request) {
        $queryBody = $request->all();
        $user = AppUser::query()->where('login', $queryBody['login'])->first();

        if (!@$queryBody['name'] || !@$queryBody['login'] || !@$queryBody['password']) {
            return response([
                'error_text' => 'Не заполнены обязательные поля',
            ], 400);
        }

        if ($user === null) {
            $user = AppUser::query()->create([
                'name' => $queryBody['name'],
                'login' => $queryBody['login'],
                'password' => $queryBody['password'],
            ]);

            $user->save();

            return $user;
        } else {
            return response([
                'error_text' => 'Пользователь существует',
            ], 400);
        }
    }
}
