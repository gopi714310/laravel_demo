<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class TableDataExport implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $data;
    protected $columns;

    public function __construct(Collection $data, array $columns)
    {
        $this->data = $data;
        $this->columns = $columns;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function columnFormats(): array
    {
        // Define the column formats here
        // For example, to display images in column 'B', use 'B' => '0';
        // '0' format code will display the image without the path
        return [
            'B' => '0',
            // Add more column formats as needed
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Define styles for the worksheet here
        // For example, set the width of column 'B' to display the image properly
        return [
            'B' => [
                'width' => 50,
            ],
            // Add more styles as needed
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $data = $event->sheet->getDelegate()->toArray();
                $highestRow = $event->sheet->getHighestRow();

                foreach ($data as $row => $rowData) {
                    if ($row > 0) {
                        $photoColumn = array_search('photo', $rowData);
                        if ($photoColumn !== false) {
                            $photoPath = $rowData[$photoColumn];

                            $drawing = new Drawing();
                            $drawing->setPath(public_path('storage/' . $photoPath));
                            $drawing->setWidthAndHeight(100, 100);
                            $drawing->setCoordinates('K' . ($row + 1));
                            $drawing->setWorksheet($event->sheet->getDelegate());
                        }
                    }
                }
            }
        ];
    }
}
