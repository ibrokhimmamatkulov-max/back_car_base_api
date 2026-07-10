<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerformerTransportPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PerformerTransportPhotoController extends Controller
{
    public function index(int $car_id)
    {
        $photos = PerformerTransportPhoto::where('performer_transport_id', $car_id)->get();
        return $this->success($photos);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'performer_transport_id' => 'required|exists:performer_transports,id',
            'photo'                  => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $transport_id = $validator->validated()['performer_transport_id'];
        $count = PerformerTransportPhoto::where('performer_transport_id', $transport_id)->count();
        if ($count >= 4) {
            return $this->error('Максимум 4 фотографии', 422);
        }

        $path = $request->file('photo')->store('cars', 'public');
        $photo = PerformerTransportPhoto::create([
            'performer_transport_id' => $transport_id,
            'path'                   => $path,
        ]);

        return $this->success($photo, 'Фото загружено', 201);
    }

    public function destroy(int $id)
    {
        $photo = PerformerTransportPhoto::find($id);
        if (!$photo) {
            return $this->error('Фото не найдено', 404);
        }
        Storage::disk('public')->delete($photo->path);
        $photo->delete();
        return $this->success(null, 'Фотография удалена');
    }
}
