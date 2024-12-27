<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class ResponsesExport implements FromArray
{
    protected $responses;

    public function __construct($responses)
    {
        $this->responses = $responses;
    }

    // Optionally, add styles to the Excel sheet
   

    // The array method to format the data for export
    public function array(): array
    {
        
        
        // Add table headers
        $exportData[] = ['date','source','completion_avg'];
        
        // Add answers and their data
        foreach ($this->responses as $res) {
            $exportData[] = [$res['created_at'],$res['device']['name'],round($res['completion_avg'],2)];
        }
       
        return $exportData;
    }
}
