<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Recaptcha\Fields
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-recaptcha
 */

namespace SilverWare\Recaptcha\Fields;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\FormField;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\View\Requirements;

/**
 * An extension of the form field class for a Google Recaptcha spam prevention field.
 *
 * @package SilverWare\Recaptcha\Fields
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-recaptcha
 */
class RecaptchaField extends FormField
{
    /**
     * An array which defines the default configuration for instances.
     *
     * @var array
     * @config
     */
    private static $default_config = [];
    
    /**
     * Defines the injector dependencies for this object.
     *
     * @var array
     * @config
     */
    private static $dependencies = [
        'client' => '%$RecaptchaHTTPClient'
    ];
    
    /**
     * Defines the public key for the Recaptcha API.
     *
     * @var string
     * @config
     */
    private static $public_api_key = '';
    
    /**
     * Defines the private key for the Recaptcha API.
     *
     * @var string
     * @config
     */
    private static $private_api_key = '';
    
    /**
     * Defines the URL for the Recaptcha client-side script.
     *
     * @var string
     * @config
     */
    private static $recaptcha_script_url = 'https://www.google.com/recaptcha/api.js';
    
    /**
     * Defines the URL for the Recaptcha server-side verification.
     *
     * @var string
     * @config
     */
    private static $recaptcha_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    
    /**
     * Defines the class names to use for alert messages.
     *
     * @var string
     * @config
     */
    private static $alert_class = 'alert alert-warning';
    
    /**
     * Defines the class name to use for the Recaptcha field.
     *
     * @var string
     * @config
     */
    private static $field_class = 'g-recaptcha';
    
    /**
     * Defines the name of the response POST variable.
     *
     * @var string
     * @config
     */
    private static $post_var = 'g-recaptcha-response';
    
    /**
     * An array which holds the configuration for an instance.
     *
     * @var array
     */
    protected $config;
    
    /**
     * Constructs the object upon instantiation.
     *
     * @param string $name Name of field.
     * @param string $title Title of field.
     * @param mixed $value Value of field.
     */
    public function __construct($name, $title = null, $value = null)
    {
        // Construct Parent:
        
        parent::__construct($name, $title, $value);
        
        // Construct Object:
        
        $this->setConfig(self::config()->default_config);
    }
    
    /**
     * Defines either the named config value, or the config array.
     *
     * @param string|array $arg1
     * @param mixed $arg2
     *
     * @return $this
     */
    public function setConfig($arg1, $arg2 = null)
    {
        if (is_array($arg1)) {
            $this->config = $arg1;
        } else {
            $this->config[$arg1] = $arg2;
        }
        
        return $this;
    }
    
    /**
     * Answers either the named config value, or the config array.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getConfig($name = null)
    {
        if (!is_null($name)) {
            return isset($this->config[$name]) ? $this->config[$name] : null;
        }
        
        return $this->config;
    }
    
    /**
     * Answers the field type for the template.
     *
     * @return string
     */
    public function Type()
    {
        return 'recaptcha';
    }
    
    /**
     * Renders the field for the template.
     *
     * @param array $properties
     *
     * @return DBHTMLText
     */
    public function Field($properties = [])
    {
        // Load Script:
        
        if ($this->hasKeys()) {
            Requirements::javascript($this->getScriptURL());
        }
        
        // Render Field:
        
        return parent::Field($properties);
    }
    
    /**
     * Answers an array of HTML attributes for the field.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = [
            'id'    => $this->ID(),
            'class' => $this->getFieldClass()
        ];
        
        $this->extend('updateAttributes', $attributes);
        
        return array_merge(
            $attributes,
            $this->getDataAttributes()
        );
    }
    
    /**
     * Answers an array of data attributes for the field.
     *
     * @return array
     */
    public function getDataAttributes()
    {
        $attributes = [];
        
        foreach ($this->getFieldConfig() as $key => $value) {
            $attributes[sprintf('data-%s', $key)] = $value;
        }
        
        $this->extend('updateDataAttributes', $attributes);
        
        return $attributes;
    }
    
    /**
     * Answers true if the value is valid for the receiver.
     *
     * @param Validator $validator
     *
     * @return boolean
     */
    public function validate($validator)
    {
        // Obtain POST Variable:
        
        if ($postVar = $this->getCurrentRequest()->postVar($this->config()->post_var)) {
            
            // Verify Using HTTP Client:
            
            $response = $this->verify($postVar);
            
            // Detect Empty Response:
            
            if (empty($response)) {
                
                // Report Failure:
                
                $validator->validationError(
                    $this->name,
                    _t(
                        __CLASS__ . '.RESPONSEEMPTY',
                        'Sorry, the recaptcha service did not respond. Please try again later.'
                    ),
                    ValidationResult::TYPE_ERROR
                );
                
                // Answer Failure:
                
                return false;
                
            }
            
            // Detect Response Failure:
            
            if (!$response['success']) {
                
                // Detect Environment:
                
                if (Director::isDev() && isset($response['error-codes'])) {
                    
                    // Report Failure (with debug information):
                    
                    $validator->validationError(
                        $this->name,
                        sprintf(
                            _t(
                                __CLASS__ . '.RESPONSEFAILUREDEBUG',
                                'Sorry, your captcha verification failed. The API reported error code(s): %s'
                            ),
                            implode(', ', $response['error-codes'])
                        ),
                        ValidationResult::TYPE_ERROR
                    );
                    
                } else {
                    
                    // Report Failure:
                    
                    $validator->validationError(
                        $this->name,
                        _t(
                            __CLASS__ . '.RESPONSEFAILURE',
                            'Sorry, your captcha verification failed.'
                        ),
                        ValidationResult::TYPE_ERROR
                    );
                    
                }
                
                // Answer Failure:
                
                return false;
                
            }
            
        } else {
            
            // Report Empty POST Variable:
            
            $validator->validationError(
                $this->name,
                _t(
                    __CLASS__ . '.POSTVAREMPTY',
                    'Please answer the captcha question before submitting the form.'
                ),
                ValidationResult::TYPE_WARNING
            );
            
            // Answer Failure:
            
            return false;
            
        }
        
        // Answer Success:
        
        return true;
    }
    
    /**
     * Answers true if both the public and private API keys are defined.
     *
     * @return boolean
     */
    public function hasKeys()
    {
        return ($this->config()->public_api_key && $this->config()->private_api_key);
    }
    
    /**
     * Answers the class names to use for any alert messages.
     *
     * @return string
     */
    public function getAlertClass()
    {
        return $this->config()->alert_class;
    }
    
    /**
     * Answers the class name to use for the field element.
     *
     * @return string
     */
    public function getFieldClass()
    {
        return $this->config()->field_class;
    }
    
    /**
     * Answers the field config for the receiver.
     *
     * @return array
     */
    protected function getFieldConfig()
    {
        $config = $this->getConfig();
        
        $config['sitekey'] = $this->config()->public_api_key;
        
        return $config;
    }
    
    /**
     * Answers the URL for the Recaptcha client-side script.
     *
     * @return string
     */
    protected function getScriptURL()
    {
        return $this->config()->recaptcha_script_url;
    }
    
    /**
     * Answers the URL for the Recaptcha server-side verification.
     *
     * @return string
     */
    protected function getVerifyURL()
    {
        return $this->config()->recaptcha_verify_url;
    }
    
    /**
     * Answers the current HTTP request object from the controller.
     *
     * @return HTTPRequest
     */
    protected function getCurrentRequest()
    {
        return Controller::curr()->getRequest();
    }
    
    /**
     * Verifies the given POST variable response using the configured HTTP client.
     *
     * @param string $postVar
     *
     * @return array
     */
    protected function verify($postVar)
    {
        $response = $this->client->post(
            $this->getVerifyURL(),
            $this->getVerifyParams($postVar)
        );
        
        return Convert::json2array($response->getBody());
    }
    
    /**
     * Answers an array of parameters for the verification POST request.
     *
     * @param string $postVar
     *
     * @return array
     */
    protected function getVerifyParams($postVar)
    {
        return [
            'secret'   => $this->config()->private_api_key,
            'remoteip' => $this->getCurrentRequest()->getIP(),
            'response' => $postVar
        ];
    }
}
