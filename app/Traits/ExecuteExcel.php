<?php

namespace App\Traits;
use Carbon\Carbon;
use Exception;
use Excel;

trait ExecuteExcel {
    public function load($path, $sheetname, $callback)
    {
        return Excel::load($path, function ($doc) use (&$sheetname, &$callback) {
            $doc->sheet($sheetname, function ($sheet) use (&$callback) {
                $callback($sheet);
            });
        });
    }

    public function setValue(&$sheet, $address, $value)
    {
        $sheet->cells($address, function ($cells) use ($value) {
            $cells->setValue($value);
        });
    }

    public function setWidth(&$sheet, $arraySize)
    {
        $sheet->setWidth($arraySize);
    }

    public function toTimeStamp($value) {
        if(isset($value)) {
            if(is_numeric($value))
                return Carbon::createFromTimestamp((int)($value - 25569) * 86400)->format(config('app.format_date'));
            else if(is_string($value)){
                try{
                    return preg_replace('!\s+!', '', trim($value));               
                }
                catch(Exception $ex) {
                    throw new Exception('Invalid date format value.'); 
                }
            }
            else
                throw new Exception('Invalid format value.'); 
        }
        else{
            throw new Exception('Value can not null.');
        }
    }

    function columnLetter($c){

        $c = intval($c);
        if ($c <= 0) return '';
    
        $letter = '';
                 
        while($c != 0){
           $p = ($c - 1) % 26;
           $c = intval(($c - $p) / 26);
           $letter = chr(65 + $p) . $letter;
        }
        
        return $letter;
            
    }
}