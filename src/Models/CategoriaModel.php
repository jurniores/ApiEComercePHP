<?php 




class CategoriaModel extends Mysql{

   
    public function Insert($nome){
        
        try{
            $sql = "INSERT INTO $this->tabela (nome) VALUES (:nome);";
            $sth = $this->coon->prepare($sql);
            $sth->bindValue(':nome',$nome);
            $sth->execute();

            if($sth->rowCount()){
                return json_encode(['Success'=>'Inserido com sucesso!']);
            }else{
                header('HTTP/1.0 404 Not Found', true, 404);
                return json_encode(['Error'=>'Erro ao inserir dados no banco']);
            }
            

            }catch(Exception $e){
                return json_encode($e);
            }
    }
}