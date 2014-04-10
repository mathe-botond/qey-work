<?php
namespace qeywork;

/**
 * 
 * Contains operations with the current user
 * @author lil-Dexx
 *
 */
class OpenIdAuth extends AbstractAuth 
{    
    /**
     * Register user by inserting to database
     * @return string Result
     */
    public function register()
    {
        $params = Params::getInstance();
        $username = $params->username;
        $session = getSession();
        if (! $session->exists('authinfo'))
            return "This is awkward. We lost your OpenId. Try logging in and registring again";
        
        $this->tUser = new TUsers();
        $this->tUser->openid = $session->authinfo['openid'];
        $this->tUser->email = $session->authinfo['email'];
        $this->tUser->username = $username;
        $this->tUser->dbSave();
        
        $session->User = $this->tUser;
        
        return "ok";
    }
    
    /**
     * Login user
     * @param string $openid The openid of the user
     * @throws Exception when hack attempt is cought
     * @return string Result
     */
    public function auth($openid, $attributes)
    {
        $session = getSession();
        global $queries;
        
        if (isset($session->User))
            return "ok";
        
        $db = getDb();
        $users = $db->query($queries["user"]["login"],
            array("openid" => $openid),
            "TUsers");
        $size = count($users);
        if ($size == 0)
        {                
            if (isset($attributes['contact/email']))
                $email = $attributes['contact/email'];
            else
                die("Provider doesn't wanna give me your e-mail, so I'm not willing to let you in. Sorry bout that. Choose different provider and report pls.");
            
            $session->authinfo = array(
                'openid' => $openid,
                'email' => $email
            );
            
            if (isset($attributes['namePerson/friendly']))
            {
                $username = $attributes['namePerson/friendly'];
                return "register&uname=" . urlencode($username);
            }
            else
            {
                return "register";
            }
        }
        if (count($users) > 1)
        {
            getLogger()->warning("More then one user requested with an openid (maybe SQL INJECTION attempt): " . $openid);
            throw new Exception("Congratulation! You've done something awesome. Contact us plz and tell us how you did it. You will be rewarded.");
        }
        
        $this->tUser = $users[0];
        $session->User = $this->tUser;
        return "ok";
    }
    
    /**
     * OpenId authentication
     * @return string Result
     */
    public function login()
    {
        global $config;
        try {
            
            # Change 'localhost' to your domain name.
            $openid = new LightOpenID(Url::getCurrentDomain());
            $openid->required = array('namePerson/friendly', 'contact/email');
            if (!$openid->mode) {
                if(isset($_GET['openid_identifier'])) {
                    $openid->identity = $_GET['openid_identifier'];
                    redirect($openid->authUrl());
                }
            } elseif ($openid->mode == 'cancel') {
                redirect($config["home"] . 'q/user/finish?cancelled');
            } else {
                if ($openid->validate())
                {
                    //Logged in / Register
                    $attributes = $openid->getAttributes();
                    $status = $this->auth($openid->identity, $attributes);
                    redirect($config["home"] . 'q/user/finish?' . $status);
                }
                else
                {
                    redirect($config["home"] . 'q/user/finish?fail');
                }
            }
        } catch(ErrorException $e) {
            echo $e->getMessage();
        }
    }
    
    /**
     * Render a simple webpage for the pop-up window to return status for the main window and to close it
     */
    public function finish()
    {
        Buffer::start();
?>
        <html>
        <head></head>
            <script type="text/javascript">
                function a()
                {
                    window.opener.openid.status = (window.location+'').split('?')[1];
                    window.close();
                }
            </script>
            <body onload="a()">
            </body>
        </html>
<?php
        return Buffer::flush();
    }
    
    /**
     * Place long-term cookie (NOT YET IMPLEMENTED)
     */
    public function rememberMe()
    {
        //TODO: Implement "remember me" for the User class
    }
}