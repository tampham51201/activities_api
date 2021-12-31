<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;



class ActivityController extends Controller
{
    public function index()
    {
        $activities=Activities::all();
        return response()->json([
            'status'=>200,
            'activities'=>$activities,
        ]);
    }
    public function getTop()
    {
        $sql = "SELECT TOP 3 * FROM activities ORDER BY point";
        $activities = DB::select($sql);
        return response()->json([
            'status'=>200,
            'activities'=>$activities,
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

    

            $activity = new Activities();
            $activity->title = $request->input('title');
            $activity->quantity = $request->input('quantity');
            $activity->description = $request->input('description');

            $activity->point = $request->input('point');
            $activity->category_id = $request->input('category_id');

            $activity->time_start_activity = $request->input('time_start');
            $activity->time_end_activity = $request->input('time_end');

            $activity->time_start_register = $request->input('time_register_start');
            $activity->time_end_register = $request->input('time_register_end');


            $activity->status = $request->input('status') == true ? 1 : 0;   

            if ($request->hasFile('img')) {
              
                $file = $request->file('img');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('upload/activity/', $filename);
                $activity->img = 'upload/activity/' . $filename;
            }

            
            $activity->save();
            return response()->json([
                'status' => 200,
                'message' => 'Thêm Hoạt Động Thành Công!',
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
            $activity = Activities::find($id);
            if ($activity) {
                $activity->title = $request->input('title');
            $activity->quantity = $request->input('quantity');
            $activity->description = $request->input('description');

            $activity->point = $request->input('point');
            $activity->category_id = $request->input('category_id');
            
            $activity->status = $request->input('status') == true ? 1 : 0;   

            $activity->time_start_activity = $request->input('time_start');
            $activity->time_end_activity = $request->input('time_end');

            $activity->time_start_register = $request->input('time_register_start');
            $activity->time_end_register = $request->input('time_register_end');

                if ($request->hasFile('img')) {
                    $path = $activity->img;
                    if(File::exists($path))
                    {
                        File::delete($path);
    
                    }
                  
                    $file = $request->file('img');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('upload/activity/', $filename);
                    $activity->img = 'upload/activity/' . $filename;

             
                }

               
                $activity->update();
                return response()->json([
                    'status' => 200,
                    'message' => 'Chỉnh Sửa Hoạt Động Thành Công',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Category ID Found',
                ]);
            }


          
        }
    }
    public function getId($id)
    {
        $activity = Activities::find($id);
        if ($activity) {
            return response()->json([
                'status' => 200,
                'activity' => $activity,
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
        $activity=Activities::find($id);
        if($activity)
        {
            $activity->delete();
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