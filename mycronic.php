<?php

try {
    $cronStatusDb = array(
        'host' => 'host_name',
        'username' => 'ncconfig',
        'password' => 'password',
        'database' => 'database_name'
    );


    $argv[1] = isset($argv[1]) ? $argv[1] : '';
    $cronConfigs = json_decode($argv[1], true);
    if (!is_array($cronConfigs)) {
        echo 'INVALID CRON CONFIG: '.$argv[1];
        exit(1);
    }


    $returnStatusCode = 0;
    $objCommonFuncs = new CommonFuncs();
    $cronId = (int) $cronConfigs['cronId'];
    $cronCommand = $cronConfigs['command'];


    $startTime = microtime(true);
    if ($objCommonFuncs->shouldRunScript($cronConfigs)) {
        if ($cronId && $cronCommand) {
            $output = $objCommonFuncs->runCommand("$cronCommand 2>&1", $returnStatusCode);
        }
        else {
            $returnStatusCode = 255;
            $output = 'INVALID CRON CONFIG: '.$argv[1];
        }
    }
    else {
        $returnStatusCode = 255;
        $output = 'MAX CONCURRENCY REACHED';
    }
    $endTime = microtime(true);
    $timeTaken = ($endTime - $startTime);

    $returnStatus = $objCommonFuncs->isCronSuccessful($returnStatusCode, $output) ? 'SUCCESS' : 'ERROR';


    $toEmailIds = $objCommonFuncs->getToEmailIds($cronConfigs);
    $fromEmailId = $objCommonFuncs->getFromEmailId($cronConfigs);
    $sendEmail = (isset($cronConfigs['cronOutput']) && $cronConfigs['cronOutput'] == 'y');
    $cronAlias = (isset($cronConfigs['cronAlias']) && $cronConfigs['cronAlias'] != '') ? $cronConfigs['cronAlias'] : $cronCommand;
    $subject = "$returnStatus: $cronAlias";

    if ($sendEmail || $objCommonFuncs->shouldSendMail($cronConfigs, $returnStatusCode, $output, $timeTaken)) {
        if (count($toEmailIds) > 0) {
            if ($objCommonFuncs->isExecutionTimeExceeded($cronConfigs, $timeTaken)) {
                $subject .= ' : <<Execution Time Exceeded>>';
            }

            $objCommonFuncs->sendEmail($toEmailIds, $fromEmailId, $subject, $output);
        }
    }

    $objCommonFuncs->logCronEnding($cronConfigs, $returnStatus, $output, $startTime, $endTime);
}
catch (Exception $e) {
    $objCommonFuncs->sendEmail($toEmailIds, $fromEmailId, 'Mycronic failed: '.$cronAlias, $e->getMessage());
    exit(1);
}


class CommonFuncs
{
    private $connDb;

    public function shouldRunScript($cronConfigs) {
        $maxConcurrency = isset($cronConfigs['maxConcurrency']) ? (int) $cronConfigs['maxConcurrency'] : 0;

        if ($maxConcurrency > 0) {
            $cronId = $cronConfigs['cronId'];
            $command = "ps ax| grep 'mycronic.php' | grep '\"cronId\":\"$cronId\"' | grep -v 'grep ' | grep -v '/bin/sh -c' | wc -l";
            $currentRuns = ((int) $this->runCommand($command) - 1);

            return ($currentRuns < $maxConcurrency);
        }

        return true;
    }

    public function runCommand($command, &$returnStatusCode = 0) {
        ob_start();
        passthru($command, $returnStatusCode);
        $output = ob_get_contents();
        ob_end_clean();

        return trim($output);
    }

    public function shouldSendMail($cronConfigs, $returnStatusCode, $output, $timeTaken) {
        return (!$this->isCronSuccessful($returnStatusCode, $output) ||
            $this->isExecutionTimeExceeded($cronConfigs, $timeTaken) ||
            strtolower($cronConfigs['cronOutput']) == 'y'
        );
    }

    public function isCronSuccessful($returnStatusCode, $output) {
        return ($returnStatusCode == 0);
    }

    public function isExecutionTimeExceeded($cronConfigs, $timeTaken) {
        if (isset($cronConfigs['timeAlert'])) {
            return ($cronConfigs['timeAlert'] * 60 < $timeTaken);
        }

        return false;
    }

    public function getToEmailIds($cronConfigs) {
        $toEmailIds = array();
        $emailIds = (isset($cronConfigs['toEmail'])) ? explode(',', $cronConfigs['toEmail']) : array();

        foreach ($emailIds as $emailId) {
            $emailId = trim($emailId);

            if ($emailId && filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
                $toEmailIds[] = $emailId;
            }
        }

        return $toEmailIds;
    }

    public function getFromEmailId($cronConfigs) {
        if (isset($cronConfigs['fromEmail']) && filter_var($cronConfigs['fromEmail'], FILTER_VALIDATE_EMAIL)) {
            return $cronConfigs['fromEmail'];
        }
        else {
            return $this->getUser().'_crons@'.$this->getHost();
        }
    }

    public function logCronEnding($cronConfigs, $returnStatus, $output, $startTime, $endTime) {
        $connDb = $this->getCronDbConnection();

        $sql = 'INSERT INTO cron_status (cronId, cronStatus, cronOutput, startTime, endTime, logTime)
            VALUES
            (:cronId, :cronStatus, :cronOutput, :startTime, :endTime, NOW())';

        $res = $connDb->prepare($sql);
        $res->bindValue(':cronId', $cronConfigs['cronId'], PDO::PARAM_INT);
        $res->bindValue(':cronStatus', $returnStatus, PDO::PARAM_STR);
        $res->bindValue(':cronOutput', $output, PDO::PARAM_STR);
        $res->bindValue(':startTime', date('Y-m-d H:i:s', $startTime), PDO::PARAM_STR);
        $res->bindValue(':endTime', date('Y-m-d H:i:s', $endTime), PDO::PARAM_STR);
        $res->execute();
        $res->closeCursor();

    }

    public function sendEmail($toEmailIds, $fromEmailId, $subject, $body = '') {
        $strToEmailIds = implode(',', $toEmailIds);
        $fileDescriptor = popen("/usr/sbin/sendmail -t -f $fromEmailId ", 'w');

        if ($fileDescriptor) {
            fputs($fileDescriptor, "To: $strToEmailIds\n");
            fputs($fileDescriptor, "From: $fromEmailId\n");
            fputs($fileDescriptor, "Subject: $subject\n");
            fputs($fileDescriptor, "MIME-Version: 1.0\n");

            if (trim($body) != '') {
                $contentType = (stristr($body, '<html>')) ? 'text/html' : 'text/plain';

                fputs($fileDescriptor, "Content-Type: $contentType; charset=iso-8859-1\n");
                fputs($fileDescriptor, "Content-Transfer-Encoding: 8bit\n");
                fputs($fileDescriptor, "Content-Disposition: inline\n\n");
                fputs($fileDescriptor, $body."\n");
            }

            fputs($fileDescriptor, "\n.\n");
            return pclose($fileDescriptor);
        }
        else {
            mail($strToEmailIds, $subject, $body, "From: $fromEmailId");
        }
    }

    private function getCronDbConnection($force = true) {
        if (is_null($this->connDb) || $force) {
            global $cronStatusDb;

            $username = $cronStatusDb['username'];
            $password = $cronStatusDb['password'];
            $database = $cronStatusDb['database'];
            $hostIP = $this->resolveDNS($cronStatusDb['host']);

            $this->connDb = new PDO("mysql:host=$hostIP;dbname=$database;", $username, $password);
            $this->connDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->connDb;
    }

    private function getUser() {
        return $this->runCommand('id -u -n');
    }

    private function getHost() {
        return $this->runCommand('uname -n');
    }

    private function resolveDNS($dsn, $pdoDb = true) {
        $pattern = "/\{([^\}]*)\}/";
        preg_match_all($pattern, $dsn, $matches);

        if (count($matches) > 0) {
            foreach ($matches[1] as $match) {
                $resolvedAddr = $this->getIpAddress($match, $pdoDb);
                $dsn = preg_replace("/\{".$match."\}/", $resolvedAddr, $dsn);
            }
        }

        return $dsn;
    }

    private function getIpAddress($match, $pdoDb = true) {
        $hostIps = array();
        $srvRecords = $this->getDnsRecord('_db._tcp.'.$match.'.', 'SRV');

        if (count($srvRecords) > 0) {
            $host = trim($srvRecords[0]['target']);
            $port = trim($srvRecords[0]['port']);

            if ($port && $host) {
                $hostIps = $this->getDnsRecord($host, 'A');
            }
            elseif ($port && !$host) {
                $hostIps = $this->getDnsRecord($match, 'A');
            }

            if (count($hostIps) > 0) {
                if ($port) {
                    return ($pdoDb) ? $hostIps[0]['ip'].";port=$port" : $hostIps[0]['ip'].":$port";
                }
                else {
                    return $hostIps[0]['ip'];
                }
            }
        }

        throw new Exception('Not able to find dns record for: '. $match);
    }

    private function getDnsRecord($domain, $recordType = DNS_ANY) {
        if ($recordType == 'SRV' || $recordType == 'A') {
            $type = constant('DNS_'.$recordType);
        }
        else {
            $type = DNS_ANY;
        }

        $records = dns_get_record($domain, $type);
        if (!$records || count($records) == 0) {
            $records = dns_get_record($domain, $type);
        }

        return $records;
    }
}

