<?php 




class EnderecoModel extends Mysql{

   
    public function Insert($rua,$bairro,$cidade, $estado,$numero, $cep, $id_user){
        
        try{
            $sql = "INSERT INTO $this->tabela (rua,bairro,cidade,estado,numero,cep,id_user) VALUES (:rua,:bairro,:cidade,:estado,:numero,:cep,:id_user);";
            $sth = $this->coon->prepare($sql);
            $sth->bindValue(':rua',$rua);
            $sth->bindValue(':bairro',$bairro);
            $sth->bindValue(':cidade',$cidade);
            $sth->bindValue(':estado',$estado);
            $sth->bindValue(':numero',$numero);
            $sth->bindValue(':id_user',$id_user);
            $sth->bindValue(':cep',$cep);
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