<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index()
    {
        $categories=Category::all();
        return response()->json([
            'status'=>200,
            'users'=>$categories,
        ]);
    }


    public function getStatus()
    {
        $categorys=Category::where('status','1')->get();
        return response()->json([
            'status'=>200,
            'categorys'=>$categorys,
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'username_input'=>'required|max:255|unique:users,username',
            // 'email_input'=>'required|email|max:255|unique:users,email',
           

        ]);
        if ($validator->fails()) {
            
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),

            ]);
          
        } else {
            $category = new Category();
      
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->status = $request->input('status') == true ? 1:0;   
            
            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'Thêm Đơn Vị Tổ Chức Thành Công!',
            ]);
        }
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            // 'username'=>'required|max:255|',
            // 'email'=>'required|email|max:255|',
            // 'password'=>'required|min:8',
            // 'role_as' => 'required|numeric',       
        ]);
        if ($validator->fails()) {   
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),

            ]);
          
        } else {
            $category = Category::find($id);
            if($category)
            {
               
                $category->name = $request->input('name');
                $category->description = $request->input('description');
                $category->status = $request->input('status') == true ? 1:0;   

                $category->update();
                return response()->json([
                    'status' => 200,
                    'message' => 'Chỉnh Sửa Thành Công',
                ]);
            }
          
        }
    }
    public function getId($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
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
        $categories=Category::find($id);
        if($categories)
        {
            $categories->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Đã Xóa Thành Công',
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