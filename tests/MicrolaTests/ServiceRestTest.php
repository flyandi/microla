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
use Support\FakeRestCall;

class ServiceRestTest extends PHPUnit_Framework_TestCase {

    /**
     * [testRequestHttpGet description]
     * @return [type] [description]
     */
    public function testRestGet() {

    	$this->assertEquals("Hello World", FakeRestCall::execute("GET", "/hello", false, [
            "Content-Type" => "text/plain"
        ]));
    }

    /**
     * [testRestGetFormatted description]
     * @return [type] [description]
     */
    public function testRestGetFormatted()
    {
        $this->assertEquals("Hello Steve", FakeRestCall::execute("GET", "/helloformatted", [
            "name" => "Steve"
        ], [
            "Content-Type" => "text/plain"
        ]));
    }

    /**
     * [testRestNotImplementedMethod description]
     * @return [type] [description]
     */
    public function testRestNotImplementedMethod() {

        $this->assertEquals(false, FakeRestCall::execute("DELETE", "/hello", false, [
            "Content-Type" => "text/plain"
        ]));
    }

    /**
     * [testRestNotImplementedEndpoint description]
     * @return [type] [description]
     */
    public function testRestNotImplementedEndpoint() {

        $this->assertEquals(false, FakeRestCall::execute("GET", "/some-endpoint", false, [
            "Content-Type" => "text/plain"
        ]));
    }

    /**
     * [testRestInvalid description]
     * @return [type] [description]
     */
    public function testRestInvalid() {

        $this->assertEquals(false, FakeRestCall::execute("UPDATE", "/hello", false, [
            "Content-Type" => "text/plain"
        ]));
    }


    /**
     * [testRestDefaultMethod description]
     * @return [type] [description]
     */
    public function testRestDefaultMethod()
    {
        $this->assertEquals("Hello Steve", FakeRestCall::execute("GET", "/hellodefault", [
            "name" => "Steve"
        ], [
            "Content-Type" => "text/plain"
        ]));
    }
}