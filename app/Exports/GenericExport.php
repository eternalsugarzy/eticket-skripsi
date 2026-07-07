<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Class export Excel generik — dipakai ulang untuk semua jenis laporan.
 * Cukup kirim data baris (array of array) + judul kolom, tidak perlu
 * bikin class export baru tiap ada laporan baru.
 */
class GenericExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected array $rows;
    protected array $headings;
    protected string $judul;

    public function __construct(array $rows, array $headings, string $judul = 'Laporan')
    {
        $this->rows     = $rows;
        $this->headings = $headings;
        $this->judul    = $judul;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function title(): string
    {
        $bersih = preg_replace('/[^A-Za-z0-9 ]/', '', $this->judul);
        return substr($bersih, 0, 31) ?: 'Laporan';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1A3D2B'],
                ],
            ],
        ];
    }
}