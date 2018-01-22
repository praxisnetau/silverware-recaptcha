<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Recaptcha\Clients
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-recaptcha
 */

namespace SilverWare\Recaptcha\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use SilverStripe\Control\HTTPResponse;
use SilverWare\Recaptcha\Interfaces\RecaptchaClient;

/**
 * A HTTP client used to validate a form submission via the Recaptcha API.
 *
 * @package SilverWare\Recaptcha\Clients
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-recaptcha
 */
class RecaptchaFieldClient implements RecaptchaClient
{
    /**
     * Issues a POST request to the given Recaptcha server-side verification URL.
     *
     * @param string $url
     * @param array $params
     *
     * @return HTTPResponse
     */
    public function post($url, $params)
    {
        // Create Guzzle Client:
        
        $client = new Client();
        
        // Initialise:
        
        $body = null;
        
        // Attempt Request:
        
        try {
            
            // Obtain Response:
            
            $response = $client->post($url, ['form_params' => $params]);
            
            // Obtain Response Body:
            
            $body = (string) $response->getBody();
            
        } catch (RequestException $e) {
            
            // Obtain Error Response:
            
            if ($e->hasResponse()) {
                
                // Build Error Body:
                
                $body = json_encode([
                    'success' => false,
                    'error-codes' => [
                        $e->getResponse()->getStatusCode()
                    ]
                ]);
                
            }
            
        }
        
        // Answer JSON Response:
        
        return HTTPResponse::create($body)->addHeader(
            'Content-Type',
            'application/json'
        );
    }
}
