<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportHelper
{
    public static function excel(
        string $filename,
        string $sheetTitle,
        array $columns,
        $rows
    ): StreamedResponse {
        $rows        = collect($rows)->toArray();
        $totalCols   = count($columns);
        $lastCol     = Coordinate::stringFromColumnIndex($totalCols);
        $fullFilename = $filename . '_' . date('Y-m-d') . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($sheetTitle, 0, 31));

        // ── ROW 1: Judul ──────────────────────────────────────────────────
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', strtoupper($sheetTitle));
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1F2937']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // ── ROW 2: Tanggal Export ─────────────────────────────────────────
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Diekspor pada: ' . date('d F Y, H:i') . ' WIB');
        $sheet->getStyle("A2:{$lastCol}2")->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF6B7280']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF9FAFB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(16);

        // ── ROW 3: Spacer ─────────────────────────────────────────────────
        $sheet->getRowDimension(3)->setRowHeight(6);

        // ── ROW 4: Header Kolom ───────────────────────────────────────────
        foreach ($columns as $i => $col) {
            $cell = Coordinate::stringFromColumnIndex($i + 1) . '4';
            $sheet->setCellValue($cell, $col);
        }
        $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF111827']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1D5DB']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(22);

        // ── ROW 5+: Data ──────────────────────────────────────────────────
        foreach ($rows as $rowIndex => $row) {
            $excelRow = 5 + $rowIndex;
            $bgColor  = $rowIndex % 2 === 0 ? 'FFFFFFFF' : 'FFF3F4F6';

            foreach (array_values($row) as $colIndex => $value) {
                $cell = Coordinate::stringFromColumnIndex($colIndex + 1) . $excelRow;
                $sheet->setCellValue($cell, $value);
            }

            $sheet->getStyle("A{$excelRow}:{$lastCol}{$excelRow}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
                'font'      => ['size' => 9],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE5E7EB']]],
            ]);
            $sheet->getRowDimension($excelRow)->setRowHeight(16);
        }

        // ── ROW TOTAL ─────────────────────────────────────────────────────
        if (!empty($rows)) {
            $totalRow = 5 + count($rows);
            $sheet->mergeCells("A{$totalRow}:{$lastCol}{$totalRow}");
            $sheet->setCellValue("A{$totalRow}", 'Total: ' . count($rows) . ' data');
            $sheet->getStyle("A{$totalRow}:{$lastCol}{$totalRow}")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 9, 'color' => ['argb' => 'FF374151']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE5E7EB']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ]);
        }

        // ── AUTO-WIDTH & FREEZE ───────────────────────────────────────────
        foreach (range(1, $totalCols) as $colIndex) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($colIndex))->setAutoSize(true);
        }
        $sheet->freezePane('A5');

        // ── DOWNLOAD ──────────────────────────────────────────────────────
        $writer = new Xlsx($spreadsheet);

        return response()->stream(
            fn() => $writer->save('php://output'),
            200,
            [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$fullFilename}\"",
                'Cache-Control'       => 'max-age=0',
                'Pragma'              => 'no-cache',
            ]
        );
    }

    // Fallback CSV (tetap ada)
    public static function csv(string $filename, array $columns, $rows): StreamedResponse
    {
        $fullFilename = $filename . '_' . date('Y-m-d') . '.csv';
        return response()->stream(function () use ($columns, $rows) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fullFilename}\"",
        ]);
    }
}