<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use \Maatwebsite\Excel\Sheet;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class LessonHistoriesExport implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents
{
    protected $statistics;
    protected $date_from;
    protected $date_to;

    public function __construct($statistics, $date_from, $date_to)
    {
        $this->statistics = $statistics;
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }


    public function view(): View
    {
        return view('admin.managers.lessons.exports.statistics', [
            'statistics' => $this->statistics, 'date_to' => $this->date_to, 'date_from' => $this->date_from,
        ]);
    }


    public function columnWidths(): array
    {
        return [
            'A' => 9,
            'B' => 25,
            'C' => 25,
            'D' => 14,
            'E' => 12
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $pageSetup = $event->sheet->getDelegate()->getPageSetup();
                //set A4 format
                $pageSetup->setPaperSize(9);

                //merge column
                $headers = $event->sheet->getDelegate()->setMergeCells(['A1:E1', 'A2:E2']);
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(40);
                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(20);

                $headers = $headers->getStyle('A1:E1');
                $headers->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

                //set border for table
                $event->sheet->styleCells(
                    'A4:E' . (4 + sizeof($this->statistics)),
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ]
                    ]
                );
            }
        ];
    }
}
