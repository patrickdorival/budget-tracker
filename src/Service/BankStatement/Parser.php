<?php


namespace App\Service\BankStatement;

class Parser
{

    public const HEADERS = [
        'date',
        'amount',
        'description',
        'balance'
    ];

    const COLUMNS = 4;


    /**
     * Parser constructor.
     */
    public function __construct()
    {
    }

    public function parseFile(string $filePath) {
        $dataArray = [];

        $row = 0;
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                for ($i=0; $i < self::COLUMNS; $i++) {
                    $dataArray[$row][] = $data[$i];
                }
                $row++;
            }
            fclose($handle);
        }

        return $dataArray;
    }


}