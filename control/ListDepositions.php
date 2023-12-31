<?php

require_once "model/Depositions.php";
class ListDepositions
{
    private $html;
    private $items;

    public function __construct()
    {
        $this->html = file_get_contents('Layout/html/listDepositions.html');
        $this->items = '';
        $this->startSession();
    }

    public function startSession()
    {
        if(!isset($_SESSION)){
            session_start();
        }

        if(!isset($_SESSION['user']))
        {
            die("Você não está auth <a href='index.php?class=Login'>Faça o Login</a>");
        }
    }

    public function load()
    {
        try {
            $depoimentos = Depositions::getAll();

            foreach ($depoimentos as $depoimento) {
                $item = file_get_contents('Layout/html/depositionsItem.html');

                $item = str_replace('{id}',                 $depoimento['id'],              $item);
                $item = str_replace('{name}',               $depoimento['name'],            $item);
                $item = str_replace('{title}',              $depoimento['title'],           $item);
                $item = str_replace('{function}',           $depoimento['function'],        $item);
                $item = str_replace('{description}',        $depoimento['description'],     $item);
                $item = str_replace('{photograph}',         $depoimento['photograph'],      $item);
                $item = str_replace('{backgroundImage}',    $depoimento['backgroundImage'], $item);

                $this->items .= $item;
            }

            return $this->html = str_replace('{items}', $this->items, $this->html);
        } catch (Exception $e) {
            return print $e->getMessage();
        }
    }

    public function delete($params)
    {
        try {
            $id = (int) $params['id'];
            Depositions::delete($id);
            return header("Location: index.php?class=ListDepositions");
        } catch (Exception $e) {
            return print $e->getMessage();
        }
    }

    public function show()
    {
        $this->load();
        print $this->html;
        return;
    }
}
