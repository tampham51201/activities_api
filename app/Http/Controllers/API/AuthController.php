<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'masv'=>'required|max:255',
            'password'=>'required|min:6',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validation_errors'=>$validator->errors(),
            ]);
        }
        else{

            $user = User::where('masv', $request->masv)->first();
            if(! $user)
            {
                $user = User::where('email', $request->masv)->first();
            }
    
            if (!$user || ! Hash::check($request->password, $user->password)){
                return response()->json([
                    'status'=>401, 
                    'message'=>"invalid Credentials"
                ]);
                
            }
            else{

                if($user->role_as==0) ///admin
                {
                    $token=$user->createToken($user->email.'_Admintoken',['server:admin'])->plainTextToken;
                }
               
                else{
                    $token=$user->createToken($user->email.'_token',[''])->plainTextToken;
                }
               
                return response()->json([
                    'status'=>200,
                    'username'=>$user->username,
                    'roles'=>$user->role_as,
                    'token'=>$token,
                    'message'=>'Login Successlly',
                ]);
            }

        }

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
          'status'=>200,
          "message"=>"Logged Out Successfully"
      ]);
    }

    public function user()
    {
        if(auth('sanctum')->check())
        {
            $user=auth('sanctum')->user();
         
              return response()->json([
                  'status'=>200,
                  "user"=>$user
              ]);
        }
        else{
            
        }
    }

    public function index()
    {
        $users=User::all();
        return response()->json([
            'status'=>200,
            'users'=>$users,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username_input'=>'required|max:255|unique:users,username',
            'email_input'=>'required|email|max:255|unique:users,email',
            'password_input'=>'required|min:8',
            'password_confirm'=>'required|min:8|same:password_input',
            'role_as' => 'required|numeric',       

        ]);
        if ($validator->fails()) {
            
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),

            ]);
          
        } else {
            $user = new User();
            $user->username = $request->input('username_input');
            $user->email = $request->input('email_input');
            $user->password = Hash::make($request->input('password_input'));
            $user->role_as = $request->input('role_as');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->phone_number = $request->input('phone_number');
            $user->andress = $request->input('andress');
            $user->date_birth =$request->input('date_birth');
            $user->gender = $request->input('gender');
            $user->status = $request->input('status');   
            
       
            if ($request->hasFile('avata')) {          
                $file = $request->file('avata');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/userAvata/', $filename);
                $user->avata = 'uploads/userAvata/' . $filename;
            }
            $user->save();
            return response()->json([
                'status' => 200,
                'message' => 'Category Add Successfully',
            ]);
        }
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'username'=>'required|max:255|',
            'email'=>'required|email|max:255|',
            'password'=>'required|min:8',
            'role_as' => 'required|numeric',       

        ]);
        if ($validator->fails()) {   
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),

            ]);
          
        } else {
            $user = User::find($id);
            if($user)
            {
                $user->username = $request->input('username');
                $user->email = $request->input('email');
                if($request->input('password') != $user->password)
                {
                    $user->password =Hash::make($request->input('password'));
                }
                $user->role_as = $request->input('role_as');
                $user->first_name = $request->input('first_name')=='null'? null:$request->input('first_name');
                $user->last_name = $request->input('last_name')=='null'? null:$request->input('last_name');
                $user->phone_number = $request->input('phone_number')=='null'? null:$request->input('phone_number');
                $user->andress = $request->input('andress')=='null'? null:$request->input('andress');
                $user->date_birth = $request->input('date_birth')=='null'? null:$request->input('date_birth');
                $user->gender = $request->input('gender');
                $user->status = $request->input('status');   
    
           
                if ($request->hasFile('avata')) {     
                    $path = $user->avata;
                    if(File::exists($path))
                    {
                        File::delete($path);
    
                    }
                    

                    $file = $request->file('avata');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/userAvata/', $filename);
                    $user->avata = 'uploads/userAvata/' . $filename;
                }
                $user->update();
                return response()->json([
                    'status' => 200,
                    'message' => 'User Edit Successfully',
                ]);
            }
          
        }
    }

    public function edit($id)

    {
        $user = User::find($id);
        if ($user) {
            return response()->json([
                'status' => 200,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No User Id Found',
            ]);
        }
    }

    public function destroy($id)
    {
        $user=User::find($id);
        if($user)
        {
            $user->delete();
            return response()->json([
                'status'=>200,
                'message'=>'User Deleted Successfully',
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No User Id Found',
            ]);
        }
       
    }
}