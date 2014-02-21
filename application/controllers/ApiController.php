<?php

class ApiController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $request = $this->getRequest();

        $query = $this->getParam('query');

        if (!empty($query)) 
        {

            $tweets = $this->twitterSearch($query);
            $fbstatuses = $this->facebookSearch($query);

            $statuses = array_merge($tweets, $fbstatuses);
            usort($statuses, array("ApiController", "sortByTime"));

/*            
            Ahora envio jSon
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(TRUE);
*/

            if (!empty($statuses))
            {
                $this->_helper->json->sendJson($statuses);
                // echo json_encode($statuses);
            } else {
                throw new Zend_Controller_Action_Exception('No hay estados con esa busqueda', 204);
            }

        } else {
            // Redireccionar al index
            return $this->redirect()->toRoute('/');
        }

    }

    public function twitterSearch($query)
    {

        $config = new Zend_Config_Ini(APPLICATION_PATH
                . '/../../TagTrackAPIs.ini', 'socialapis');

        $twitter = new Zend_Service_Twitter(array(
            'accessToken' => array( 
                'token' => $config->twitter->accessToken->token,
                'secret' => $config->twitter->accessToken->secret,
            ),
            'oauthOptions' => array(
                'consumerKey' => $config->twitter->oauthOptions->consumerKey,
                'consumerSecret' => $config->twitter->oauthOptions->consumerSecret,
            )
        ));

        $twitterAccount = $twitter->account->verifyCredentials();

        $search = $twitter->searchTweets($query)->statuses;

        $statuses = array();

        foreach ($search as $tweet) {

            $status = new Application_Model_Status();
            $user = new Application_Model_User();

            $user->id = $tweet->user->id;
            $user->name = $tweet->user->name;
            $user->username = $tweet->user->screen_name;
            $user->picture = str_replace("_normal.", ".", $tweet->user->profile_image_url);

            $time = strtotime($tweet->created_at);

            $status->id = $tweet->id_str;
            $status->message = $tweet->text;
            $status->date = $time;
            $status->user = $user;
            $status->source = 'Twitter';

            $statuses[] = $status;
        }

        return $statuses;

    }

    public function facebookSearch($query) {

        require_once('facebook.php');

        $config = new Zend_Config_Ini(APPLICATION_PATH
                . '/../../TagTrackAPIs.ini', 'socialapis');

        $facebook = new Facebook(array(
                'appId' => $config->facebook->appId,
                'secret' => $config->facebook->secret,
                'cookie' => $config->facebook->cookie
        ));

        $query = str_replace(" ", "+", $query);

        // Replace Hashtag (#) at begining of query
        $hashtagReplace = "";

        if (substr($query, 0, 1) === '%23')
        {
            $hashtagReplace = "%23";
            $query = substr($query, 1);
        }

        $search = $facebook->api('/search?access_token=' . $config->facebook->appId . '|' . $config->facebook->secret . '&q=' . $hashtagReplace . $query . '&type=post&limit=100');

        $facebookStatuses = $object = json_decode(json_encode($search['data']), FALSE);

        $statuses = array();

        foreach ($facebookStatuses as $facebookStatus) {
        
            // Just to make sure that is a true profile and not a page

            if (!empty($facebookStatus->message) 
                && $facebookStatus->type === "status"
                && !isset($facebookStatus->from->category)
            ) 
            {

                $status = new Application_Model_Status();
                $user = new Application_Model_User();

                $user->id = $facebookStatus->from->id;
                $user->name = $facebookStatus->from->name;
                $user->picture = "http://graph.facebook.com/" . $facebookStatus->from->id . "/picture?type=large";
                $user->username = json_decode(file_get_contents('http://graph.facebook.com/' . $facebookStatus->from->id))->username;

                $time = strtotime($facebookStatus->created_time);

                $status->id = $facebookStatus->id;
                $status->message = $facebookStatus->message;
                $status->date = $time;
                $status->user = $user;
                $status->source = 'Facebook';

                $statuses[] = $status;
            }

        }

        return $statuses;

    }

    public static function sortByTime($a, $b) {
        if ($a->date > $b->date) {
            return -1;
        }
        return ($a->date === $b->date) ? 0 : 1;
    }

}

