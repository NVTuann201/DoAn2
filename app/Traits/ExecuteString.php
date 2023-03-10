<?php

namespace App\Traits;

trait ExecuteString {    
    public function cappitalizeEachWord($input)
    {
        return preg_replace('!\s+!', ' ',mb_convert_case(trim($input), MB_CASE_TITLE, "UTF-8"));
    } 
    
    public function code($input)
    {
        return preg_replace('!\s+!', '', trim($input));
    }

    public function ucfirst($input)
    {
        return preg_replace('!\s+!', ' ', ucfirst(trim($input)));
    }

    public function gioiTinh($input){
        
        if(isset($input)){
            if($input == 'Nam'||$input=='nam'){
                return true;
            }
            return false;
        }
        return false;
    }

    public function boolean($input){
        if(isset($input)){
            if($input == 'Có'){
                return true;
            }
            return false;
        }
        return false;
    }

    public function trimAll($input)
    {
        return preg_replace('!\s+!', '', trim($input));
    }

    /**
     * Hàm loại bỏ dấu tiêng việt va chuyen thanh chu thuong
     */
    public function vn_to_str($str){
 
        $unicode = array(         
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',         
            'd'=>'đ',         
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',         
            'i'=>'í|ì|ỉ|ĩ|ị',         
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',         
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',         
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',         
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',         
            'D'=>'Đ',         
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',         
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',         
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',         
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',         
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',         
        );
         
        foreach($unicode as $nonUnicode=>$uni) {            
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);            
        }
        
        $str = str_replace(' ', '_',  $str);
            
        return strtolower($str);         
    }

    public function generateRandom($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function vietnamese_to_english($str){
 
        $unicode = array(         
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',         
            'd'=>'đ',         
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',         
            'i'=>'í|ì|ỉ|ĩ|ị',         
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',         
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',         
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',         
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',         
            'D'=>'Đ',         
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',         
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',         
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',         
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',         
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',         
        );
         
        foreach($unicode as $nonUnicode=>$uni) {            
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);            
        }
            
        return strtolower($str);         
    }
}