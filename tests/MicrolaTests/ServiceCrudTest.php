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
 * <http://www.doctrine-project.org>.
 */

namespace MicrolaTests;

use PHPUnit_Framework_TestCase;
use Microla\Service as Service;

class ServiceCrudTest extends PHPUnit_Framework_TestCase {

    /**
     * [setUp description]
     */
    public function setUp() {

        $this->service = new Service();
    }

    /**
     * [testRequestHttpGet description]
     * @return [type] [description]
     */
    public function xtestRequestHttpGet() {

    	$this->assertEquals("Hello World", $this->fakeCrudRequest("get", "/hello", false, [
            "Content-Type" => "text/plain"
        ]));
    }

    /**
     * [fakeRestRequest description]
     * @param  [type]  $method [description]
     * @param  boolean $path   [description]
     * @param  boolean $data   [description]
     * @return [type]          [description]
     */
    private function fakeCrudRequest($method, $path = false, $data = false, $headers = false) {

       	// fake server request
    	$_SERVER["REQUEST_METHOD"] = strtoupper($method);

    	// fake server request
    	$_SERVER["REQUEST_URI"] = $path;

        // set headers
        foreach(Extend($headers) as $name => $value) {
            $_SERVER[strtoupper(str_replace("-", "_", $name))] = $value;
        }

    	// obstart
    	ob_start();

    	$this->service->getRouter()->route();

   		$result = ob_get_contents();

    	ob_end_clean();

   		return $result;
    }	

}