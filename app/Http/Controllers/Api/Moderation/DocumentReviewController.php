<?php

namespace App\Http\Controllers\Api\Moderation;

use App\Http\Controllers\Controller;
use App\Models\OwnerDocument;
use App\Models\PerformerTransport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Проверка техпаспорта менеджером.
 *
 * Отметка «VIN проверен» — единственное, что на витрине подтверждает, что
 * машину сдаёт её настоящий владелец. До этого контроллера поле
 * vin_verified было объявлено в модели, но никем не выставлялось: снимки
 * техпаспорта лежали в базе, а ставить отметку было некому и нечем.
 */
class DocumentReviewController extends Controller
{
    public function index(Request $request, int $id): JsonResponse
    {
        $listing = PerformerTransport::find($id);

        if (!$listing) {
            return $this->error('Объявление не найдено.', 404);
        }

        $documents = OwnerDocument::where('performer_transport_id', $id)
            ->orderBy('id')
            ->get()
            ->map(fn ($d) => [
                'id'            => $d->id,
                'original_name' => $d->original_name,
                'status'        => $d->status,
                'comment'       => $d->comment,
                'uploaded_at'   => $d->created_at?->toIso8601String(),
            ]);

        return $this->success([
            'vin_verified'    => (bool) $listing->vin_verified,
            'vin_verified_at' => $listing->vin_verified_at?->toIso8601String(),
            'documents'       => $documents,
        ]);
    }

    /**
     * Отдаёт сам файл.
     *
     * Документы лежат на приватном диске и по прямой ссылке недоступны —
     * иначе адрес снимка чужого техпаспорта утекал бы вместе с объявлением.
     */
    public function show(Request $request, int $id, int $documentId)
    {
        $document = OwnerDocument::where('performer_transport_id', $id)
            ->where('id', $documentId)
            ->first();

        if (!$document || !Storage::disk('local')->exists($document->path)) {
            return $this->error('Документ не найден.', 404);
        }

        return Storage::disk('local')->response($document->path, $document->original_name);
    }

    public function verify(Request $request, int $id): JsonResponse
    {
        $listing = PerformerTransport::find($id);

        if (!$listing) {
            return $this->error('Объявление не найдено.', 404);
        }

        $pending = OwnerDocument::where('performer_transport_id', $id)->count();

        if ($pending === 0) {
            return $this->error('По объявлению нет загруженных документов.', 422);
        }

        DB::transaction(function () use ($listing, $id) {
            OwnerDocument::where('performer_transport_id', $id)
                ->update([
                    'status'  => OwnerDocument::STATUS_APPROVED,
                    'comment' => null,
                ]);

            $listing->forceFill([
                'vin_verified'    => true,
                'vin_verified_at' => now(),
            ])->save();
        });

        return $this->success([
            'vin_verified'    => true,
            'vin_verified_at' => $listing->fresh()->vin_verified_at?->toIso8601String(),
        ], 'VIN подтверждён.');
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:500',
        ], [
            'comment.required' => 'Укажите, что не так с документами — владелец увидит эту причину.',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $listing = PerformerTransport::find($id);

        if (!$listing) {
            return $this->error('Объявление не найдено.', 404);
        }

        $comment = (string) $request->input('comment');

        DB::transaction(function () use ($listing, $id, $comment) {
            OwnerDocument::where('performer_transport_id', $id)
                ->update([
                    'status'  => OwnerDocument::STATUS_REJECTED,
                    'comment' => $comment,
                ]);

            // Отметку снимаем: раз документы отклонены, подтверждать нечем.
            $listing->forceFill([
                'vin_verified'    => false,
                'vin_verified_at' => null,
            ])->save();
        });

        return $this->success(null, 'Документы отклонены.');
    }
}
