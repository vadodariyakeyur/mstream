<?php

class Artist{

    private $id;
    private $con;

    public function __construct($con, $id){
        $this->id = $id;
        $this->con = $con;
    }

    public function getName(){
        $artistQuery = mysqli_query($this->con,"SELECT name FROM artists WHERE id='$this->id'");
        $artist = mysqli_fetch_array($artistQuery);
        return $artist['name'];
    }

    public function getId(){
        return $this->id;
    }

    public function getSongIds(){
        $query = mysqli_query($this->con,"SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays DESC");
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_push($array,$row['id']);
        }

        return $array;
    }

}


?>