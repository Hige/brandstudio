<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporter extends AbstractExporter
{
    public function export()
    {
        Excel::create('Filename', function($excel) {

            $excel->sheet('Sheetname', function(LaravelExcelWorksheet $sheet) {

                $this->chunk(function ($records) use ($sheet) {

                    $rows = $records->map(function ($item) {

                        $object = $item->toArray();

                        foreach($object as $key => $val) {
                            if(is_array($val) || is_object($val)) {
                                unset($object[$key]);
                            }
                        }

                        return $object;
                    });

                    $sheet->rows($rows);

                });

            });

        })->export('xls');
    }
}