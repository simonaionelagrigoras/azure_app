<?php
/**
 * Created by PhpStorm.
 * User: Simona
 * Date: 28/02/2019
 * Time: 16:24
 */

class Logger{

    public function log($data){
        $logFile = 'http://' . $_SERVER['SERVER_NAME']  . '/var/log/request.log';
        $handle = fopen($logFile, 'w');
        $formattedLog = $this->formatLog($data);
        file_put_contents($logFile, $formattedLog, FILE_APPEND | LOCK_EX);
    }

    protected function formatLog($data)
    {
        //[2018-12-06 10:53:38]
        $date = date( "[Y-m-d H:i:s]",time());
        return $date . " " . json_encode($data) . "\n";
    }
}