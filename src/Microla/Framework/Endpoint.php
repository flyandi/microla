<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.microla.io>
 */

namespace Microla;

use Closure;
use ReflectionClass;

class Endpoint
{

    /**
     * @const default
     */
    private const DEFAULT = 'default';

    /**
     * [$classInstance description]
     * @var null
     */
    private $classInstance = null;


    /**
     * [$className description]
     * @var null
     */
    private $className = null;

    /**
     * [$endpointId description]
     * @var null
     */
    private $endpointId = null;

    /**
     * [$parameters description]
     * @var null
     */
    private $parameters = null;

    /**
     * [construct description]
     * @return [type] [description]
     */
    public function __construct($endpointId, $className)
    {
        $this->endpointId = $endpointId;

        $this->className = $className;

        $this->classInstance = new $className($this);

        $this->parameters = new Parameters();
    }

    /**
     * [__call description]
     * @param  [type] $name      [description]
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */
    public function __call($name, $arguments)
    {
        foreach ([$name, self::DEFAULT] as $methodName) {
            if (method_exists($this->classInstance, $methodName)) {
                return call_user_func_array([$this->classInstance, $methodName], [$this->parameters, $this]);
            }
        }

        return null;
    }

    /**
     * [getParameters description]
     * @return [type] [description]
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
