<?php

namespace Database\Seeders;

use App\Models\Currencie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('currencies.json');
        $json = json_decode(file_get_contents($path));
        foreach ($json as $currencie){
            Currencie::create($currencie);
        }
    }
}
