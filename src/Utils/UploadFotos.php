<?php


class Upload{
    static function Imagem(){
        $validaImg = ['image/jpeg','image/png','image/jpg'];

        if(isset($_FILES['img'])){
            $image = $_FILES['img'];
            foreach($image['name'] as $index =>$img){
                if(!in_array($image['type'][$index],$validaImg)){
                    header('HTTP/1.0 404 Not Found', true, 404);
                    echo json_encode(['Error'=>'Envie somente imagens']);
                    exit;
                }
            }
            foreach($image['name'] as $index =>$img){
                $name = rand(1,999)*5598415 .'fotoecomerce'.rand(1,999)*5598415 .'.'.explode('/',$image['type'][$index])[1];
                move_uploaded_file($image['tmp_name'][$index],'../../upload/'.$name);
                $arrayImg[$index] = $name;
                
            }
            return json_encode($arrayImg);
            
            
        }else{
            header('HTTP/1.0 404 Not Found', true, 404);
            echo json_encode(['Error'=>'Envie o campo img']);
            exit;
        }
                
    }
    static function DeleteFile($id){
        global $Fotos;
        $dados = $Fotos->FindOne('id_venda',$id['id']);
        $fotos = json_decode($dados,true);

        if(isset($fotos['Error'])){
            echo $dados;
            exit;
        } 
        $name = json_decode($fotos['nome'],true);
        if(file_exists('../../upload/'.$name[0])){
            unlink('../../upload/'.$name[0]);
        }else{
            return Erro('Não existe foto no servidor');
        };
        
        
        $Fotos->Delete($fotos['id']);
    }
}