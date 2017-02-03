<?php
$base = __DIR__ . '/../app/';

$folders = [
'lib',
'model',
'controller',
'middleware',
'validation',
'route',
];

require_once $base.'controller/MasterController.php';
require_once $base.'model/MasterModel.php';



foreach($folders as $f)
{
    foreach (glob($base . "$f/*.php") as $k => $filename)
    {
        require_once $filename;
    }
}


$path = $base.'modules';


loadFiles($path);

function loadFiles($path)
{   
    global $app;
    $results = scandir($path);
    foreach ($results as $result) 
    { 
        if ($result === '.' or $result === '..') continue;
        $final_path = $path."/".$result;
        if(is_dir($final_path))
        {
            loadFiles($final_path);
        }
        else
        {
            $parts = explode(".", $result );
            if(isset($parts[1]))
            {
                if($parts[1] === "php")
                {
                    require_once $final_path;
                }
            }
        }
    }
}












/*


foreach ($results as $result) {
    if ($result === '.' or $result === '..') continue;

    if (is_dir($path . '/' . $result)) {
        foreach (glob($path . '/' . $result . "/*.php") as $k => $filename)
        {
            require_once $filename;

        }
    }
}*/