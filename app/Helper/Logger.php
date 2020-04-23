<?php

namespace App\Helper;

class Logger
{
    private $logFileName;
    private $starter;

    public function __construct($starter = false, $logFileName = "default")
    {
        $this->logFileName = $logFileName;
        $this->starter     = $starter;

//      $this->logger->flyLog();            <--------------- ШАБЛОН
    }

    public function setLog($str)
    {
        if($this->starter != false)
        {
            $pathFile   = "/var/www/ruletka-server.com/public_html/app/Storage/".$this->logFileName.".txt";
            $updateFile =  "|" . $str;
            file_put_contents($pathFile, $updateFile, FILE_APPEND);
        }
    }

    public function getAllLogs()
    {
        if($this->starter != false)
        {
            $pathFile   = "/var/www/ruletka-server.com/public_html/app/Storage/".$this->logFileName.".txt";
            $bodyFileLogs = file_get_contents($pathFile);
            $arrayLogs = explode("|", $bodyFileLogs);

            return $bodyFileLogs;
        }
    }

    public function printLogs()
    {
        if($this->starter == false)
        {
            $arrayLogs = $this->getAllLogs();

            var_dump($arrayLogs);
            foreach ($arrayLogs as $log)
            {
                echo "<pre>";
                var_dump($log);
                echo "<pre>";
            }
        }
    }

    public function flyLog($str)
    {
        if($this->starter == "fly"){
            var_dump($str); echo "<br/>";
        }
    }
}

