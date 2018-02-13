<?php

namespace AppBundle\Service;


class ImportCSV
{
    public function readCsv($filename)
    {
        $path = realpath($filename);

        $result = array();
        $row = 1;
        if (($handle = fopen($path, 'r')) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $result[] = $data;
                $row++;
            }
            fclose($handle);
        }

        print_r($result);

    }
}