<?php 




class FotosModel extends Mysql{

   
    public function Insert($nome,$id_venda){
        
        try{
            $sql = "INSERT INTO $this->tabela (nome, id_venda) VALUES (:nome,:id_venda);";
            $sth = $this->coon->prepare($sql);
            $sth->bindValue(':nome',$nome);
            $sth->bindValue(':id_venda',$id_venda);
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