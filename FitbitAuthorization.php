<?php

    require __DIR__ . '/vendor/autoload.php';    
    use djchen\OAuth2\Client\Provider\Fitbit;


    $provider = new Fitbit([
    'clientId'          => '227WP9',
    'clientSecret'      => '0f947f3eed699edb1fa68a2c6d45a036',
    'redirectUri'       => 'https://laurawarr.ca/motif/home.php'
    ]);

    if(!isset($_SESSION['accessToken'])){
        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {
    
            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl();
    
            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();
    
            // Redirect the user to the authorization URL.
            header('Location: ' . $authorizationUrl);
            exit;
    
        // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
    
        } else {
    
            try {
    
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);
    
                // We have an access token, which we may use in authenticated
                // requests against the service provider's API.

                // $accessToken->getToken() . "\n";
                // $accessToken->getRefreshToken() . "\n";
                // $accessToken->getExpires() . "\n";
                // ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";
                $_SESSION['accessToken'] = serialize($accessToken);
                getData($accessToken, $provider);
            
    
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    
                // Failed to get the access token or user details.
                exit($e->getMessage());
    
            }; //end catch
    
        }; //end !isset GET code
    } else {
         $accessToken = unserialize($_SESSION['accessToken']);

         // if session has expired, refresh token before using
         if ($accessToken -> hasExpired()){
            $accessToken = $accessToken -> getRefreshToken();
         }
         getData($accessToken, $provider);

    }; //end !isset SESSION accessToken


    function getData($token, $provider){
        global $user_data;

        // pulls all three types of active minutes of user goal is set to minutes and sums the three
        // otherwise just pulls specified goal - resulting array 'timeseries'
        if ($user_data['goal'] == 'minutes'){
            $request = $provider->getAuthenticatedRequest(
                Fitbit::METHOD_GET,
                Fitbit::BASE_FITBIT_API_URL . '/1/user/-/activities/minutesLightlyActive/date/today/1w.json',
                $token,
                ['headers' => [Fitbit::HEADER_ACCEPT_LANG => 'en_US'], [Fitbit::HEADER_ACCEPT_LOCALE => 'en_US']]
            );
            $timeseries1 = $provider->getResponse($request);
            $timeseries1 = $timeseries1['activities-minutesLightlyActive'];

            $request = $provider->getAuthenticatedRequest(
                Fitbit::METHOD_GET,
                Fitbit::BASE_FITBIT_API_URL . '/1/user/-/activities/minutesFairlyActive/date/today/1w.json',
                $token,
                ['headers' => [Fitbit::HEADER_ACCEPT_LANG => 'en_US'], [Fitbit::HEADER_ACCEPT_LOCALE => 'en_US']]
            );
            $timeseries2 = $provider->getResponse($request);
            $timeseries2 = $timeseries2['activities-minutesFairlyActive'];

            $request = $provider->getAuthenticatedRequest(
                Fitbit::METHOD_GET,
                Fitbit::BASE_FITBIT_API_URL . '/1/user/-/activities/minutesVeryActive/date/today/1w.json',
                $token,
                ['headers' => [Fitbit::HEADER_ACCEPT_LANG => 'en_US'], [Fitbit::HEADER_ACCEPT_LOCALE => 'en_US']]
            );
            $timeseries3 = $provider->getResponse($request);
            $timeseries3 = $timeseries3['activities-minutesVeryActive'];

            global $timeseries;

            for($i = 0; $i < count($timeseries1); $i++){
                $timeseries[$i] = $timeseries1[$i] + $timeseries2[$i] + $timeseries3[$i];
            }; //end for loop

        } else {
            $request = $provider->getAuthenticatedRequest(
                Fitbit::METHOD_GET,
                Fitbit::BASE_FITBIT_API_URL . '/1/user/-/activities/'.$user_data['goal'].'/date/today/1w.json',
                $token,
                ['headers' => [Fitbit::HEADER_ACCEPT_LANG => 'en_US'], [Fitbit::HEADER_ACCEPT_LOCALE => 'en_US']]
            );
            // Make the authenticated API request and get the response.
            global $timeseries; 
            $result = $provider->getResponse($request);
            $str = 'activities-'.$user_data['goal'];
            $timeseries = $result[$str];
        }; //end if goal == minutes
        

        $request = $provider->getAuthenticatedRequest(
            Fitbit::METHOD_GET,
            Fitbit::BASE_FITBIT_API_URL . '/1/user/-/activities/date/today.json',
            $token,
            ['headers' => [Fitbit::HEADER_ACCEPT_LANG => 'en_US'], [Fitbit::HEADER_ACCEPT_LOCALE => 'en_US']]
        );
        // Make the authenticated API request and get the response.
        global $dailyData; 
        $dailyData = $provider->getResponse($request);
        // var_dump($profileData['user']['distanceUnit']);

    }; //end getData
?>