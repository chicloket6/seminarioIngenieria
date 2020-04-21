<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use App\Models\Reservacion;

class Reporte implements FromView, WithColumnFormatting, WithMapping, ShouldAutoSize
{
    public function view(): View
    {
        return view('exports.reportes', [
            'reservaciones' => Reservacion::all()
        ]);
    }

    public function map($invoice): array
    {
        return [
            Date::dateTimeToExcel($invoice->fecha_entrada),
            Date::dateTimeToExcel($invoice->fecha_salida),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
}
