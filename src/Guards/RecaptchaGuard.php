<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Recaptcha\Guards
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-recaptcha
 */

namespace SilverWare\Recaptcha\Guards;

use SilverWare\Recaptcha\Fields\RecaptchaField;
use SilverWare\SpamGuard\Interfaces\SpamGuard;

/**
 * A spam guard implementation which uses Google Recaptcha for spam prevention.
 *
 * @package SilverWare\Recaptcha\Guards
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-recaptcha
 */
class RecaptchaGuard implements SpamGuard
{
    /**
     * The default name for the form field.
     *
     * @var string
     */
    protected $name = 'Recaptcha';
    
    /**
     * The default title for the form field.
     *
     * @var string
     */
    protected $title = '';
    
    /**
     * Defines the value of the name attribute.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        
        return $this;
    }
    
    /**
     * Answers the value of the name attribute.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Defines the value of the title attribute.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        
        return $this;
    }
    
    /**
     * Answers the value of the title attribute.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Answers the form field used for implementing the spam guard.
     *
     * @param string $name
     * @param string $title
     * @param mixed $value
     *
     * @return RecaptchaField
     */
    public function getFormField($name = null, $title = null, $value = null)
    {
        return RecaptchaField::create($name, $title, $value);
    }
    
    /**
     * Answers the default name for the form field.
     *
     * @return string
     */
    public function getDefaultName()
    {
        return $this->name;
    }
    
    /**
     * Answers the default title for the form field.
     *
     * @return string
     */
    public function getDefaultTitle()
    {
        return $this->title;
    }
}
