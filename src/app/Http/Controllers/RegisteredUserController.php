<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //ユーザーのばりでーしょん
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
        try {
            //ユーザーの作成
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

                //ログに出力　確認用
        Log::info('Register User Information: ', $user->toJson());

        //登録成功時のリダイレクト
        return redirect('/')->with('success', '登録完了');
    } catch (\Exception $e) {
        // エラーが発生した場合のハンドリング
        Log::error('Error occurred during use registration:', ['exception' => $e]);

        //エラーメッセージと一緒にリダイレクト
        return redirect('/auth/register')->with('error', '登録中にエラーが発生しました');
    }
}
};
