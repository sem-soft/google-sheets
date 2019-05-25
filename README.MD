# GoogleSheets API Base Implementation
This library implements basic functionality for writing data to Google Sheets document through Service Google Account.  
## Installation via composer.json
```
"sem-soft/google-sheets": "~1.0.0"
```
## Usage
This library use the official [Google Client package](https://github.com/googleapis/google-api-php-client).
### Auth by service aacount
Before use this library we must to configure basic Google Auth Client object for using service account credential file.
```php
$auth = new \Sem\GoogleSheets\Auth\ServiceAccountAuthenticator();
$client = $auth->setAuth(new \Google_Client(), '/path/to/Credentials-5c2688ed460a.json');
```
### Middleware objects
For writing process we must instantiate some basic objects.
Book Object — entity of Google Spreadsheet.
Sheet Object — entity of Google Spreadsheet sheet layer.
This objects implemets current spreadsheet for writing process.
```php
$book = new \Sem\GoogleSheets\Book('193J0l6pRREaQa5632PdD2sCioAJw5AxTV0TWnK0SNH7');
$sheet = new \Sem\GoogleSheets\Sheet('Лист 1');
```
### Writer object
The writer object implements functionality for writing your data to cells, rows and clear ranges in sheets.
This object is required book and sheet objects.
```php
$writer = new \Sem\GoogleSheets\Writer(
    $client,
    $book,
    $sheet
);
```
### Example for writing data to sheet of the book
```php
$auth = new ServiceAccountAuthenticator();
$client = $auth->setAuth(new \Google_Client(), '/opt/Example-5c1111ed600a.json'));

$writer = new Writer(
    $client,
    new Book('193J0l6pRREaQa5632PdD2sCioAJw5AxTV0TWnK0SNH7'),
    new Sheet('Лист 1')
);
// Clear range
$writer->clearRange('A1:E100');
// Insert row of cells from A2
$writer->insertRow('A2', [
    'Раз',
    'Два',
    'Три'
]);
// Insert row of cells from A3
$writer->insertRow('A3', [
    'Четыри',
    'Пять',
    'Шесть'
]);
// Insert rows of cells from D column and 4 row
$writer->insertRows('D', 4, [
    [
        'Раз',
        'Два',
        'Три'
    ],
    [
        'Четыри',
        'Пять',
        'Шесть'
    ]
]);
// Set cell value
$writer->cell('D12', 'Привет!');
```