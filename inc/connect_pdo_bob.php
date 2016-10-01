<?
        $tns = "  
        (DESCRIPTION =
            (ADDRESS_LIST =
              (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.1.3)(PORT = 1521))
            )
            (CONNECT_DATA =
              (SERVICE_NAME = ncue)
            )
          ) ";
        
        try{
            $dbh = new PDO("oci:dbname=".$tns,"bob","boblee");
        }catch(PDOException $e){
            echo ($e->getMessage());
        }

?>