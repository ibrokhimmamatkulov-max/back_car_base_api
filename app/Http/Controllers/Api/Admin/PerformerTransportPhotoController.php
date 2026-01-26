<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerformerTransportPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PerformerTransportPhotoController extends Controller
{
    public function index($car_id)
    {
        $performerTransportPhoto= PerformerTransportPhoto::where('performer_transport_id', $car_id)->get();
        if(!$performerTransportPhoto){
            return response()->json(['message'=>'Not found']);
        }
        return response()->json(['data'=>$performerTransportPhoto]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'performer_transport_id' => 'required|exists:performer_transports,id',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:4096', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $path = $request->file('photo')->store('cars', 'public');
        // ограничение: максимум 4 фото
        $count = PerformerTransportPhoto::where('performer_transport_id', $validator->validate()['performer_transport_id'])->count();
        if ($count >= 4) {
            return response()->json([
                'message' => 'Максимум 4 фотографии'
            ], 422);
        }

        $performerTransportPhoto= PerformerTransportPhoto::create([
            'performer_transport_id' => $request->performer_transport_id,
            'path' => $path,
        ]);
        return response()->json(['data'=>$performerTransportPhoto]);
    }

    public function destroy($id)
    {
        $performerTransportPhoto= PerformerTransportPhoto::find($id);
        if(!$performerTransportPhoto){
            return response()->json(['message'=>'Not found']);
        }
        Storage::disk('public')->delete($performerTransportPhoto->path);
        $performerTransportPhoto->delete();

        return response()->json([
            'message' => 'Фотография удалена'
        ]);
    }
}
