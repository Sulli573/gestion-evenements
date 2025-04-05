<?php
class TokenModel extends DefaultModel {

    public function generateToken($userId) {
        //generation du token
        $token=bin2hex(random_bytes(32));
        $sql="INSERT INTO user_tokens(user_id,token) VALUES (:user_id,:token)";
        $stmt=$this->db->prepare($sql);
        $stmt->bindParam(':user_id',$userId,PDO::PARAM_INT);
        $stmt->bindParam(':token',$token,PDO::PARAM_STR);

        if($stmt->execute()){
            return $token;
        }else{
            return false;
        }
    }
    public function validateToken ($userId,$token) {
        $sql = "SELECT id FROM user_tokens WHERE user_id =:user_id AND token =:token";
        $stmt=$this->db->prepare($sql);
        $stmt->bindParam(':user_id',$userId,PDO::PARAM_INT);
        $stmt->bindParam(':token',$token,PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->fetch()) {
            return true;
        }
        return false;
    }
    public function deleteToken($token) {
        $sql="DELETE FROM user_tokens WHERE token =:token";
        $stmt=$this->db->prepare($sql);
        $stmt->bindParam(':token',$token,PDO::PARAM_STR);
        return $stmt->execute();
    }
}
?>