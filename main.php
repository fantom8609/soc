<?php
echo $_SERVER['REQUEST_URI'];
echo "<br>";
echo trim($_SERVER['REQUEST_URI'], '/');
echo "<hr>";

$mas = array('Admin',"User","Task");
$res = array_shift($mas) . 'Controller'; // array_shift($mas) удаляет первый элемент из массива и возвращает его
echo $res;
print_r($mas) ;

echo "<hr>";

$string = "21-11-2015";
$pattern = "/([0-9]{2})-([0-9]{2})-([0-9]{4})/";
$replacement = "Year: $3;  Month: $2;  Day:  $1";

echo preg_replace($pattern , $replacement, $string);

?>