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

class Pool
{
    /**
     * @var string
     */
    const SERVICE = "Service";

    /**
     * @var array
     */
    private $endpoints = [];

    /**
     * Pool constructor.
     */
    public function __construct()
    {
        $this->discover();
    }

    /**
     * @return void
     */
    public function discover()
    {

        // Pool
        $this->clear();

        // get files in path
        $files = new \RegexIterator(
            // iterator
            new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('.')),
            // pattern
            '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH
        );
            
        // register
        foreach ($files as $file) {
            if (isset($file[0])) {

                // get classId
                $classId = pathinfo(@$file[0], PATHINFO_FILENAME);

                // get classInstance
                $classInstanceName = sprintr("{0}{1}", Service::SERVICE_NAMESPACE, $classId);

                // check class
                if (class_exists($classInstanceName)) {

                    // register endpoint
                    $this->__registerEndpoint($classId, $classInstanceName);
                }
            }
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->endpoints);
    }


    /**
     * @return void
     */
    public function clear()
    {
        $this->endpoints = [];
    }


    /**
     * @param $request
     * @return bool|mixed
     */
    public function getEndpoint($request)
    {
        // prepare
        $endpointId = $this->getEndpointId($request);

        // check if endpoint is registered
        if (!$this->hasEndpoint($endpointId)) {
            return false;
        }

        // execute endpoint
        return $this->endpoints[$endpointId];
    }


    /**
     * @param $name
     * @return string
     */
    public function getEndpointId($name)
    {
        return strtolower(trim($name));
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasEndpoint($name)
    {
        return isset($this->endpoints[$this->getEndpointId($name)]);
    }

    /**
     * @param $classId
     * @param $classInstanceName
     * @return bool
     */
    private function __registerEndpoint($classId, $classInstanceName)
    {

        // prepare
        $endpointId = $this->getEndpointId($classId);

        // sanity check
        if (isset($this->endpoints[$endpointId])) {
            return false;
        }

        // register endpoint
        $this->endpoints[$endpointId] = new Endpoint($endpointId, $classInstanceName);

        // return
        return true;
    }
}
