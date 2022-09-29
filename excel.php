<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelIO
{
    private IReader $reader;
    private Spreadsheet $spreadsheet;

    private array $excelBooks;
    private array $dbData;

    public function __construct(string $filename)
    {
        $this->reader = IOFactory::createReader('Xlsx');
        $this->spreadsheet = $this->reader->load($filename);
        $this->importBooks();
    }

    public function importBooks(): void
    {
        $this->reader->setReadDataOnly(true);

        $data = $this->spreadsheet->getActiveSheet()->toArray();
        foreach ($data as $bookTitle) {
            $this->excelBooks[] = $bookTitle[0];
        }
    }

    public function getBooksFromDB(): void
    {
        $this->db = new PDO('mysql:host=localhost;dbname=muztv', 'root', 'root');
        $getBooks = $this->db->query('SELECT books.title, authors.name FROM books JOIN authors_books ON books.id = authors_books.book_id JOIN authors ON authors.id = authors_books.author_id');

        while ($data = $getBooks->fetch(PDO::FETCH_ASSOC)) {
            if(in_array($data['title'], $this->excelBooks)){
                $this->dbData[] = $data;
            }
        }
    }


    private function formatData(array $array): array
    {
        $newArr = [];
        foreach ($array as $subArr) {
            $newArr[$subArr['title']][] = $subArr['name'];
        }
        return $newArr;
    }

    public function export(): void
    {
        $spreadsheet = new Spreadsheet();
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $header = ['Book', 'Author'];
        $sheet->fromArray([$header]);

        $exportArray = $this->formatData($this->dbData);

        $rowCount = 2;
        foreach ($exportArray as $book => $authors) {
            $sheet->setCellValue('A' . $rowCount, $book);
            $sheet->setCellValue('B' . $rowCount, implode(', ', $authors));
            $rowCount++;
        }

        $writer->save('report.xlsx');
    }
}


$r = new ExcelIO('test.xlsx');
$r->getBooksFromDB();
$r->export();