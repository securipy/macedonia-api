<?php

$container = $app->getContainer();

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__.'/../app',[
            'debug' => true
        ]);


    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new Twig_Extensions_Extension_I18n());



    $getTextdomain = new Twig_SimpleFunction('get_textdomain', function () {
        return textdomain(NULL);
    });
    $setTextdomain = new Twig_SimpleFunction('set_textdomain', function ($domain) {
        textdomain($domain);
    });

    $view->getEnvironment()->addFunction($getTextdomain);
    $view->getEnvironment()->addFunction($setTextdomain);



    if(isset($_COOKIE['audit']) && !empty($_COOKIE['audit'])){

    $basePathModules = __DIR__ . '/../app/';

    $path = $basePathModules.'modules';
    $results = scandir($path);
    $menu = array();
    $content_xml = "";

    foreach ($results as $result) {
        if ($result === '.' or $result === '..') continue;
            if(file_exists($path . '/' . $result.'/menu.xml')){
                if($d = fopen($path . '/' . $result.'/menu.xml', "r")){
                    while ($aux= fgets($d, 1024)){
                        $content_xml .= $aux;
                    }
                    $xml = simplexml_load_string($content_xml);
                    for($i=0; $i<count($xml->options); $i++){
                        array_push($menu, array('text'=>$xml->options[$i]->text[0],'view'=>$xml->options[$i]->view[0],'icon'=>$xml->options[$i]->icon[0]));
                    }
                    fclose($d);
                }
            }
    }
        $view->getEnvironment()->addGlobal('menu_element',$menu);

        $view->getEnvironment()->addGlobal('ga_menu', True);
    }else{
        $view->getEnvironment()->addGlobal('ga_menu', False);    
    }

    return $view;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Database
$container['db'] = function($c){
    $connectionString = $c->get('settings')['connectionString'];
    
    $pdo = new PDO($connectionString['dns'], $connectionString['user'], $connectionString['pass']);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, TRUE);

    return $pdo; 
};

// Email
$container['mail'] = function($c){
    $emailString = $c->get('settings')['emailString'];

    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host       = $emailString['host']; // SMTP server
    $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->SMTPAuth   = $emailString['smtp_auth'];                  // enable SMTP authentication
    $mail->SMTPSecure = $emailString['smtp_secure'];                 // sets the prefix to the servier
    $mail->Port       = $emailString['port'];                   // set the SMTP port for the GMAIL server
    $mail->Username   = $emailString['username'];  // GMAIL username
    $mail->Password   = $emailString['password'];            // GMAIL password

    return $mail; 
};


// Models
$container['model'] = function($c){
    $base = (object)[
        'user' => new App\Model\UserModel($c->db),
        'auth' => new App\Model\AuthModel($c->db),
        'xrequest' => new App\Model\XRequestModel($c->db),
        'dashboard' => new App\Model\DashboardModel($c->db),
        'audit' => new App\Model\AuditModel($c->db),
        'aws' => new App\Model\AwsModel($c->db),
        'server' => new App\Model\ServerModel($c->db),
        'scripts' => new App\Model\ScriptsModel($c->db),
    ];

    $basePathModules = __DIR__ . '/../app/';

    $path = $basePathModules.'modules';
    $results = scandir($path);

    foreach ($results as $result) {
        if ($result === '.' or $result === '..') continue;
        if(file_exists($path . '/' . $result.'/'.ucfirst($result)."Model.php")){
            $model = "App\Model\\".ucfirst($result)."Model";
            $base->$result = new $model($c->db);
        }
    }

    return $base;
};





// Models
$container['controller'] = function($c){
    $base = (object)[
        'user' => new App\Controller\UserController($c->model->user),
        'auth' => new App\Controller\AuthController($c->model->auth),
        'xrequest' => new App\Controller\XRequestController($c->model->xrequest),
        'dashboard' => new App\Controller\DashboardController($c->model->dashboard),
        'audit' => new App\Controller\AuditController($c->model->audit),
        'aws' => new App\Controller\AwsController($c->model->aws),
        'server' => new App\Controller\ServerController($c->model->server,$c->db),
        'scripts' => new App\Controller\ScriptsController($c->model->scripts),
    ];

    $basePathModules = __DIR__ . '/../app/';

    $path = $basePathModules.'modules';
    $results = scandir($path);

    foreach ($results as $result) {
        if ($result === '.' or $result === '..') continue;
        if(file_exists($path . '/' . $result.'/'.ucfirst($result)."Controller.php")){
            $controller = "App\Controller\\".ucfirst($result)."Controller";
            $base->$result = new $controller($c->model->$result,$c->db);
        }
    }

    return $base;
};
