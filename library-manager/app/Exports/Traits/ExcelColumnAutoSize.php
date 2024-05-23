<?php
namespace App\Exports\Traits;
use Maatwebsite\Excel\Events\AfterSheet;

trait ExcelColumnAutoSize
{
    /**
     *
     * @param \Maatwebsite\Excel\Events\AfterSheet $event
     */
    public function autoSizeColumns(AfterSheet $event)
    {
        $worksheet = $event->sheet->getDelegate();
        $highestRow = $worksheet->getHighestRow(); 
        $highestColumn = $worksheet->getHighestColumn(); 
    
        // Convert highest column letter to an integer (1-based index)
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
    
        // Loop through all columns using index
        for ($columnIndex = 1; $columnIndex <= $highestColumnIndex; $columnIndex++) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
    
            $maxWidth = 0;
            for ($row = 1; $row <= $highestRow; $row++) {
                $cellValue = $worksheet->getCell($columnLetter . $row)->getValue();
                $length = mb_strlen($cellValue);
                if ($length > $maxWidth) {
                    $maxWidth = $length;
                }
            }
    
            if($maxWidth > 40)
                $maxWidth = 40;
    
            $worksheet->getColumnDimension($columnLetter)->setWidth($maxWidth + 2); // Adjust width plus buffer*/
        }
    
        $tableRange = 'A1:' . $highestColumn . $highestRow;
        $event->sheet->setAutoFilter($tableRange);
        $worksheet->getStyle($tableRange)->getAlignment()->setWrapText(true);
    }
}