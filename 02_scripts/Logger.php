<?php
    session_start();
    $date = date("H:i:s". "\t". "d.m.Y");
    //Ouverture du fichier log
    $log = fopen("06_documents/datalog/datalog.txt", 'a+');
    //On insère une nouvelle ligne dans le fichier
    fputs ($log, $date. "\t". $_SERVER["REMOTE_ADDR"]. "\t". $_SESSION['file']. "\t". $_SERVER['HTTP_REFERER']. "\n");
    //Fermeture du fichier
    fclose ($log);
?>