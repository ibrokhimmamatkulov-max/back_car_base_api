<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Реестр VIN-кодов отдельно от объявлений (решение от 25.09.2026,
 * «может понадобиться» — задел на будущее, не привязан к конкретному
 * экрану). Раньше VIN жил только строкой в performer_transports.VIN
 * и исчезал вместе с объявлением. Отдельная таблица переживает архивацию
 * и удаление объявления, поэтому одну и ту же машину можно будет узнать,
 * даже если её перевыставят под другим аккаунтом.
 *
 * Бита/не бита хранится тут же: это свойство конкретного автомобиля,
 * а не конкретного объявления о нём.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_vins', function (Blueprint $table) {
            $table->id();

            // Верхний регистр, без пробелов — сверка по строке должна находить
            // совпадение независимо от того, как VIN ввели в форме.
            $table->string('vin', 32)->unique();

            // NULL — статус не выясняли. Осознанное «не битый» отличаем от
            // «никто не проверял», поэтому не boolean с default(false).
            $table->boolean('is_damaged')->nullable();
            $table->text('note')->nullable();

            // Последнее объявление и владелец, кто сообщил этот VIN — для
            // прослеживаемости, не для каскадного удаления: запись должна
            // пережить архивацию или удаление самого объявления.
            $table->unsignedBigInteger('performer_transport_id')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();

            $table->timestamps();

            $table->index('is_damaged');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_vins');
    }
};
