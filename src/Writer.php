<?php
/**
 * Файл класса Writer.php
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace Sem\GoogleSheets;

/**
 * Содержит логику для работы с данными листов Google-таблицы
 * @package Sem\GoogleSheets
 */
class Writer
{

    /**
     * @var Book таблица, с листом которой мы работаем
     */
    protected $book;

    /**
     * @var Sheet лист таблицы @see $table, в который пишим данные
     */
    protected $sheet;

    /**
     * @var \Google_Service_Sheets сервис для работы с листами таблицы
     */
    protected $serviceSheets;

    /**
     * @var \Google_Service_Sheets_ValueRange сервис для работы с диапазонами значений
     */
    protected $serviceSheetsValueRange;

    /**
     * Инициализируем необходимые сервисы
     * @param \Google_Client $client
     * @param Book $book
     * @param Sheet $sheet
     */
    public function __construct(\Google_Client $client, Book $book, Sheet $sheet)
    {
        $this->book = $book;
        $this->sheet = $sheet;
        $this->serviceSheets = new \Google_Service_Sheets($client);
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);
        $this->serviceSheetsValueRange = new \Google_Service_Sheets_ValueRange();
    }

    /**
     * Производит вставку строки значений на лист таблицы
     * @param string $fromCell адрес ячейки, начиная с которой произойдет вставка.
     * В адресе может участвовать название листа. Например,
     * ```php
     * $this->gSheet->insertRow('C11', [
     *     'key1' => 'Some value',
     *     'key2' => 'Some value 1',
     *     'key3' => 'Some value 2',
     * ]);
     * ```
     * @param array $data
     */
    public function insertRow(string $fromCell, array $data): void
    {
        $this->serviceSheets->spreadsheets_values->update(
            $this->book->getId(),
            $this->sheet->getFullRangeAddress($fromCell),
            $this->getValuedRange($data),
            [
                'valueInputOption' => 'USER_ENTERED',
            ]
        );
    }

    /**
     *
     * @param string $range диапазон для отчистки
     */
    public function clearRange(string $range): void
    {
        $this->serviceSheets->spreadsheets_values->clear(
            $this->book->getId(),
            $this->sheet->getFullRangeAddress($range),
            new \Google_Service_Sheets_ClearValuesRequest()
        );
    }

    /**
     * Подготавливает диапазон значений для вставки
     * @param array $data
     * @return \Google_Service_Sheets_ValueRange
     */
    protected function getValuedRange(array $data): \Google_Service_Sheets_ValueRange
    {
        $this->serviceSheetsValueRange->setValues([
            'values' => array_values($data)
        ]);

        return $this->serviceSheetsValueRange;
    }

}