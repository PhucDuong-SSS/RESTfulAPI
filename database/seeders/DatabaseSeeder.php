<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        static $password;

        $faker  =Faker::create();
        $gender =$faker->randomElement(['male','female']);
       foreach (range(1,1000) as $index)
        {
            DB::table('users')->insert([
                'name'=> $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password'=> $password?:$password = bcrypt('secret'),
        'remember_token' =>Str::random(10),
        'verified' => $faker->randomElement([User::VERIFIED_USER,User::UNVERIFIED_USER]),
        'verification_token' => User::generateVerificationCode(),
        'admin' => $admin =$faker->randomElement([User::ADMIN_USER, User::REGULAR_USER]),
            ]);
        }

        foreach (range(1,30) as $index)
        {
            DB::table('categories')->insert([
                'name'=> $faker->word,
                'description' => $faker->paragraph(1)
            ]);
        }

        foreach (range(1,1000) as $index)
        {
            $category_id = Category::all()->random()->id;

            DB::table('products')->insert([
                'name'=> $faker->word,
                'description' => $faker->paragraph(1),
                'quantity' => $faker->numberBetween(1,10),
                'status' => $faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
                'image' => $faker->randomElement(['1.jpg','2.jpg','3.jpg']),
                'seller_id'=> User::all()->random()->id,
                'category_id'=> $category_id,

            ]);
            $product= Product::find($index);
            $product->categories()->attach($category_id);

        }

        foreach (range(1,1000) as $index)
        {
            $seller  = Seller::has('products')->get()->random();
             $buyer = User::all()->except($seller->id)->random();
            DB::table('transactions')->insert([
                'quantity'=> $faker->numberBetween(1,3),
                'buyer_id' => $buyer->id,
                'product_id' => $seller->products->random()->id,
            ]);
        }

    }
}
