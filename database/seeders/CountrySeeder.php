<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/countries.json");
        $countriesObj = json_decode($json);
        $countries = [];
        foreach ($countriesObj as $key => $country) {
            $newCountry = Country::updateOrcreate([
                'code' => $country->code ?? $country->alpha2,
                'alpha2' => $country->alpha2 ?? null,
                'alpha3' => $country->alpha3 ?? null,
                'country_calling_codes' => json_decode($country->country_calling_codes) ?? null,
                'currencies' => json_decode($country->currencies) ?? null,
                'emoji' => $country->emoji ?? null,
                'ioc' => $country->ioc ?? null,
                'languages' => json_decode($country->languages) ?? null,
                'name' => $country->name ?? null,
                'status' => $country->status ?? null,
                'zone_id' => $country->zone_id ?? null,
                'zone_name' => $country->zone_name ?? null,
            ]);
            array_push($countries, $newCountry);
        }

        Cache::rememberForever('countries', function () use ($countries) {
            return $countries;
        });
    }
}
