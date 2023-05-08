<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShoppingRegisterPost;
use Illuminate\Support\Facades\Auth;
use App\Models\shopping_list as shopping_listModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\completed_shopping_lists as completed_shopping_listsModel;

class ShoppingListController extends Controller
{
    /**
     * トップページ を表示する
     *
     * @return \Illuminate\View\View
     */

    public function list()
    {
        $per_page = 5;

        $list=shopping_listModel::where('user_id',Auth::id())
                                ->orderBy('name','asc')
                                ->paginate($per_page);//->get();
        //echo "<pre>\n"; var_dump($list); exit;
        return view('shopping.list',['list'=>$list]);
    }

    public function register(ShoppingRegisterPost $request){
        $datum = $request->validated();
        //$id = Auth::id();
        //var_dump($datum,$id); exit;
        $datum['user_id']=Auth::id();

        try{
            $r=shopping_listModel::create($datum);
        //var_dump($r); exit;
        }catch(\Throwable $e) {
            echo $e->getMessage();
            exit;
        }

        $request->session()->flash('Shopping_register_success', true);
        return redirect(route('front.list'));

    }

    protected function getShopping_listModel($shopping_list_id){
        $shopping=shopping_listModel::find($shopping_list_id);
        if($shopping===null){
            return null;
        }
        if($shopping->user_id !== Auth::id()){
            return null;
        }
        return $shopping;
    }

    public function delete(Request $request,$shopping_list_id){
        $shopping=$this->getShopping_listModel($shopping_list_id);

        if($shopping !== null){
            //var_dump($shopping);exit;
            $shopping->delete();
            $request->session()->flash('list_delete_success', true);
        }

        return redirect(route('front.list'));
    }

    public function complete(Request $request, $shopping_list_id){
        try{
            DB::beginTransaction();
            $shopping=$this->getShopping_listModel($shopping_list_id);

            if($shopping === null){
                throw new \Exception('');
            }
            //var_dump($shopping->toArray());exit;

            $shopping->delete();

            $dask_datum=$shopping->toArray();
            unset($dask_datum['created_at']);
            unset($dask_datum['updated_at']);

            $r=completed_shopping_listsModel::create($dask_datum);

            if ($r === null) {
                throw new \Exception('');
            }

            DB::commit();

            $request->session()->flash('front.shopping_completed_sucsess', true);

        }catch(\Throwable $e) {
            //var_dump($e->getMessage()); exit;
            DB::rollBack();

            $request->session()->flash('front.shopping_completed_failure', true);
        }

        return redirect(route('front.list'));
    }
}