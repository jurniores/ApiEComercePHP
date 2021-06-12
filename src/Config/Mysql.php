<?php



class Mysql{
    protected $coon;
    protected $table;
    
    public function __construct(PDO $coon,$table)
    {
        $this->coon = $coon;
        $this->tabela = $table;
        
    }
    public function FindAll(){
        try{
        $sql = "SELECT * FROM $this->tabela;";
        $sth = $this->coon->prepare($sql);
        $sth->execute();
        
        if($sth->rowCount()){
            $data = $sth->fetchAll(PDO::FETCH_NAMED);
            return json_encode($data);
        }else{
            header('HTTP/1.0 404 Not Found', true, 404);
            return json_encode(['Error'=>'Não existe dados na tabela']);
        }
        
        }catch(Exception $e){
            header('HTTP/1.0 404 Not Found', true, 404);
            return json_encode($e);
        }
    }
  

    public function FindOne($campo,$value){

        try{
        $sql = "SELECT * FROM $this->tabela WHERE $campo=:val;";
        $sth = $this->coon->prepare($sql);
        $sth->bindValue(':val', $value);
        $sth->execute();
        if($sth->rowCount()){
            $data = $sth->fetch(PDO::FETCH_NAMED);
            return json_encode($data);
        }else{
            header('HTTP/1.0 404 Not Found', true, 404);
            return json_encode(['Error'=>'Não existe dados na tablea']);
            
        }
        

        return json_encode($data);
        }catch(Exception $e){
            header('HTTP/1.0 404 Not Found', true, 404);
            return json_encode($e);
        }

    }
    public function FindId($id){
        try{
            $sql = "SELECT * FROM $this->tabela WHERE id=:id;";
            $sth = $this->coon->prepare($sql);
            $sth->bindValue(':id', $id);
            $sth->execute();

            if($sth->rowCount()){
                $data = $sth->fetch(PDO::FETCH_NAMED);
                return json_encode($data);
            }else{
                header('HTTP/1.0 404 Not Found', true, 404);
                return json_encode(['Error'=>'Não existe dados na tablea']);
                exit;
            }
    
            return $data;
        }catch(Exception $e){
            header('HTTP/1.0 404 Not Found', true, 404);
            return json_encode($e);
        }
        

    }
    public function Update($array=[],$id){
       try{
        foreach($array as $campo=>$valor){
            $valor2 = $valor;
            if($campo=='email'){
                $valor2 = strtolower($valor);
            }
            $sql = "UPDATE $this->tabela SET $campo=:nome WHERE id=:id;";
            $sth = $this->coon->prepare($sql);
            $sth->bindValue(':nome',$valor2);
            $sth->bindValue(':id',$id);
            $sth->execute();
              
        }
        
            return json_encode(['Success'=>'Editado com sucesso!']);

       }catch(Exception $e){
            header('HTTP/1.0 404 Not Found', true, 404);
            return json_encode(['Erro'=>'Não foi possível editar a tabela']);
        }
        

        
    }
    public function Delete($id){
        try{
        $sql = "DELETE FROM $this->tabela WHERE id=:id;";
        $sth = $this->coon->prepare($sql);
        $sth->bindValue(':id',$id);
        $sth->execute();

        if($sth->rowCount()){
            
            return json_encode(['Success'=>'Deletado com sucesso!']);
        }else{
            header('HTTP/1.0 404 Not Found', true, 404);
            return json_encode(['Error'=>'Não existe dados na tablea']);
        }
        
        }catch(Exception $e){
            header('HTTP/1.0 404 Not Found', true, 404);
            return json_encode($e);
        }
        
    }  
}

