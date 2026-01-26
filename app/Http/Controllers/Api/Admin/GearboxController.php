<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gearbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GearboxController extends Controller
{
    public function index()
    {
        $gearbox= Gearbox::orderBy('id')->get();
        return response()->json(['data'=>$gearbox]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:gearboxes,code',
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $gearbox= Gearbox::create($validator);

        return response()->json(['data'=>$gearbox]);

    }

    public function show($id)
    {
        $gearbox= Gearbox::find($id);
        if(!$gearbox){
            return response()->json(['message'=>'Not found']);
        }
        return response()->json(['data'=>$gearbox]);
    }

    public function update(Request $request, $id)
    {
        $gearbox = Gearbox::find($id);
        if(!$gearbox){
            return response()->json(['message'=>'Not found']);
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:gearboxes,code,' . $gearbox->id,
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $gearbox->update($validator->validated());

        return response()->json(['data'=>$gearbox]);
    }

    public function destroy($id)
    {
        $gearbox= Gearbox::find($id);
        if(!$gearbox){
            return response()->json(['message'=>'Not found']);
        }
        $gearbox->delete();
        return response()->json([
            'message' => 'Коробка передач удалена'
        ]);
    }
}
