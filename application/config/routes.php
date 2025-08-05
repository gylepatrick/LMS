<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['settings'] = 'settings/index';

$route['export/generate_excel_acquisition_all'] = 'export/generate_excel_acquisition_all';

$route['library/login'] = 'library/login';
$route['library/get_book_by_barcode'] = 'library/get_book_by_barcode';
$route['library'] = 'library/index';
$route['library/opac'] = 'library/opac';    
$route['library'] = 'library/store';
$route['library/update'] = 'library/update';
$route['library/borrow_book'] = 'library/borrow_book';
$route['opac'] = 'library/opac_results';

$route['library/search_books'] = 'library/search_books';
$route['export_library/generate_excel'] = 'export_library/generate_excel';
//student
$route['student_dashboard'] = 'library/student_dashboard';

// borrowed books
$routes['borrowed_books'] = 'library/borrowed_books';


// return
$route['library/return_book'] = 'library/return_book';
$route['library/get_borrowed_book_by_barcode'] = 'library/get_borrowed_book_by_barcode';
$route['library/return_book'] = 'library/return_book';
$route['library/library_dashboard'] = 'library/library_dashboard';


// export
$route['export_library/generateReport'] = 'export_library/generateReport';
$route['export_library/generateReportBorrowed'] = 'export_library/generateReportBorrowed';
$route['export_library/previewReportBorrowed'] = 'export_library/previewReportBorrowed';
$route['export_library/generateReportMostBorrowed'] = 'export_library/generateReportMostBorrowed';

// opac
$route['opac'] = 'library/opac_results';
$route['opac/search'] = 'opac/search';
$route['opac/liveSearch'] = 'opac/liveSearch';


$route['export/generate_excel_acquisition_all'] = 'export/generate_excel_acquisition_all';




$route['offices/issue'] = 'offices/issue';
$route['offices/process_issue'] = 'offices/process_issue';



// login
$route['login'] = 'login/index';
$route['login/submit'] = 'login/submit';
$route['library/submit'] = 'library/submit';
$route['logout'] = 'login/logout';
$route['library/logout'] = 'library/logout';

// dashboard
$route['dashboard'] = "dashboard/index";

$route['default_controller'] = 'library/login';
$route['main'] = 'home/index';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
