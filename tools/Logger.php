<?php
namespace qeywork;

    
    /* Author    : Kenneth Katzgrau < katzgrau@gmail.com >
     * Date    : July 26, 2008
     * Website    : http://codefury.net
     * Version    : 1.0
     *
     * Usage: 
     *        $log = new Logger ( "log.txt" , Logger::INFO );
     *        $log->LogInfo("Returned a million search results");    //Prints to the log file
     *        $log->LogFATAL("Oh dear.");        //Prints to the log file
     *        $log->LogDebug("x = 5");    //Prints nothing due to _priority setting
     */
    
    class Logger 
    {
        const DEBUG        = 1;    // Most Verbose
        const INFO         = 2;    // ...
        const WARN         = 3;    // ...
        const ERROR        = 4;    // ...
        const FATAL        = 5;    // Least Verbose
        const OFF          = 6;    // Nothing at all.
        
        const LOG_OPEN     = 1;
        const OPEN_FAILED  = 2;
        const LOG_CLOSED   = 3;
        
        /* Public members: Not so much of an example of encapsulation, but that's okay. (lil-Dexx: lol) */
        public $LogStatus     = Logger::LOG_CLOSED;
        public $DateFormat    = "Y-m-d G:i:s";
        public $MessageQueue;
    
        private $_logFile;
        private $_priority = Logger::INFO;
        
        private $file_handle;
        
        public function __construct($logFilePath, $priority = Logger::DEBUG )
        {
            if ( $priority == Logger::OFF ) return;
            
            $this->_logFile = $logFilePath;
            $this->MessageQueue = array();
            $this->_priority = $priority;
            
            if ( file_exists( $this->_logFile ) )
            {
                if ( !is_writable($this->_logFile) )
                {
                    $this->LogStatus = Logger::OPEN_FAILED;
                    $this->MessageQueue[] = "The file exists, but could not be opened for writing. Check that appropriate permissions have been set.";
                    return;
                }
            }
            
            if (($this->file_handle = fopen( $this->_logFile , "a" )) == true ) {
                $this->LogStatus = Logger::LOG_OPEN;
                $this->MessageQueue[] = "The log file was opened successfully.";
            } else {
                $this->LogStatus = Logger::OPEN_FAILED;
                $this->MessageQueue[] = "The file could not be opened. Check permissions.";
            }
            
            return;
        }
        
        public function __destruct()
        {
            if ( $this->file_handle )
                fclose( $this->file_handle );
        }
        
        public function info($line)
        {
            $this->log( $line , Logger::INFO );
        }
        
        public function debug($line)
        {
            $this->log( $line , Logger::DEBUG );
        }
        
        public function warning($line)
        {
            $this->log( $line , Logger::WARN );    
        }
        
        public function error($line)
        {
            $this->log( $line , Logger::ERROR );        
        }

        public function fatal($line)
        {
            $this->log( $line , Logger::FATAL );
        }
        
        public function log($line, $priority)
        {            
            if ( $this->_priority <= $priority )
            {
                $status = $this->getTimeLine( $priority );
                $this->writeFreeFormLine ( "$status $line \n" );
            }
        }
        
        public function writeFreeFormLine( $line )
        {
            if ( $this->LogStatus == Logger::LOG_OPEN && $this->_priority != Logger::OFF ) {
                if ( fwrite( $this->file_handle , $line ) === false) {
                    $this->MessageQueue[] = "The file could not be written to. Check that appropriate permissions have been set.";
                }
            }
        }
        
        private function getTimeLine( $level )
        {
            $time = date( $this->DateFormat );
        
            switch( $level )
            {
                case Logger::INFO:
                    return "$time - INFO  -->";
                    
                case Logger::WARN:
                    return "$time - WARN  -->";   
                    
                case Logger::DEBUG:
                    return "$time - DEBUG -->";   
                    
                case Logger::ERROR:
                    return "$time - ERROR -->";
                    
                case Logger::FATAL:
                    return "$time - FATAL -->";
                    
                default:
                    return "$time - LOG   -->";
            }
        }
    }
?>