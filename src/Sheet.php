<?php
/**
 * Файл класса Sheet.php
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace Sem\GoogleSheets;

/**
 * Содержит логику для работы с данными листов Google-таблицы
 * @package Sem\GoogleSheets
 */
class Sheet
{

    /**
     * @var string уникальынй идентификатор таблицы, в которую производится запись
     */
    protected $tableId;

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
     * @param string $tableId
     */
    public function __construct(\Google_Client $client, string $tableId)
    {
        $this->tableId = $tableId;
        $this->serviceSheets = new \Google_Service_Sheets($client);
        $this->serviceSheetsValueRange = new \Google_Service_Sheets_ValueRange();
    }

    /**
     * Производит вставку строки значений на лист таблицы
     * @param string $fromCell адрес ячейки, начиная с которой произойдет вставка.
     * В адресе может участвовать название листа. Например,
     * ```php
     * $this->gSheet->insertRow('Some sheet name!C11', [
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
            $this->tableId,
            $fromCell,
            $this->getValuedRange($data),
            [
                'valueInputOption' => 'USER_ENTERED',
            ]
        );
    }

    public function clearRange()
    {

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