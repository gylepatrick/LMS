<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Reports_Library extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Library_model');
    }

    public function generate_excel_subsidiary($item_code = null) {
        // Get inputs
        $item_code = $this->input->get('item_code');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $school_name = $this->input->get('schl_name');
        $school_address = $this->input->get('schl_address');
        
        // Fetch data
        $offices = $this->PPE_model->get_inventory_ppe($item_code, $start_date, $end_date);
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // **Set Title and Address (Left-Aligned)**
        $sheet->setCellValue('A1', $school_name);
        $sheet->setCellValue('A2', $school_address);
        $sheet->setCellValue('A4', 'SUBSIDIARY LEDGER REPORT')->getStyle('A4')->getFont()->setBold(true)->getColor()->setRGB('0000');
    
        // **Date Range**
        $date_range_text = "From " . (!empty($start_date) ? date('F j, Y', strtotime($start_date)) : 'N/A') .
                   " to " . (!empty($end_date) ? date('F j, Y', strtotime($end_date)) : 'N/A');
    
        $sheet->setCellValue('A5', $date_range_text);
    
        // **Display Item Name (if filtered)**
        if ($item_code) {
            $sheet->setCellValue('A6',  $item_code);
        }
    
        // **Table Headers**
        $sheet->mergeCells('N8:O8');
        $sheet->setCellValue('N8', 'Balance');
        $sheet->getStyle('N8')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('N8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Define headers (Row 9)
        $headers = [
            'Date Purchased', 'Date Entered', 'Transaction', 'Item Code', 'Description', 'Brand',
            'Supplier', 'Requesting Office', 'Location', 'Unit', 'Quantity', 'Unit Cost', 'Amount',
            'Stock Status', 'Entering Personnel'
        ];

            
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '8', $header);
            $col++;
        }
    
        // **Style Headers**
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        $borderStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        $sheet->setCellValue('N9', 'Qty.');
        $sheet->setCellValue('O9', 'Amt.');
        $sheet->getStyle('A8:Q8')->applyFromArray($headerStyle);

        // Apply styling to headers
        $sheet->getStyle('N9:O9')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
            
        // **Group Data by Item Code**
        $grouped_data = [];

        $row = $startingRow;
        foreach ($offices as $t) {
            $grouped_data[$t->item_code][] = $t;
        }
    
        // Populate Data for Each Item Code**
        $row = 10;
        foreach ($grouped_data as $item_code => $transactions) {
            
    
            // INIT BALANCE
            $balances = [];
    
            foreach ($transactions as $t) {
                $unitCost = ($t->quantity > 0) ? number_format($t->total / $t->quantity, 2) : 0;
    
                if (!isset($balances[$t->item_code][$t->batch_number])) {
                    $balances[$t->item_code][$t->batch_number] = ['quantity' => 0, 'total' => 0, 'unit_cost' => 0];
                }
    
                // PREV BALANCE DISPLAY FIRST
                $previousBalance = $balances[$t->item_code][$t->batch_number]['quantity'];
    
                // TRANSACTION PROCESS
                if ($t->type == 'Purchase' || $t->type == 'Donation') {
                    $balances[$t->item_code][$t->batch_number]['quantity'] += $t->quantity;
                    $balances[$t->item_code][$t->batch_number]['total'] += $t->total;
                } elseif ($t->type == 'Issuance') {
                    $balances[$t->item_code][$t->batch_number]['quantity'] -= $t->quantity;
                    $balances[$t->item_code][$t->batch_number]['total'] -= $t->total;
                }
    
                // UPDATE UNIT COST
                $balances[$t->item_code][$t->batch_number]['unit_cost'] = 
                    ($balances[$t->item_code][$t->batch_number]['quantity'] > 0) 
                    ? $balances[$t->item_code][$t->batch_number]['total'] / $balances[$t->item_code][$t->batch_number]['quantity'] 
                    : 0;
    
                // CALCULATE BALANCE AFTER ISSUANCES
                $currentBalance = $balances[$t->item_code][$t->batch_number]['quantity'];
                $unitCost = ($t->quantity > 0) ? round($t->total / $t->quantity, 2) : 0;

    
                // CHECK STOCK IF IN STOCK OR OUT OF STOCK
                $status = ($currentBalance <= 0) ? 'Out of Stock' : 'In Stock';
                //CHECK TYPE FOR IPURCHASED DATE DISPLAY
                $enterDate = ($t->type == "Issuance" || $t->type == "Disposal") ? '' : $t->purchased_date;  
    
                $sheet->setCellValue('A' . $row, $enterDate);
                $sheet->setCellValue('B' . $row, $t->entered_date);
                $sheet->setCellValue('C' . $row, $t->type);
                $sheet->setCellValue('D' . $row, $t->item_code);
                $sheet->setCellValue('E' . $row, $t->discription);
                $sheet->setCellValue('F' . $row, $t->brand);
                $sheet->setCellValue('G' . $row, $t->supplier);
                $sheet->setCellValue('H' . $row, $t->requesting_office);
                $sheet->setCellValue('I' . $row, $t->location);
                $sheet->setCellValue('J' . $row, $t->unit);
                $sheet->setCellValue('K' . $row, $t->quantity);
                $sheet->setCellValue('L' . $row, number_format($unitCost, 2));
                $sheet->setCellValue('M' . $row, number_format($t->total, 2));
                $sheet->setCellValue('O' . $row, number_format($balances[$t->item_code][$t->batch_number]['total'], 2));
                $sheet->setCellValue('Q' . $row, $t->entered_by);
                

                $sheet->getStyle("N$row:O$row")->getFont()->setBold(true);

                
                $sheet->getStyle("B9:Q9")->applyFromArray([
                    'borders' => ['ALL' => ['borderStyle' => Border::BORDER_THICK]]
                ]);

                $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('M' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('O' . $row)->getNumberFormat()->setFormatCode('#,##0.00');


                // Define style for highlighting specific columns
                $highlightStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => '000000']], // White text
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFF00'], // Blue background
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ];

                $sheet->getStyle("J$row")->applyFromArray($highlightStyle); // Unit
                $sheet->getStyle("K$row")->applyFromArray($highlightStyle); // Quantity
                $sheet->getStyle("L$row")->applyFromArray($highlightStyle); // Unit Cost
                $sheet->getStyle("M$row")->applyFromArray($highlightStyle); // Amount
                $sheet->getStyle("N$row:O$row")->applyFromArray($highlightStyle);
                

                $row++;
            }
        }
    
        // Apply borders to the entire table
        $sheet->getStyle("A8:Q$row")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Apply bold and centered text to the headers
        $sheet->getStyle("A8:Q8")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THICK]]
        ]);

        // Format currency columns
        $sheet->getStyle("L10:L$row")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("M10:M$row")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("O10:O$row")->getNumberFormat()->setFormatCode('#,##0.00');

        // Ensure balance columns are bold
        $sheet->getStyle("N10:N$row")->getFont()->setBold(true);
        $sheet->getStyle("O10:O$row")->getFont()->setBold(true);

        // Auto-size columns
        $sheet->getColumnDimension('A')->setWidth(12);
        foreach (range('B', 'Q') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        
    
        // NO OUTPUT BEFORE HEADER
        ob_clean();
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="subsidiary_ledger_' . ($item_code ?: 'all') . '.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }


    public function generate_excel_acquisition($item_code = null) {
        // Get inputs
        $item_code = $this->input->get('item_code');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $school_name = $this->input->get('schl_name');
        $school_address = $this->input->get('schl_address');
        
        // Fetch data
        $offices = $this->PPE_model->get_all_books();
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // **Set Title and Address (Left-Aligned)**
        $sheet->setCellValue('A1', $school_name);
        $sheet->setCellValue('A2', $school_address);
        $sheet->setCellValue('A4', 'ACQUSITION, DONATION, ISSUANCE AND DISPOSAL REPORT  ')->getStyle('A4')->getFont()->setBold(true)->getColor()->setRGB('000000');
    
        // **Date Range**
        $date_range_text = "From " . (!empty($start_date) ? date('F j, Y', strtotime($start_date)) : 'N/A') .
                   " to " . (!empty($end_date) ? date('F j, Y', strtotime($end_date)) : 'N/A');
    
        $sheet->setCellValue('A5', $date_range_text);
    
        // **Display Item Name (if filtered)**
        if ($item_code) {
            $sheet->setCellValue('A6', 'Item: ' + $item_code);
        }
    
        // **Table Headers**
        $sheet->mergeCells('N8:O8');
        $sheet->setCellValue('N8', 'Balance');
        $sheet->getStyle('N8')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('N8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Define headers (Row 9)
        $headers = [
            'Title', 'Author'
        ];

            
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '8', $header);
            $col++;
        }
    
        // **Style Headers**
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        $borderStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        $sheet->setCellValue('N9', 'Qty.');
        $sheet->setCellValue('O9', 'Amt.');
        $sheet->getStyle('A8:Q8')->applyFromArray($headerStyle);

        // Apply styling to headers
        $sheet->getStyle('N9:O9')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
            
        // **Group Data by Item Code**
        $grouped_data = [];

        $row = $startingRow;
        foreach ($offices as $t) {
            $grouped_data[$t->item_code][] = $t;
        }
    
        // Populate Data for Each Item Code**
        $row = 10;
        foreach ($grouped_data as $item_code => $transactions) {
            
    
            // INIT BALANCE
            $balances = [];
    
            foreach ($transactions as $t) {
                $unitCost = ($t->quantity > 0) ? number_format($t->total / $t->quantity, 2) : 0;
    
                if (!isset($balances[$t->item_code][$t->batch_number])) {
                    $balances[$t->item_code][$t->batch_number] = ['quantity' => 0, 'total' => 0, 'unit_cost' => 0];
                }
    
                // PREV BALANCE DISPLAY FIRST
                $previousBalance = $balances[$t->item_code][$t->batch_number]['quantity'];
    
                // TRANSACTION PROCESS
                if ($t->type == 'Purchase' || $t->type == 'Donation') {
                    $balances[$t->item_code][$t->batch_number]['quantity'] += $t->quantity;
                    $balances[$t->item_code][$t->batch_number]['total'] += $t->total;
                } elseif ($t->type == 'Issuance') {
                    $balances[$t->item_code][$t->batch_number]['quantity'] -= $t->quantity;
                    $balances[$t->item_code][$t->batch_number]['total'] -= $t->total;
                }
    
                // UPDATE UNIT COST
                $balances[$t->item_code][$t->batch_number]['unit_cost'] = 
                    ($balances[$t->item_code][$t->batch_number]['quantity'] > 0) 
                    ? $balances[$t->item_code][$t->batch_number]['total'] / $balances[$t->item_code][$t->batch_number]['quantity'] 
                    : 0;
    
                // CALCULATE BALANCE AFTER ISSUANCES
                $currentBalance = $balances[$t->item_code][$t->batch_number]['quantity'];
                $unitCost = ($t->quantity > 0) ? round($t->total / $t->quantity, 2) : 0;

    
                // CHECK STOCK IF IN STOCK OR OUT OF STOCK
                $status = ($currentBalance <= 0) ? 'Out of Stock' : 'In Stock';
                //CHECK TYPE FOR IPURCHASED DATE DISPLAY
                $enterDate = ($t->type == "Issuance" || $t->type == "Disposal") ? '' : $t->purchased_date;  
    
                $sheet->setCellValue('A' . $row, $enterDate);
                $sheet->setCellValue('B' . $row, $t->entered_date);
                $sheet->setCellValue('C' . $row, $t->type);
                $sheet->setCellValue('D' . $row, $t->item_code);
                $sheet->setCellValue('E' . $row, $t->discription);
                $sheet->setCellValue('F' . $row, $t->brand);
                $sheet->setCellValue('G' . $row, $t->supplier);
                $sheet->setCellValue('H' . $row, $t->requesting_office);
                $sheet->setCellValue('I' . $row, $t->location);
                $sheet->setCellValue('J' . $row, $t->unit);
                $sheet->setCellValue('K' . $row, $t->quantity);
                $sheet->setCellValue('L' . $row, number_format($unitCost, 2));
                $sheet->setCellValue('M' . $row, number_format($t->total, 2));
                $sheet->setCellValue('N' . $row, $currentBalance);
                $sheet->setCellValue('O' . $row, number_format($balances[$t->item_code][$t->batch_number]['total'], 2));
                

                $sheet->getStyle("N$row:O$row")->getFont()->setBold(true);

                
                $sheet->getStyle("B9:Q9")->applyFromArray([
                    'borders' => ['ALL' => ['borderStyle' => Border::BORDER_THICK]]
                ]);

                $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('M' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('O' . $row)->getNumberFormat()->setFormatCode('#,##0.00');


                // Define style for highlighting specific columns
                $highlightStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => '000000']], // White text
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFF00'], // Blue background
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ];

                $sheet->getStyle("J$row")->applyFromArray($highlightStyle); // Unit
                $sheet->getStyle("K$row")->applyFromArray($highlightStyle); // Quantity
                $sheet->getStyle("L$row")->applyFromArray($highlightStyle); // Unit Cost
                $sheet->getStyle("M$row")->applyFromArray($highlightStyle); // Amount
                $sheet->getStyle("N$row:O$row")->applyFromArray($highlightStyle);
                

                $row++;
            }
        }
    
        // Apply borders to the entire table
        $sheet->getStyle("A8:O$row")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Apply bold and centered text to the headers
        $sheet->getStyle("A8:O8")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THICK]]
        ]);

        // Format currency columns
        $sheet->getStyle("L10:L$row")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("M10:M$row")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("O10:O$row")->getNumberFormat()->setFormatCode('#,##0.00');

        // Ensure balance columns are bold
        $sheet->getStyle("N10:N$row")->getFont()->setBold(true);
        $sheet->getStyle("O10:O$row")->getFont()->setBold(true);

        // Auto-size columns
        $sheet->getColumnDimension('A')->setWidth(12);
        foreach (range('B', 'O') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        
    
        // NO OUTPUT BEFORE HEADER
        ob_clean();
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="subsidiary_ledger_' . ($item_code ?: 'all') . '.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    
}
?>
