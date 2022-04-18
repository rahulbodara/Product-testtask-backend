<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            "user_id" => Auth::id(),
            "auther_name" => "Chelsea Lee",
            "name" => "Shild",
            "description" => "This is first product.",
            "amount" => "5000"
        ]);
    }
}
