<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class QuestionExport implements  FromArray, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


        // Optionally, add styles to the Excel sheet
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Style the question text (bold, centered)
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Style the table headers (bold, centered)
                $sheet->getStyle('A2:C2')->getFont()->setBold(true);
                $sheet->getStyle('A2:C2')->getAlignment()->setHorizontal('center');

                // Style the table rows (borders)
                $sheet->getStyle('A3:C' . ($event->sheet->getHighestRow()))
                      ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }

    // The array method to format the data for export
    public function array(): array
    {
        $exportData = [];
        
        // Add the question
        $exportData[] = ['Question: ' . $this->data['question']];
        $exportData[] = [];  // Empty row for spacing
        
        // Add table headers
        $exportData[] = ['Answer Text', 'Total', 'Percent'];
        
        // Add answers and their data
        foreach ($this->data['statisticsData'] as $answer) {
            $exportData[] = [$answer['text'], $answer['total'], $answer['percent']];
        }

        return $exportData;
    }
}
