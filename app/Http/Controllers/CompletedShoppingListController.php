<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompletedShopping as CompletedShoppingModel;
use Illuminate\Support\Facades\Auth;

class CompletedShoppingListController extends Controller
{
    //
    public function list(){

        $per_page=10;
        $list=CompletedShoppingModel::where('user_id', Auth::id())
                                        ->orderBy('name')
                                        ->orderBy('created_at')
                                        ->paginate($per_page);
        //echo "<pre>\n";var_dump($list);exit;   order by追加↑ok

        return view('shopping.completed_list',['list'=>$list]);
    }
}
