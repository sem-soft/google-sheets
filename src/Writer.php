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
     * @param array $row
     */
    public function insertRow(string $fromCell, array $row): void
    {
        $this->serviceSheets->spreadsheets_values->update(
            $this->book->getId(),
            $this->sheet->getFullRangeAddress($fromCell),
            $this->getValuedRange($row),
            [
                'valueInputOption' => 'USER_ENTERED',
            ]
        );
    }

    /**
     * Производит вставку множества строк начиная с опредленного столбца и строки
     *
     * @param string $fromColumn столбец, с которого начинать вставку, например `'F'`
     * @param int $fromRow номер строки, с которой начинат вставку, например  `5`
     * @param array $rows
     */
    public function insertRows(string $fromColumn, int $fromRow,  array $rows): void
    {
        if (!empty($rows)) {
            $data = [];

            foreach ($rows as $row) {
                $valuedRange = $this->getValuedRange($row);
                $valuedRange->setRange($this->sheet->getFullRangeAddress($fromColumn . ($fromRow++)));
                $data[] = $valuedRange;
            }

            $batch = new \Google_Service_Sheets_BatchUpdateValuesRequest();
            $batch->setData($data);
            $batch->setValueInputOption('USER_ENTERED');

            $this->serviceSheets->spreadsheets_values->batchUpdate(
                $this->book->getId(),
                $batch
            );
        }
    }

    /**
     * Производит очистку указанного диапазона ячеек
     * @param string $range диапазон для отчистки, например `'A2:I26'`
     */
    public function clear(string $range): void
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