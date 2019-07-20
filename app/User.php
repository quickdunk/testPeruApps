<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    protected $table = "user";
    protected $fillable = ["user_name","first_name","last_name","password","email","visitor"];

    public static function showPublicUserData(int $p_id){
        return User::select("user_name","password","first_name","last_name","email","visitor")->where("id", $p_id)->get();
    }

    public function getUserListPaginateAndFilter(Request $request){
        $o_user = $this;
        $a_queries_filter = [] ;
        $a_valid_filters = ["user_name","first_name","last_name"];

        foreach($a_valid_filters as $col){
            if($request->has($col)){
                $o_user = $o_user->where($col,"LIKE",$request->$col."%");
                $a_queries_filter[$col] = $request->$col;
            }
        }
        
        if($request->has("sort") && in_array($request->sort,['asc','desc'])){
            $o_user = $o_user->orderBy("last_name",$request->sort);
        }
        $i_rows = 10;
        if(($request)->has("rows") && is_numeric($request->rows)){
            $i_rows = $request->rows;
        }
        
        return $o_user->paginate($i_rows)->appends($a_queries_filter);
    }
}
