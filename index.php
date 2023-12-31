<?php 

spl_autoload_register(function($class) 
{
    if(file_exists('./control/' . $class . '.php'))
    {
        require_once './control/' . $class . '.php';
    }
});

$class = isset($_REQUEST['class']) ? $_REQUEST['class'] : 'LandPage'; 
$method = isset($_REQUEST['method']) ? $_REQUEST['method'] : null;
  
if(class_exists($class))
{
    $pagina = new $class($_REQUEST, $_FILES);
    if(!empty($method) and method_exists($class, $method))
    {
        $pagina->$method($_REQUEST, $_FILES);
    }
    $pagina->show();
} else {
    $error404 = "<h1>404</h1> <br> <a href='index.php?class=LandPage'>Ir para o inicio</a>";
    print $error404;
}

