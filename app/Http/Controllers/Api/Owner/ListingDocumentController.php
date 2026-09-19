<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\OwnerDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Снимки техпаспорта.
 *
 * Хранятся на приватном диске и наружу не отдаются: в объявлении документы
 * не показываются (ТЗ §4), их видит только менеджер при проверке. Владелец
 * получает лишь имя файла и статус — по ним он понимает, что загружено
 * и чем закончилась проверка.
 */
class ListingDocumentController extends Controller
{
    /** Два разворота техпаспорта — больше для сверки VIN не нужно */
    private const MAX_DOCUMENTS = 4;

    private const TYPE = 'vehicle_passport';

    public function index(Request $request, int $id): JsonResponse
    {
        $owner = $request->user('owner');

        $documents = OwnerDocument::where('performer_transport_id', $id)
            ->where('owner_id', $owner->id)
            ->orderBy('id')
            ->get()
            ->map(fn ($d) => $this->present($d));

        return $this->success($documents);
    }

    public function store(Request $request, int $id): JsonResponse
    {
        $owner = $request->user('owner');

        $validator = Validator::make($request->all(), [
            'documents'   => 'required|array|min:1',
            'documents.*' => 'file|mimes:jpeg,jpg,png,webp,pdf|max:5120',
        ], [
            'documents.required' => 'Приложите снимки техпаспорта.',
            'documents.*.max'    => 'Каждый файл — не больше 5 МБ.',
            'documents.*.mimes'  => 'Допустимые форматы: JPG, PNG, WebP, PDF.',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $existing = OwnerDocument::where('performer_transport_id', $id)
            ->where('owner_id', $owner->id)
            ->count();

        $incoming = count($request->file('documents'));

        if ($existing + $incoming > self::MAX_DOCUMENTS) {
            return $this->error(
                'Больше ' . self::MAX_DOCUMENTS . ' файлов загрузить нельзя. '
                . "Сейчас загружено {$existing}.",
                422
            );
        }

        $created = [];

        foreach ($request->file('documents') as $file) {
            // Диск 'local' приватный: файл не попадёт в публичное хранилище,
            // как попадают фотографии машины.
            $path = $file->store("documents/{$owner->id}", 'local');

            $document = OwnerDocument::create([
                'owner_id'               => $owner->id,
                'performer_transport_id' => $id,
                'type'                   => self::TYPE,
                'path'                   => $path,
                'original_name'          => $file->getClientOriginalName(),
                'status'                 => OwnerDocument::STATUS_PENDING,
            ]);

            $created[] = $this->present($document);
        }

        return $this->success($created, 'Документы отправлены на проверку.', 201);
    }

    public function destroy(Request $request, int $id, int $documentId): JsonResponse
    {
        $owner = $request->user('owner');

        $document = OwnerDocument::where('performer_transport_id', $id)
            ->where('owner_id', $owner->id)
            ->where('id', $documentId)
            ->first();

        if (!$document) {
            return $this->error('Документ не найден.', 404);
        }

        // Одобренный документ — основание для отметки «VIN проверен».
        // Позволить удалить его значит оставить отметку без основания.
        if ($document->status === OwnerDocument::STATUS_APPROVED) {
            return $this->error('Проверенный документ удалить нельзя.', 422);
        }

        Storage::disk('local')->delete($document->path);
        $document->delete();

        return $this->success(null, 'Документ удалён.');
    }

    /** Наружу — без пути к файлу: он ведёт в приватное хранилище */
    private function present(OwnerDocument $document): array
    {
        return [
            'id'            => $document->id,
            'original_name' => $document->original_name,
            'status'        => $document->status,
            'comment'       => $document->comment,
            'uploaded_at'   => $document->created_at?->toIso8601String(),
        ];
    }
}
