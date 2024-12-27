<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Make sure Carbon is imported

class QuestionsCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
            DB::table('questions_categories')->insert([
                ['category_name' => 'Logic Question','category_name_ar' => 'الأسئلة المنطقية', 'created_at' => now(), 'updated_at' => now()],
                ['category_name' => 'Multiple Options','category_name_ar' => 'الأسئلة متعددة الإختيارات', 'created_at' => now(), 'updated_at' => now()],
                ['category_name' => 'Rating & Satisfaction','category_name_ar' =>'التقييم و الرضى' , 'created_at' => now(), 'updated_at' => now()],
                ['category_name' => 'Text Question','category_name_ar' => 'أسئلة النصوص' , 'created_at' => now(), 'updated_at' => now()],
                ['category_name' => 'Date','category_name_ar' =>'تاريخ' , 'created_at' => now(), 'updated_at' => now()],
                ['category_name' => 'Drawing','category_name_ar' =>'رسم' , 'created_at' => now(), 'updated_at' => now()],
            
            ]);
        
    }
}
