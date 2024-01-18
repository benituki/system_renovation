<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Users\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;

use App\Models\Users\Subjects;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function registerView()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    // 新規ユーザー作成用メソッド
    public function registerPost(Request $request)
    {
        DB::beginTransaction(); //ビギントランザクション
        // 1つ、または複数のデータベース操作からすべて成功するか失敗するかを判断する。

        // validation
        $request -> validate ([
            'over_name' => 'required',
            'under_name' => 'required',
            'over_name_kana' => 'required',
            'under_name_kana' => 'required',
            'mail_address' => 'required',
            'sex' => 'required',
            'old_year', 'old_month', 'old_day' => 'required',
            'role' => 'required',
            'password' => 'required'
        ]);

        try{
            //下記、HTTPリクエストからパラメータを取得し変数に代入
            $old_year = $request->old_year;
            $old_month = $request->old_month;
            $old_day = $request->old_day;
            $data = $old_year . '-' . $old_month . '-' . $old_day;
            $birth_day = date('Y-m-d', strtotime($data)); //年月日データを連結して$data変数に格納
            $subjects = $request->subject;

            // 新しいユーザーをデータベースに作成し、ユーザーの属性はHTTPリクエストから取得される。
            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password)
            ]);

            $user = User::findOrFail($user_get->id);
            // 新しく作成さっれたユーザーをデータベースから取得する。
            $user->subjects()->attach($subjects);
            // ユーザーと科目の多対多のリレーションシップを構築、ユーザーに関連付けられた科目を保存する。
            DB::commit();
            // これまでのすべてのDB操作が問題なく完了した場合はトランザクションをコミットして変更を確定させる。
            return view('auth.login.login');
            // ユーザー登録が成功した場合、ログイン画面へのビューにリダイレクトします。
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->route('loginView');
            // 例外が発生した場合、ログイン画面へのルートにリダイレクトします。
        }
    }
}