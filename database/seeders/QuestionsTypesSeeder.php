<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Make sure Carbon is imported

class QuestionsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
            DB::table('questions_types')->insert([
                [
                    'category_id' => 1,
                    'type_text' => 'logic_question',
                    'question_type_details' => 'Logic Question',
                    'image' => env('APP_URL').'/images/questions_types/English/Yes_or_No_Question.png',
                    'question_type_details_ar' => 'سؤال منطقي',
                    
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'category_id' => 3,
                    'type_text' => 'rating',
                    'question_type_details' => 'Rating 1-5',
                    'image' => env('APP_URL').'/images/questions_types/English/Rating.png',
                    'question_type_details_ar' => 'تقييم 1-5',
                    
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'category_id' => 3,
                    'type_text' => 'satisfaction',
                    'question_type_details' => 'Satisfaction Level',
                    'image' => env('APP_URL').'/images/questions_types/English/Satisfaction_Level.png',
                    'question_type_details_ar' => 'مستوى الرضى',
                    
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'category_id' => 2,
                    'type_text' => 'mcq',
                    'question_type_details' => 'Multi Option (single answer)',
                    'image' => env('APP_URL').'/images/questions_types/English/Multi-Option_(single answer).png',
                    'question_type_details_ar' => 'متعدد الإختيارات (إجابة واحدة)',
                    
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'category_id' => 2,
                    'type_text' => 'checkbox',
                    'question_type_details' => 'Multi Option (multi answer)',
                    'image' => env('APP_URL').'/images/questions_types/English/Multi-Option_(multi answer).png',
                    'question_type_details_ar' => 'متعدد الإجابات (أكثر من إجابة)',
                  
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                
                
                
               
                [
                    'category_id' => 4,
                    'type_text' => 'short_text_question',
                    'question_type_details' => 'Short Text',
                    'image' => env('APP_URL').'/images/questions_types/English/Short_Text_Question.png',
                    'question_type_details_ar' => 'نص قصير',
                   
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'category_id' => 4,
                    'type_text' => 'email',
                    'question_type_details' => 'Email Address',
                    'image' => env('APP_URL').'/images/questions_types/English/Email.png',
                    'question_type_details_ar' => 'البريد الإلكتروني',
                
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'category_id' => 4,
                    'type_text' => 'long_text_question',
                    'question_type_details' => 'Long Text',
                    'image' => env('APP_URL').'/images/questions_types/English/LongTextQuestion.png',
                    'question_type_details_ar' => 'نص طويل',
                   
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'category_id' => 5,
                    'type_text' => 'date_question',
                    'question_type_details' => 'Select a Date',
                    'image' => env('APP_URL').'/images/questions_types/English/Date_Question.png',
                    'question_type_details_ar' => 'حدد تاريخ',
                    
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'category_id' => 4,
                    'type_text' => 'number',
                    'question_type_details' => 'Mobile Number',
                    'image' => env('APP_URL').'/images/questions_types/English/Number.png',
                    'question_type_details_ar' => 'رقم الموبايل',
                    
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                
                [
                  'category_id' => 6,
                    'type_text' => 'drawing',
                    'question_type_details' => 'Signature',
                    'image' => env('APP_URL').'/images/questions_types/English/Drawing.png',
                    'question_type_details_ar' => 'توقيع',
                
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
                ]);
    
    
           
        
    }
}
