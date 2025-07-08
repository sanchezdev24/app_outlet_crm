<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Categorías principales
        $categories = [
            ['name' => 'Ropa', 'description' => 'Toda la ropa de outlet'],
            ['name' => 'Calzado', 'description' => 'Zapatos y zapatillas'],
            ['name' => 'Accesorios', 'description' => 'Bolsos, cinturones, etc.'],
            ['name' => 'Deportes', 'description' => 'Ropa y accesorios deportivos'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Subcategorías
        $ropaId = Category::where('name', 'Ropa')->first()->id;
        $subcategories = [
            ['name' => 'Camisetas', 'description' => 'Camisetas para hombre y mujer', 'parent_id' => $ropaId],
            ['name' => 'Pantalones', 'description' => 'Pantalones y jeans', 'parent_id' => $ropaId],
            ['name' => 'Vestidos', 'description' => 'Vestidos para mujer', 'parent_id' => $ropaId],
        ];

        foreach ($subcategories as $subcategory) {
            Category::create($subcategory);
        }
    }
}