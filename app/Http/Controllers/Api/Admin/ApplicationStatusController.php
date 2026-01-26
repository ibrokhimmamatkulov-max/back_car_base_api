<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationStatusController extends Controller
{
    public function index()
    {
        $application_status= ApplicationStatus::orderBy('id')->get();
        return response()->json(['data'=>$application_status]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:application_statuses,code',
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $application_status= ApplicationStatus::create($validator->validated());
        return response()->json(['data'=>$application_status]);
    }

    public function show($id)
    {
        $application_status= ApplicationStatus::find($id);
        if(!$application_status){
            return response()->json(['message'=>'Not found']);
        }
        return response()->json(['data'=>$application_status]);
    }

    public function update(Request $request, $id)
    {
        $application_status = ApplicationStatus::find($id);
        
        if(!$application_status){
            return response()->json(['message'=>'Not found']);
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:application_statuses,code,' . $application_status->id,
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $application_status->update($validator->validated());

        return response()->json(['data'=>$application_status]);
    }

    public function destroy($id)
    {
       $application_status= ApplicationStatus::find($id);
        if(!$application_status){
            return response()->json(['message'=>'Not found']);
        }
       $application_status->delete();

        return response()->json([
            'message' => 'Статус заявки удалён'
        ]);
    }
}
