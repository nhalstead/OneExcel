<?php
namespace Imtigger\OneExcel\Reader;

use Imtigger\OneExcel\Format;
use Imtigger\OneExcel\OneExcelReaderInterface;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Reader\AbstractReader;

class SpoutReader extends OneExcelReader implements OneExcelReaderInterface
{
    public static $input_format_supported = [Format::XLSX, Format::CSV, Format::ODS];
    /** @var AbstractReader $reader */
    private $reader;
    private $sheet;

    public function load($filename, $input_format = Format::AUTO, $options = [])
    {
        $this->checkFormatSupported($input_format);
        $this->input_format = $input_format;

        $this->reader = ReaderFactory::create($input_format);
        $this->reader->open($filename);
        $this->reader->setShouldFormatDates(true);

        foreach ($this->reader->getSheetIterator() as $sheetIndex => $sheet) {
            $this->sheet = $sheet;
            break;
        }
    }

    public function row()
    {
        foreach ($this->sheet->getRowIterator() as $row) {
            yield $row;
        }
    }

    public function close()
    {
        $this->reader->close();
    }
}