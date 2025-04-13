<?php

namespace App\Http\Controllers\PmsApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;


class TasksController extends Controller{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        try{
            $perPage = $request->has('take') ? $request->get('take') : 15;
            $list = Task::paginate($perPage);

            return response()->json([
                'status' => true,
                'data' => $list,
                'message' => 'Successfully Retrive'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Internal Error",
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(), [
               'title' => 'required',
               'due_date' => 'required',
               'description' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Fields are required',
                    'errors' => $validator->errors()
                ], 422);
            }
            if (Task::where('title', $request->title)
                    ->where('id', '!=', $request->id)
                    ->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'title has already been taken',
                ], 422);
            }
            $obj = Task::firstOrNew(['id'=>$request->id]);
            $obj->title = $request->title;
            $obj->due_date = $request->due_date;
            $obj->description = $request->description;
            $obj->status = $request->status;
            $obj->save();
            return response()->json([
                'status' => true,
                'message' => 'Task Saved Successfully'
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

    public function detail($id=NULL){
        try{
            $detail = Task::where('id', $id)->first();
            return response()->json([
                'status' => true,
                'data' =>$detail,
                'message' => 'Listed successfully'
            ], 201);

        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateStatus(Request $request, $id){

        try{
            $query = Task::findOrFail($id);
            $request->validate([
                'status'=> 'required|boolean',
            ]);

            $query->status = $request->status;
            if ($query->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Status updated successfully'
                ], 201);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something Went Wrong'
                ], 400);
            }

        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        try{
            $query = Task::findOrFail($id);

            $query->delete();
            return response()->json([
                'status' => true,
                'message' => 'Task Deleted Successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteMultipleRecord(Request $request){
        try{
            $ids = $request->get('ids');
            if(!empty($ids)){
                Task::whereIn('id', $ids)->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully deleted selected records.',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or empty IDs provided.',
                ], 400);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message'=> 'Internal Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteCountry(Request $request){
        try{
            $ids = $request->get('ids');
            if(!empty($ids)){
                Task::whereIn('id', $ids)->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully deleted selected records.',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or empty IDs provided.',
                ], 400);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message'=> 'Internal Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
