<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);




        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',

            'product-list',
            'product-create',
            'product-edit',
            'product-delete',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            'location-list',
            'location-create',
            'location-edit',
            'location-delete',

            'report-list',
            'report-create',
            'report-edit',
            'report-delete',

            'purchase-list',
            'purchase-create',
            'purchase-edit',
            'purchase-delete',

            'installment-list',
            'installment-create',
            'installment-edit',
            'installment-delete',
            'dashboard-view',

            'product-model-list',
            'product-model-create',
            'product-model-edit',
            'product-model-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        // $permissions = [
            
        //     'installment-pay-show',
    
        // ];

        // foreach ($permissions as $permission) {
        //     Permission::firstOrCreate(['name' => $permission]);
        // }


        // DB::table('products')->insert([
        //     ['product_name' => 'Mobile Phone'],
        //     ['product_name' => 'Laptop'],
        //     ['product_name' => 'Refrigerator'],
        //     ['product_name' => 'Television'],
        //     ['product_name' => 'Washing Machine'],
        //     ['product_name' => 'Air Conditioner (AC)'],
        //     ['product_name' => 'Motorcycle'],
        //     ['product_name' => 'Fan'],
        //     ['product_name' => 'Microwave Oven'],
        //     ['product_name' => 'Furniture'],
        //     ['product_name' => 'Smart Watch'],
        //     ['product_name' => 'Inverter'],
        //     ['product_name' => 'Gas Stove'],
        //     ['product_name' => 'Home Theater'],
        //     ['product_name' => 'Desktop Computer'],
        //     ['product_name' => 'Tablet'],
        //     ['product_name' => 'Water Purifier'],
        //     ['product_name' => 'Electric Kettle'],
        //     ['product_name' => 'Pressure Cooker'],
        // ]);
    }
}
