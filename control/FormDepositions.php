<?php

require_once 'model/Depositions.php';

class FormDepositions
{
    private $html;
    private $data;
    private $folder;

    public function __construct()
    {
        $this->startSession();
        $this->html     = file_get_contents("Layout/html/depositions.html");
        $this->folder   = './files/';
        $this->data     = [
            'id'              => '',
            'name'            => '',
            'title'           => '',
            'function'        => '',
            'description'     => '',
            'photograph'      => '',
            'backgroundImage' => ''
        ];
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

    public function edit($params)
    {
        try {
            $id  = (int) $params['id'];
            $this->data = Depositions::find($id);
        } catch (Exception $e) {
            return  print $e->getMessage();
        }
    }

    public function save($request, $files)
    {
        try {
            foreach ($files as $campo => $file) {
                $nomeDoArquivo = $file['name'];
                $novoNomeDoArquivo = uniqid();
                $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

                $path = $this->folder . $novoNomeDoArquivo . '.' . $extensao;

                $deu_certo = move_uploaded_file($file['tmp_name'], $path);

                if ($deu_certo) {
                    $this->data[$campo] = $path;
                }
            }


            $this->data['id']             = $request['id'];
            $this->data['name']             = $request['name'];
            $this->data['function']         = $request['function'];
            $this->data['title']            = $request['title'];
            $this->data['description']      = $request['description'];



            Depositions::save($this->data);
            return  header("Location: index.php?class=ListDepositions");
        } catch (Exception $e) {
            return print $e->getMessage();
        }
    }

    public function show()
    {
        $this->html = str_replace('{id}',                $this->data['id'],                $this->html);
        $this->html = str_replace('{name}',              $this->data['name'],              $this->html);
        $this->html = str_replace('{title}',             $this->data['title'],             $this->html);
        $this->html = str_replace('{function}',          $this->data['function'],          $this->html);
        $this->html = str_replace('{description}',       $this->data['description'],       $this->html);
        $this->html = str_replace('{photograph}',        $this->data['photograph'],        $this->html);
        $this->html = str_replace('{backgroundImage}',   $this->data['backgroundImage'],   $this->html);
        print $this->html;
    }
}


// TODO - colocar tudo em ingles até os arquivos    