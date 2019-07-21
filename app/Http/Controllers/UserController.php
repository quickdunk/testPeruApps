<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use UploadTrait;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['user_name', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $o_user = new User;

        return $o_user->getUserListPaginateAndFilter($request);
        // $a_queries_filter = [] ;
        // $a_valid_filters = ["user_name","first_name","last_name"];

        // foreach($a_valid_filters as $col){
        //     if($request->has($col)){
        //         $o_user = $o_user->where($col,'LIKE',$request->$col."%");
        //         $a_queries_filter[$col] = $request->$col;
        //     }
        // }
        
        // if($request->has('sort')){
        //     $o_user = $o_user->orderBy('last_name',$request->sort);
        // }

        // return $o_user->paginate(10)->appends($a_queries_filter);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $o_user = new User;

        $o_user->user_name = $request->user_name;
        $o_user->first_name = $request->first_name;
        $o_user->last_name = $request->last_name;
        $o_user->password = Hash::make($request->password);
        $o_user->email = $request->email;
        $o_user->visitor = $request->ip();
        $o_user->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::showPublicUserData($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $o_user = User::find($id);
        if ($o_user) {
            $a_input = $request->all();
            foreach ($a_input as $col => $value) {
                $o_user->$col = $value;
            }
            $o_user->save();
        } else {
            // not found
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $o_user = User::find($id);
        if ($o_user) {
            $o_user->delete();
        } else {
            // not found
        }
    }

    public function uploadProfileImage(Request $request, $id)
    {
        $o_user = User::find($id);
        if ($o_user && $request->has("profile_image")) {
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            $image =$request->file('profile_image');
            $s_file_name = str_slug($request->input('name')).'_'.time();
            $s_folder = "/uploads/images/";
            $s_file_path = $s_folder . $s_file_name. '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $s_folder, 'public', $s_file_name);
            
            $o_user->profile_image = "/storage".$s_file_path;
            $o_user->save();
        }
    }
}
