<?php 

$data = json_decode(file_get_contents(__DIR__."/../public/data/globals.txt"),true);



// foreach($data as $key => $value){
    
// }

usort($data,function($a,$b){
    if($a["date_month"] == $b["date_month"])
        return $a["date_day"] > $b["date_day"];
    return $a["date_month"] > $b["date_month"];
});

file_put_contents(__DIR__."/../public/data/globals_2.txt",json_encode($data));
