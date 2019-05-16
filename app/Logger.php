<?php
/**
 * Created by PhpStorm.
 * User: Simona
 * Date: 28/02/2019
 * Time: 16:24
 */

class Logger{

    public function log($data){
        $logFile = $_SERVER['DOCUMENT_ROOT']. '\var\log\request.log';
        $formattedLog = $this->formatLog($data);
        if ( file_put_contents($logFile, $formattedLog, FILE_APPEND | LOCK_EX) === FALSE) {
            // the message print that the file could not be created.
            print 'The file could not be created.';
        }
    }

    protected function formatLog($data)
    {
        //[2018-12-06 10:53:38]
        $date = date( "[Y-m-d H:i:s]",time());
        return $date . " " . json_encode($data) . "\n";
    }
}