<?php
// Load PhpSpreadsheet Library
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreasheet\Worksheet\Bold;

class Export_Library extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // session
        $this->load->library('session');
        $this->load->database(); // Add this line to load the database
        
    }

    public function generateReport() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()
              ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
              ->setPaperSize(PageSetup::PAPERSIZE_A4)
              ->setFitToHeight(0);
    
        // Get settings
        $settings = $this->db->get('settings')->row();
        $school_name = $settings->school_name;
        $school_address = $settings->address;
        $checked_by = $settings->checked_by;
        $approved_by = $settings->approved_by;
        $logoFile = trim($settings->school_logo);
        $logo_path = FCPATH . $logoFile;
    
        // Filters
        $prepared_by = $this->session->userdata('full_name');
        $transactionType = $this->input->get('transaction_type');
        $classification = $this->input->get('classification');
        $period = $this->input->get('period');
        $dateFrom = $this->input->get('date_from');
        $dateTo = $this->input->get('date_to');
    
        // Insert logo
        if (file_exists($logo_path)) {
            $drawing = new Drawing();
            $drawing->setName('School Logo');
            $drawing->setDescription('School Logo');
            $drawing->setPath($logo_path);
            $drawing->setHeight(100);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
        }
    
        // Query building
        $this->db->select('*')->from('lib');
        if (!empty($transactionType)) $this->db->where('transaction_type', $transactionType);
        if (!empty($classification)) $this->db->where('classification', $classification);
    
        if ($period == 'Weekly') {
            $this->db->where('date_entered >=', date('Y-m-d', strtotime('-1 week')));
        } elseif ($period == 'Monthly') {
            $this->db->where('date_entered >=', date('Y-m-d', strtotime('-1 month')));
        } elseif ($period == 'Yearly') {
            $this->db->where('date_entered >=', date('Y-m-d', strtotime('-1 year')));
        } elseif ($period == 'Custom' && !empty($dateFrom) && !empty($dateTo)) {
            $this->db->where('date_entered >=', $dateFrom);
            $this->db->where('date_entered <=', $dateTo);
        }
    
        $transactions = $this->db->get()->result_array();
    
        // Header Info
        $sheet->setCellValue('B1', $school_name);
        $sheet->mergeCells('B1:D1');
        $sheet->setCellValue('B2', $school_address);
        $sheet->mergeCells('B2:D2');
        $sheet->mergeCells('B3:D3');
        $sheet->setCellValue('B4', 'ACQUISITION, DONATION AND DISPOSAL REPORT');
        $sheet->mergeCells('B4:D4');
        $sheet->setCellValue('B5', 'From ' . (!empty($dateFrom) ? $dateFrom : 'Start') . ' to ' . (!empty($dateTo) ? $dateTo : 'Present'));
        $sheet->mergeCells('B5:D5');
        $sheet->setCellValue('B6', 'LIBRARY RESOURCES');
        $sheet->mergeCells('B6:D6');
    
        // Column headers
        $headers = [
            'Date Purchased', 'Date Entered', 'TRANSACTION', 'TITLE', 'CLASSIFICATION',
            'CLASSIFICATION NUMBER', 'AUTHOR', 'SUBJECT', 'CATEGORY SUBJECT', 'ISBN', 'YEAR PUBLISHED'
        ];
    
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '8', $header);
            $sheet->getStyle($col . '8')->getFont()->setBold(true);
            $sheet->getStyle($col . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }
    
        // Data
        $row = 9;
        foreach ($transactions as $tx) {
            $sheet->setCellValue('A' . $row, $tx['date_purchased']);
            $sheet->setCellValue('B' . $row, $tx['date_entered']);
            $sheet->setCellValue('C' . $row, $tx['transaction_type']);
            $sheet->setCellValue('D' . $row, $tx['book_title']);
            $sheet->setCellValue('E' . $row, $tx['classification']);
            $sheet->setCellValue('F' . $row, $tx['classification_number']);
            $sheet->setCellValue('G' . $row, $tx['author']);
            $sheet->setCellValue('H' . $row, $tx['subject']);
            $sheet->setCellValue('I' . $row, $tx['category_subject']);
            $sheet->setCellValue('J' . $row, $tx['isbn']);
            $sheet->setCellValue('K' . $row, $tx['year_published']);
            $row++;
        }
    
        // Auto-size columns
        foreach (range('A', 'K') as $colID) {
            $sheet->getColumnDimension($colID)->setAutoSize(true);
        }
    
        // Border formatting
        $styleArray = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A8:K' . ($row - 1))->applyFromArray($styleArray);
    
        // Output
        $filename = 'Library_Report_' . str_replace(' ', '_', $school_name) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    


    // by books

    public function generateReportByBook() {

        $school = $this->session->userdata('school');
        $address = $this->session->userdata('school_address');
        // Load database
        $this->load->database();

        // Get filter parameters from the request
        $book = $this->input->get('book');
        $transactionType = $this->input->get('transaction_type'); // Acquisition, Donation, Disposal
        $classification = $this->input->get('classification'); // e.g., Fiction, Non-Fiction
        $period = $this->input->get('period'); // Weekly, Monthly, Yearly, Custom
        $dateFrom = $this->input->get('date_from'); // Custom Start Date
        $dateTo = $this->input->get('date_to'); // Custom End Date

        // Build query with filters
        $this->db->select('*');
        $this->db->from('lib');
        $this->db->where('id', $book);
        
        if (!empty($transactionType)) {
            $this->db->where('transaction_type', $transactionType);
        }
        if (!empty($classification)) {
            $this->db->where('classification', $classification);
        }

        if(!empty($book)) {
            $this->db->where('id', $book);
        }
        if ($period == 'Weekly') {
            $this->db->where('date_entered >=', date('Y-m-d', strtotime('-1 week')));
        } elseif ($period == 'Monthly') {
            $this->db->where('date_entered >=', date('Y-m-d', strtotime('-1 month')));
        } elseif ($period == 'Yearly') {
            $this->db->where('date_entered >=', date('Y-m-d', strtotime('-1 year')));
        } elseif ($period == 'Custom' && !empty($dateFrom) && !empty($dateTo)) {
            $this->db->where('date_entered >=', $dateFrom);
            $this->db->where('date_entered <=', $dateTo);
        }

        // Fetch filtered data
        $query = $this->db->get();
        $transactions = $query->result_array();

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Report Header
        $sheet->setCellValue('A1', $school);
        $sheet->setCellValue('A2', $address);
        $sheet->setCellValue('A4', 'ACQUISITION, DONATION AND DISPOSAL REPORT');
        $sheet->setCellValue('A5', 'From ' . ($dateFrom ?? 'Start') . ' to ' . ($dateTo ?? 'Present'));
        $sheet->setCellValue('A6', 'LIBRARY RESOURCES');

        // Column Headers
        $headers = ['Date Purchased', 'Date Entered', 'TRANSACTION', 'TITLE', 'CLASSIFICATION', 'CLASSIFICATION NUMBER', 'AUTHOR', 'SUBJECT', 'CATEGORY SUBJECT', 'ISBN', 'YEAR Published'];
        $column = 'A';

        foreach ($headers as $header) {
            $sheet->setCellValue($column . '8', $header);
            $sheet->getStyle($column . '8')->getFont()->setBold(true);
            $sheet->getStyle($column . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $column++;
        }

        // Populate Data
        $row = 9;
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, $transaction['date_purchased']);
            $sheet->setCellValue('B' . $row, $transaction['date_entered']);
            $sheet->setCellValue('C' . $row, $transaction['transaction_type']);
            $sheet->setCellValue('D' . $row, $transaction['book_title']);
            $sheet->setCellValue('E' . $row, $transaction['classification']);
            $sheet->setCellValue('F' . $row, $transaction['classification_number']);
            $sheet->setCellValue('G' . $row, $transaction['author']);
            $sheet->setCellValue('H' . $row, $transaction['subject']);
            $sheet->setCellValue('I' . $row, $transaction['category_subject']);
            $sheet->setCellValue('J' . $row, $transaction['isbn']);
            $sheet->setCellValue('K' . $row, $transaction['year_published']);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Apply borders to all filled cells
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A8:K' . ($row - 1))->applyFromArray($styleArray);

        // Save as Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Library_Report.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
    }




    public function previewReportBorrowed()
{
    $school = $this->session->userdata('school');
    $address = $this->session->userdata('school_address');
    // Load database
    $this->load->database();
    
    // Get filter parameter
    $studentId = $this->input->get('student_name');

    // Fetch Student Details
    $studentDetails = null;
    if (!empty($studentId)) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('fullname', $studentId);
        $studentQuery = $this->db->get();
        $studentDetails = $studentQuery->row_array();
    }

    // Fetch Borrowed Books
    $this->db->select('*');
    $this->db->from('borrowed_books');

    if (!empty($studentId)) {
        $this->db->where('student_name', $studentId);
    }

    $query = $this->db->get();
    $transactions = $query->result_array();

    // Format transactions
    foreach ($transactions as &$transaction) {
        $transaction['status'] = empty($transaction['actual_returned_date']) ? 'Not Yet Returned' : 'Returned';
    }

    // Return JSON response
    echo json_encode([
        'student' => $studentDetails ?? [],
        'transactions' => $transactions,
    ]);
}



public function generateReportBorrowed() {

    // Load database
    $this->load->database();

    // Get settings from database
    $settings = $this->db->get('settings')->row();
    $school_name = $settings->school_name;
    $school_address = $settings->address;
    $checked_by = $settings->checked_by;
    $approved_by = $settings->approved_by;
    $logoFile = trim($settings->school_logo);
    $logo_path = FCPATH . '/' . $logoFile;

    // Get current user's name for "Prepared by"
    $prepared_by = $this->session->userdata('full_name');

    // Get filter parameters from the request
    $studentId = $this->input->get('student_name'); // Actually the student full name

    // Fetch Student Details
    $studentDetails = null;
    if (!empty($studentId)) {
        $this->db->select('*');
        $this->db->from('users'); // Assuming 'users' is the table where student data is stored
        $this->db->where('fullname', $studentId);
        $studentQuery = $this->db->get();
        $studentDetails = $studentQuery->row_array(); // Fetch single row
    }

    // Build query with filters for borrowed books
    $this->db->select('*');
    $this->db->from('borrowed_books');
    if (!empty($studentId)) {
        $this->db->where('student_name', $studentId);
    }

    // Fetch filtered data
    $query = $this->db->get();
    $transactions = $query->result_array();

    // Create Spreadsheet and sheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set Page Setup
    $sheet->getPageSetup()
        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
        ->setFitToHeight(0); // Unlimited height, fit width to one page

    // Add logo if exists
    if (file_exists($logo_path)) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('School Logo');
        $drawing->setDescription('School Logo');
        $drawing->setPath($logo_path);
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
    }

    // Set School Header
    $sheet->setCellValue('B1', $school_name);
    $sheet->setCellValue('B2', $school_address);
    $sheet->mergeCells('B1:D1');
    $sheet->mergeCells('B2:D2');

    $sheet->getStyle('B1:B2')->getFont()->setBold(true);

    // Student Details Section
    $sheet->setCellValue('A4', 'Name:');
    $sheet->setCellValue('B4', !empty($studentDetails['fullname']) ? $studentDetails['fullname'] : 'N/A');

    $sheet->setCellValue('A5', 'ID Number:');
    $sheet->setCellValue('B5', !empty($studentDetails['student_id']) ? $studentDetails['student_id'] : 'N/A');
    $sheet->getStyle('B5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

    $sheet->setCellValue('A6', 'Course:');
    $sheet->setCellValue('B6', !empty($studentDetails['course']) ? $studentDetails['course'] : 'N/A');

    $sheet->setCellValue('A7', 'Year:');
    $sheet->setCellValue('B7', !empty($studentDetails['year']) ? $studentDetails['year'] : 'N/A');
    $sheet->getStyle('B7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


    // bold
    $sheet->getStyle('A4:A7')->getFont()->setBold(true);
    // Set Report Title
    $sheet->setCellValue('A9', 'STUDENT LEDGER');
    $sheet->getStyle('A9')->getFont()->setBold(true);
    $sheet->getStyle('A9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('A9:H9');

    // Column Headers
    $headers = ['Date Borrowed', 'Title of the Book', 'Author', 'Author', 'BAR CODE', 'Date Due', 'Date Returned', 'Status'];
    $column = 'A';

    foreach ($headers as $header) {
        $sheet->setCellValue($column . '11', $header);
        $sheet->getStyle($column . '11')->getFont()->setBold(true);
        $sheet->getStyle($column . '11')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $column++;
    }

    // Populate Data
    $row = 12;
    foreach ($transactions as $transaction) {
        $sheet->setCellValue('A' . $row, $transaction['borrow_date']);
        $sheet->setCellValue('B' . $row, $transaction['book_title']);
        $sheet->setCellValue('C' . $row, $transaction['book_author']);
        $sheet->setCellValue('D' . $row, $transaction['book_author']); // Duplicate Author column
        $sheet->setCellValue('E' . $row, $transaction['barcode']);
        $sheet->setCellValue('F' . $row, $transaction['return_date']);
        $sheet->setCellValue('G' . $row, $transaction['actual_returned_date']);

        // Determine Status
        if (!empty($transaction['actual_returned_date'])) {
            $status = 'Returned';
        } else {
            $status = (strtotime($transaction['return_date']) < time()) ? 'Overdue' : 'Borrowed';
        }
        $sheet->setCellValue('H' . $row, $status);

        $row++;
    }

    // Auto-size columns
    foreach (range('A', 'H') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Apply borders
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ];
    $sheet->getStyle('A11:H' . ($row - 1))->applyFromArray($styleArray);

    // Output the file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'Student_Ledger_Report.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
}


    public function generateReportMostBorrowed() {
       
        // Load database
        $this->load->database();


        // Get settings from database
    $settings = $this->db->get('settings')->row();
    $school_name = $settings->school_name;
    $school_address = $settings->address;
    $checked_by = $settings->checked_by;
    $approved_by = $settings->approved_by;
    $logoFile = trim($settings->school_logo);
    $logo_path = FCPATH . '/' . $logoFile;

    // Get current user's name for "Prepared by"
    $prepared_by = $this->session->userdata('full_name');

    
        // Get filter parameter for top borrowed books
        $top = $this->input->get('top') ?: 100; // Default to Top 100 if not specified
    
        $this->db->select('b.book_title, b.book_author, b.isbn, l.classification, COUNT(*) as borrow_count');
        $this->db->from('borrowed_books b');
        $this->db->join('lib l', 'b.book_id = l.id', 'left'); // Join lib table to get classification
        $this->db->group_by('l.classification'); // Group by ISBN to count borrows
        $this->db->order_by('borrow_count', 'DESC');
        $this->db->limit($top); // Limit the number of results

        $query = $this->db->get();
        $books = $query->result_array(); // Fetch results
    
        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
       // Create Spreadsheet and sheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set Page Setup
    $sheet->getPageSetup()
        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)
        ->setFitToHeight(0); // Unlimited height, fit width to one page

    // Add logo if exists
    if (file_exists($logo_path)) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('School Logo');
        $drawing->setDescription('School Logo');
        $drawing->setPath($logo_path);
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');
        // center the logo

        $drawing->setWorksheet($sheet);
    }

    // Set School Header
    $sheet->setCellValue('B1', $school_name);
    $sheet->setCellValue('B2', $school_address);

    $sheet->setCellValue('B6', "MOST BORROWED BOOKS TOP $top");
    $sheet->mergeCells('B1:D1');
    $sheet->mergeCells('B2:D2');
    $sheet->mergeCells('B6:D6');
    $sheet->getStyle('B2')->getFont()->setBold(true);
    $sheet->getStyle('B6')->getFont()->setBold(true);
    
        // Column Headers
        $headers = ['TITLE', 'AUTHOR', 'ISBN NUMBER', 'CLASSIFICATION'];
        $column = 'A';
    
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '7', $header);
            $sheet->getStyle($column . '7')->getFont()->setBold(true);
            $sheet->getStyle($column . '7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $column++;
        }
    
        // Populate Data
        $row = 8;
        foreach ($books as $book) {
            $sheet->setCellValue('A' . $row, $book['book_title']);
            $sheet->setCellValue('B' . $row, $book['book_author']);
            $sheet->setCellValue('C' . $row, $book['isbn']);
            $sheet->setCellValue('D'. $row, $book['classification'] );
            $row++;
        }
    
        // Auto-size columns
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        // Apply borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A7:D' . ($row - 1))->applyFromArray($styleArray);
    
        // Save as Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'OPAC_Most_Borrowed_Books_Top_' . $top . '.xlsx';
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
    }
    


    // opac

    public function generate_excel()
    {
        $query = $this->input->get('query');
        $school_name = $this->session->userdata('school');
        $school_address = $this->session->userdata('school_address');

        if (!$school_name || !$school_address) {
            echo "Missing school name or address!";
            exit;
        }

        // Load model
        $this->load->model('Library_model');
        $books = $this->Library_model->search_books($query);

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set school name and address
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', $school_name);
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', $school_address);
        $sheet->mergeCells('A4:H4');
        $sheet->setCellValue('A4', 'ONLINE PUBLIC ACCESS CATALOG');

        // Apply styling to headers
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        $sheet->getStyle('A1:A4')->applyFromArray($headerStyle);

        // Set column headers
        $headers = ['TITLE', 'AUTHOR', 'CLASSIFICATION', 'SUBJECT', 'CATEGORY SUBJECT', 'ISBN NUMBER', 'YEAR PUBLISHED', 'LANGUAGE'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '7', $header);
            $col++;
        }

        // Apply table styling
        $tableStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];
        $sheet->getStyle('A7:H7')->applyFromArray($tableStyle);

        // Insert data
        $row = 8;
        foreach ($books as $book) {
            $sheet->setCellValue('A' . $row, $book->book_title);
            $sheet->setCellValue('B' . $row, $book->author);
            $sheet->setCellValue('C' . $row, $book->classification);
            $sheet->setCellValue('D' . $row, $book->subject);
            $sheet->setCellValue('E' . $row, $book->category_subject);
            $sheet->setCellValue('F' . $row, $book->isbn);
            $sheet->setCellValue('G' . $row, $book->year_published);
            $sheet->setCellValue('H' . $row, $book->language);
            $row++;
        }

        // Apply borders to table
        $sheet->getStyle('A7:H' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output as downloadable Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Book_Report.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
}   