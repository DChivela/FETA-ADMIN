<?php
class Administrador{
    protected $conn;
    function __construct(pdo $conn){
        $this->conn = $conn;
    }

    public function get(int $id) : array{
        
        $query=$this->conn->prepare("SELECT * FROM administrador WHERE id = ?");
        $query->bindValue(1, $id);
        $query->execute();
        return $query->fetch();
    }
    public function getByEmail(string $email) : array{
        
        $query=$this->conn->prepare("SELECT * FROM administrador WHERE email = ?");
        $query->bindValue(1, $email);
        $query->execute();
        return $query->fetch();
    }

    public function verificaEmail(string $email) : bool{
        
        $query=$this->conn->prepare("SELECT * FROM administrador WHERE email = ?");
        $query->bindValue(1, $email);
        $query->execute();
        $res = $query->fetch();

        if(gettype($res) == "boolean"){ return false; }

        if(count($res) > 0){ return true; }
        else{ return false; }

    }
    public function verificaCodigo(string $email, string $codigo) : bool{
        
        $query=$this->conn->prepare("SELECT codigo FROM administrador WHERE email = ?");
        $query->bindValue(1, $email);
        $query->execute();
        if($query->fetch()[0] == $codigo){ return true; }
        else{ return false; }
    }
    public function setCodigo(string $email, string $codigo) : bool{
        
        $query=$this->conn->prepare("UPDATE administrador SET codigo = ? WHERE email = ?");
        $query->bindValue(1, $codigo);
        $query->bindValue(2, $email);
        $query->execute();

        return true;
    }

    public function alterarDetalhes(int $id, string $nome, string $email) : array {
        $query=$this->conn->prepare("UPDATE administrador SET nome = ?, email = ? WHERE id = ? ");
        $query->bindValue(1, $nome);
        $query->bindValue(2, $email);
        $query->bindValue(3, $id);
        $query->execute();

        return $this->get($id);
    }
    
    public function getPasse(string $email) : string {
        $query=$this->conn->prepare("SELECT passe FROM administrador WHERE email = ?");
        $query->bindValue(1, $email);
        $query->execute();

        return $query->fetch()[0];
    }
    public function verificaPasse(string $email, string $passe) : bool {
        $passeAtual = self::getPasse($email);
        if($passeAtual == $passe){
            return true;
        }
        return false;
    }
    public function alterarPasse(string $email, string $passe) : bool {

        $passeAntiga = self::getPasse($email);

        $query=$this->conn->prepare("UPDATE administrador SET passe = ? WHERE email = ? ");
        $query->bindValue(1, $passe);
        $query->bindValue(2, $email);
        $query->execute();

        
        return true;

    }
    public function login(string $email, string $passe) : bool {

        
        $query=$this->conn->prepare("SELECT * FROM administrador WHERE email = ? AND passe = ?");
        $query->bindValue(1, $email);
        $query->bindValue(2, $passe);
        $query->execute();
        $res = $query->fetchAll();
        

        if(count($res) > 0){
            $id = $res[0]['id'];
           
            return true;
        }
        return false;
    }
}