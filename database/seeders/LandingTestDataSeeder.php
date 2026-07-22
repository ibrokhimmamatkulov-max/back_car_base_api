<?php

namespace Database\Seeders;

use App\Models\ApplicationStatus;
use App\Models\CarBrand;
use App\Models\City;
use App\Models\Marka;
use App\Models\PerformerTransport;
use App\Models\RentalTariff;
use Illuminate\Database\Seeder;

class LandingTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::firstOrCreate(
            ['name' => 'Душанбе'],
            ['description' => 'Столица Таджикистана'],
        );

        $brand = CarBrand::firstOrCreate(
            ['name' => 'Toyota'],
            ['is_active' => 1],
        );

        $model = Marka::firstOrCreate(
            ['car_model' => 'Camry', 'car_brand_id' => $brand->id],
            ['name' => 'Camry', 'is_active' => 1],
        );

        ApplicationStatus::firstOrCreate(
            ['code' => 'new'],
            ['name' => 'Новая'],
        );

        $transport = PerformerTransport::firstOrCreate(
            ['car_number' => 'TEST-001'],
            [
                'car_model_id'   => $model->id,
                'city_id'        => $city->id,
                'year_of_issue'  => 2022,
                'count_seat'     => 5,
                'active'         => PerformerTransport::ACTIVE,
                'min_rent_days'  => 1,
                'address'        => 'г. Душанбе, ул. Рудаки 1',
            ],
        );

        $tariffOneDay = RentalTariff::firstOrCreate(
            ['duration_days' => 1],
            ['price' => 150, 'free_weekend_day' => false],
        );

        $tariffWeek = RentalTariff::firstOrCreate(
            ['duration_days' => 7],
            ['price' => 900, 'free_weekend_day' => true],
        );

        $transport->tariffs()->syncWithoutDetaching([$tariffOneDay->id, $tariffWeek->id]);

        $this->command->info("City id={$city->id}, Transport id={$transport->id}");
        $this->command->info("Test: GET /api/landing/offers?city_id={$city->id}");
        $this->command->info("Test: POST /api/landing/apply  { name, phone, city_id:{$city->id}, offer_id:{$transport->id} }");
    }
}
