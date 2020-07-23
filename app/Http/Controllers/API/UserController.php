<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return user::orderBy('created_at','desc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|string|max:191|unique:users',
            'password' => 'required|string'
        ]);
      // return response()->json(['user' => $request->all()]);
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

    }


    public function profile()
    {
        return auth('api')->user();
    }

    public function updateProfile(Request $request)
    {
        $user =  auth('api')->user();
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|string|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);

        $currentImage = $user->image;

        if($request->image != $currentImage){
            $name = time().'.' .explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('admin/img/profile/').$name);
            $request->merge(['image' => $name]);

            $oldImage = public_path("admin/img/profile/".$currentImage);
            if(file_exists($oldImage)){
                unlink($oldImage);
            }
        }

        if(!empty($request->password)){
            $request->merge(['password' => bcrypt($request->password)]);
        }

        $user->update($request->all());

        return ['message' => 'success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        //update user
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|string|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6'
        ]);

        $user->update($request->all());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find or fail user
        $user = User::findOrFail($id);
        //delete user
        $user->delete();
        //send successfull message
        return ['message' => 'user successfully deleted'];
    }
}
