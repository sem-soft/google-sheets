<?php
/**
 * Файл класса Sheet.php
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace Sem\GoogleSheets;

/**
 * Реализует логику работы с конкретны листом Google-таблици
 * @package Sem\GoogleSheets
 */
class Sheet
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * Устанавливает наименование листа
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Возвращает наименование листа
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает полный адрес диапазона внутри листа книги.
     * Например, если `$this->name = 'Some sheet name'`, а `$range = 'C11'`,
     * то результатом метода будет строка `'Some sheet name!C11'`
     *
     * @param string $range диапазон или конкретная ячейка. Например, `A2:I100` или `С11`
     * @return string полный адрес диапазона с учетом листа книги
     */
    public function getFullRangeAddress(string $range): string
    {
        return "{$this->name}!{$range}";
    }
}