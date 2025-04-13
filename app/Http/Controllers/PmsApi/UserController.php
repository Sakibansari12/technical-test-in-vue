<?php

namespace App\Http\Controllers\PmsApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller{
    
    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(), [
               'name' => 'required',
               'email' => 'required',
               'mobile' => 'required',
               'password' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Fields are required',
                    'errors' => $validator->errors()
                ], 422);
            }

            if (User::where('email', $request->email)
                    ->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User Register has already been taken',
                ], 422);
            }

            $obj = User::firstOrNew(['id'=>$request->id]);
            $obj->name = $request->name;
            $obj->email = $request->email;
            $obj->mobile = $request->mobile;
            $obj->password = $request->password;
            $obj->role = 'Admin';
            $obj->status = 1;
            $obj->save();
            return response()->json([
                'status' => true,
                'message' => 'User Register Successfully'
            ], 200);

            return response([
                'status' => true,
                'message' => 'Added Successfully',
                'last_insert_id' => $SQL->id
            ], 200);

        }catch(\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Error!, please try again later.',
                'error' => $e->getMessage()
            ], 400);
        }

        return response([
            'status' => false,
            'message' => 'Internal error, please try again later.'
        ], 401);
    }


}
