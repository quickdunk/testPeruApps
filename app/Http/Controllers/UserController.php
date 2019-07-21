<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use UploadTrait;

    protected $a_response = array(
        "code" => 200,
        "message" => "Success"
    );

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['user_name', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            $this->a_response["message"] = "User or password invalid";
            $this->a_response["code"] = 401;
            return response()->json($this->a_response, 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $this->a_response["message"] = "Successfully logged out";
        return response()->json($this->a_response);
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
        $this->a_response["data"] = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ];
        return response()->json($this->a_response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $o_user = new User;
        $this->a_response["data"]  = $o_user->getUserListPaginateAndFilter($request);
        return $this->a_response;
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
        $validatedData = $request->validate([
            'user_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'email' => 'required'

        ]);

        $o_user = new User;

        $o_user->user_name = $request->user_name;
        $o_user->first_name = $request->first_name;
        $o_user->last_name = $request->last_name;
        $o_user->password = Hash::make($request->password);
        $o_user->email = $request->email;
        $o_user->visitor = $request->ip();
        $o_user->save();

        return $this->a_response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->a_response["data"]  = User::showPublicUserData($id);
        if (count($this->a_response["data"]) == 0) {
            unset($this->a_response["data"]);
            $this->a_response["message"] = "User not found";
        }
        return $this->a_response;
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
            $o_user->easyUpdate($request);
        } else {
            $this->a_response["message"] = "User not found";
        }

        return $this->a_response;
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
            $this->a_response["message"] = "User not found";
        }
        return $this->a_response;
    }

    public function uploadProfileImage(Request $request, $id)
    {
        $validatedData = $request->validate([
            'profile_image' => 'required'
        ]);

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
        } else {
            $this->a_response["message"] = "User not found";
        }
        return $this->a_response;
    }
}
