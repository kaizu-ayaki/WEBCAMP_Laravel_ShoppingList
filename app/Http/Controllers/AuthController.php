<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginPostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * トップページ を表示する
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        return view('index');
    }

    public function login(LoginPostRequest $request){
        $datum = $request->validated();

        //var_dump($datum);exit;

        if(Auth::attempt($datum)===false){
            return back()->withInput()
                   ->withErrors(['auth' => 'emailかパスワードに誤りがあります。',]);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('front.list'));
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->regenerateToken();
        $request->session()->regenerate();
        return redirect(route('front.index'));
    }

}