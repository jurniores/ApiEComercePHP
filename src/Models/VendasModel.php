<?php 




class VendasModel extends Mysql{

    
   
    public function Insert($nome,$tipo,$descricao, $desconto,$valor, $destaque, $id_user, $categoria){
        
        try{
            $sql = "INSERT INTO $this->tabela (nome,tipo, descricao, desconto, valor, destaque, id_user, categoria) VALUES (:nome,:tipo,:descricao,:desconto,:valor,:destaque,:id_user,:categoria);";
            $sth = $this->coon->prepare($sql);
            $sth->bindValue(':nome',$nome);
            $sth->bindValue(':tipo',$tipo);
            $sth->bindValue(':descricao',$descricao);
            $sth->bindValue(':desconto',$desconto);
            $sth->bindValue(':valor',$valor);
            $sth->bindValue(':destaque',$destaque);
            $sth->bindValue(':id_user',$id_user);
            $sth->bindValue(':categoria',$categoria);
            $sth->execute();

            if($sth->rowCount()){
                return $this->coon->lastInsertId();
            }else{
                header('HTTP/1.0 404 Not Found', true, 404);
                return json_encode(['Error'=>'Erro ao inserir dados no banco']);
            }
            

            }catch(Exception $e){
                return json_encode($e);
            }
    }
}