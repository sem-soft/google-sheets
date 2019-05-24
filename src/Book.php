<?php
/**
 * Файл класса Book.php
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace Sem\GoogleSheets;

/**
 * Реализует логику работы с книгой Google
 * @package Sem\GoogleSheets
 */
class Book
{
    /**
     * @var string уникальный идентификатор книги
     */
    protected $id;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->setId($id);
    }

    /**
     * Устанавливает уникальный идентификатор книги
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает уникальный идентификатор книги
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}