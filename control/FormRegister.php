<?php 

require_once 'model/Preferences.php';
require_once 'model/User.php';
class FormRegister
{
    private $html;
    private $data;

    public function __construct()
    {
        $this->startSession();
        $this->html = file_get_contents("Layout/html/register.html");
        $this->data = ['id'       => '',
                       'email'    => '',
                       'senha'    => ''];
    }
    public function startSession()
    {
        if(!isset($_SESSION)){
            session_start();
           
        }
        
        if(isset($_SESSION['user']))
        {
          return  header('Location: index.php?class=Dashboard');
        }
    }

    public function load()
    {
        try 
        {
            $preferences = Preferences::getAll();
            
            foreach($preferences as $preference)
            {
                return  $this->html = str_replace('{logo}', $preference['headerLogo'], $this->html);
            }
        }
        catch(Exception $e)
        {
           print $e->getMessage();
        }
    }

    public function save($params)
    {
        try 
        {
            $pessoa = User::save($params);
            return  header("Location: index.php?class=Login");
        } 
        catch (Exception $e) 
        {
            return print $e->getMessage();
        }  
    }

    public function show()  
    {
        $this->load();
        print $this->html;
    }
}

