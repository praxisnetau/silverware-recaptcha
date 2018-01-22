<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Recaptcha\Interfaces
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-recaptcha
 */

namespace SilverWare\Recaptcha\Interfaces;

use SilverStripe\Control\HTTPResponse;

/**
 * An interface for Recaptcha HTTP client implementations.
 *
 * @package SilverWare\Recaptcha\Interfaces
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-recaptcha
 */
interface RecaptchaClient
{
    /**
     * Issues a POST request to the given Recaptcha server-side verification URL.
     *
     * @param string $url
     * @param array $params
     *
     * @return HTTPResponse
     */
    public function post($url, $params);
}
