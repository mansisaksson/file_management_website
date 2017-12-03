<?php
require_once dirname(__DIR__).'/header.php';

class Session
{
    const SESSION_STARTED = TRUE;
    const SESSION_NOT_STARTED = FALSE;
    
    private $sessionState = self::SESSION_NOT_STARTED;
    private static $instance;
    
    private function __construct() {}
    
    
    public function UserName()
    {
        return isset(self::$instance) ? self::$instance->username : null;
    }
    
    public function SetUserName($username)
    {
        if (isset(self::$instance)) 
            return self::$instance->username = $username;
    }
    
    public function UserID()
    {
        return isset(self::$instance) ? self::$instance->userID : null;
    }
    
    public function SetUserID($userID)
    {
        if (isset(self::$instance))
            self::$instance->userID = $userID;
    }
    
    /**
     *    Destroys any existing instance of the 'Session'.
     *    and initializes a new one.
     *
     *    @return    object
     **/
    public static function createNewSession()
    {
        if (isset(self::$instance))
        {
            self::$instance->destroy();
        }
        
        return Session::getInstance();
    }
    
    /**
     *    Returns THE instance of 'Session'.
     *    The session is automatically initialized if it wasn't.
     *
     *    @return    object
     **/
    public static function getInstance()
    {
        if ( !isset(self::$instance))
        {
            self::$instance = new self;
        }
        
        self::$instance->startSession();
        
        return self::$instance;
    }
    
    /**
     *    (Re)starts the session.
     *
     *    @return    bool    TRUE if the session has been initialized, else FALSE.
     **/
    private function startSession()
    {
        if ( $this->sessionState == self::SESSION_NOT_STARTED )
        {
            $this->sessionState = session_start();
        }
        
        return $this->sessionState;
    }
    
    /**
     *    Destroys the current session.
     *
     *    @return    bool    TRUE is session has been deleted, else FALSE.
     **/
    public function destroy()
    {
        if ( $this->sessionState == self::SESSION_STARTED )
        {
            $this->sessionState = !session_destroy();
            unset( $_SESSION );
            
            return !$this->sessionState;
        }
        
        return FALSE;
    }
    
    public function __set( $name , $value )
    {
        $_SESSION[$name] = $value;
    }
    
    public function __get( $name )
    {
        if ( isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
    }
    
    public function __isset( $name )
    {
        return isset($_SESSION[$name]);
    }
    
    public function __unset( $name )
    {
        unset( $_SESSION[$name] );
    }
}
?>