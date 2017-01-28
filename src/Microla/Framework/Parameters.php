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

class Parameters
{
    /**
     * @var null
     */
    private $data = null;

    /**
     * @var null|object
     */
    private $query = null;

    /**
     * Parameters constructor.
     */
    public function __construct()
    {
        $this->clear();

        $this->query = $this->getQueryParameters();
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return DefaultValue(@$this->data->{$name}, DefaultValue(@$this->query->{$name}, null));
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data->{$name} = $value;
    }

    /**
     * @param $name
     * @param $arguments
     * @return null
     */
    public function __call($name, $arguments)
    {
        if (count($arguments) != 1) {
            return null;
        }

        $this->{$name} = $arguments[0];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->{$name};
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value)
    {
        return $this->{$name} = $value;
    }

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    public function is($name, $value)
    {
        return $this->has($name) && Compare($this->{$name}, $value);
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return $this->hasProperty($name) || $this->hasQueryParameterisset($this->data->{$name}) || isset($this->data);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasProperty($name)
    {
        return isset($this->data->{$name});
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasQueryParameter($name)
    {
        return isset($this->query->{$name});
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->data = (object) [];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return (array) Extend($this->query, $this->data);
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->data);
    }

    /**
     * @return null
     */
    public function toObject()
    {
        return $this->data;
    }

    /**
     * @return object
     */
    public function getQueryParameters()
    {
        return (object) Extend($_GET, $_POST, json_decode(file_get_contents('php://input'), true));
    }
}
