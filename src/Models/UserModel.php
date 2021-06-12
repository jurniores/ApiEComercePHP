<?php 




class UserModel extends Mysql{

   
    public function Insert($nome,$email,$senha, $master){
                
        try{
            $sql = "INSERT INTO $this->tabela (nome,email,senha, master) VALUES (:nome,:email,:senha, :master);";
            $sth = $this->coon->prepare($sql);
            $sth->bindValue(':nome',$nome);
            $sth->bindValue(':email',$email);
            $sth->bindValue(':senha',$senha);
            $sth->bindValue(':master',$master);
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