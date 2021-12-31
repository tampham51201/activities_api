<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\RegisterActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class RegisterActivityController extends Controller
{
    //

    public function indexAll()
    {
        
        $RegisterActivity=RegisterActivity::all();
        return response()->json([
            'status'=>200,
            'registerActivity'=>$RegisterActivity,
        ]);
    }
    public function indexAllUser()
    {
        if(auth('sanctum')->check())
        {
            $user_id=auth('sanctum')->user()->id;
            $RegisterActivity=RegisterActivity::where('id_user',$user_id)->get();
            return response()->json([
            'status'=>200,
            'registerActivity'=>$RegisterActivity,
        ]);
            
        }
       
       
    }
    public function index()
    {
       
        $sql = " SELECT AC.id,AC.title,AC.time_start_activity,AC.time_end_activity, AC.quantity ,AC.status, COUNT(RA.id_activity) AS quantity_register ,AC.img ,AC.description,AC.point
        FROM register_activities AS RA
        LEFT JOIN activities AS AC
        ON AC.id = RA.id_activity
        GROUP BY AC.id,AC.title,AC.status,AC.time_start_activity,AC.time_end_activity, AC.quantity ,AC.img ,AC.description,AC.point";
       
        $register_list = DB::select($sql);
     
    
        return response()->json([
            'status'=>200,
            'register_list'=>$register_list,
        ]);
    }
    public function getId($id)
    {
        $register_list = RegisterActivity::where('id_activity',$id)->get();
     
        return response()->json([
            'status'=>200,
            'register_list'=>$register_list,
        ]);
    }
    public function update_status(Request $request)
    {
        $id_activity=$request->input('id_activity');
        $id_user=$request->input('id_user');
        $user=auth('sanctum')->user()->id;
     


        $user_point= User::find($id_user)->point;
        $activity_point = Activities::find($id_activity)->point;
        $user_add = User::find($id_user);

 
        
  
       if( $request->input('status')==0)
       {
        $sql = "UPDATE register_activities SET `status` = 1 ,`id_approver`= $user WHERE id_activity = $id_activity AND id_user =$id_user";
        DB::update($sql);
        if($user_add)
        {     
            $user_add->point =  $user_point + $activity_point;
            $user_add->update();

        }
        return response()->json([
            'status' => 200,
            'message' => 'Duyệt Thành Công',
        ]);
       }
       if( $request->input('status')==1)
       {
        $sql = "UPDATE register_activities SET `status` = 0, `id_approver`= null WHERE id_activity = $id_activity AND id_user =$id_user";
        DB::update($sql);

        if($user_add)
        {
           
            $user_add->point =  $user_point - $activity_point;
            $user_add->update();
  
        }
        return response()->json([
            'status' => 200,
            'message' => 'Hoàn Tác Duyệt Thành Công',
        ]);
       }
    }

    public function register_activity($id)
    {
        
        $user=auth('sanctum')->user()->id;

     
 
        $registerActivity = new RegisterActivity();
      
        $registerActivity->id_activity =$id ;
        $registerActivity->id_user = $user;
        $registerActivity->save();

        $activity = Activities::find($id);

        if($activity)
        {     
            $activity->quantity_register = $activity->quantity_register + 1;
            $activity->update();

        }

        return response()->json([

            'status' => 200,
            'message' => 'Đăng Kí Thành Công!',
        ]);

       }
       public function destroy(Request $request)
       {
        $id_activity=$request->input('id_activity');
        $id_user=$request->input('id_user');
        
        $sqlDelete="DELETE FROM `register_activities` WHERE id_activity = $id_activity AND id_user =$id_user";
   
        DB::delete($sqlDelete);

        $activity = Activities::find($id_activity);

        if($activity)
        {     
            $activity->quantity_register = $activity->quantity_register - 1;
            $activity->update();

        }
        return response()->json([
            'status'=>200,
            'message'=>'Đã Xóa Thành Công',
        ]);
          
      
          
       }
    
}