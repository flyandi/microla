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
     * [$data description]
     * @var [type]
     */
    private $data;

    /**
     * [$query description]
     * @var [type]
     */
    private $query;

    /**
     * [__constructor description]
     * @return [type] [description]
     */
    public function __construct()
    {
        $this->clear();

        $this->query = $this->getQueryParameters();
    }

    /**
     * [__get description]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function __get($name)
    {
        return DefaultValue(@$this->data->{$name}, DefaultValue(@$this->query->{$name}, null));
    }

    /**
     * [__set description]
     * @param [type] $name  [description]
     * @param [type] $value [description]
     */
    public function __set($name, $value)
    {
        $this->data->{$name} = $value;
    }

    /**
     * [__call description]
     * @param  [type] $name      [description]
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */
    public function __call($name, $arguments)
    {
        if (count($arguments) != 1) {
            return null;
        }

        $this->{$name} = $arguments[0];
    }

    /**
     * [get description]
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function get($name)
    {
        return $this->{$name};
    }

    /**
     * [set description]
     * @param [type] $name  [description]
     * @param [type] $value [description]
     */
    public function set($name, $value)
    {
        return $this->{$name} = $value;
    }

    /**
     * [is description]
     * @param  [type]  $name  [description]
     * @param  [type]  $value [description]
     * @return boolean        [description]
     */
    public function is($name, $value)
    {
        return $this->has($name) && Compare($this->{$name}, $value);
    }

    /**
     * [has description]
     * @param  [type]  $name [description]
     * @return boolean       [description]
     */
    public function has($name)
    {
        return $this->hasProperty($name) || $this->hasQueryParameterisset($this->data->{$name}) || isset($this->data);
    }

    /**
     * [hasProperty description]
     * @param  [type]  $name [description]
     * @return boolean       [description]
     */
    public function hasProperty($name)
    {
        return isset($this->data->{$name});
    }

    /**
     * [hasQueryParameter description]
     * @param  [type]  $name [description]
     * @return boolean       [description]
     */
    public function hasQueryParameter($name)
    {
        return isset($this->query->{$name});
    }

    /**
     * [clear description]
     * @return [type] [description]
     */
    public function clear()
    {
        $this->data = (object) [];
    }

    /**
     * [toArray description]
     * @return [type] [description]
     */
    public function toArray()
    {
        return (array) Extend($this->query, $this->data);
    }

    /**
     * [toJson description]
     * @return [type] [description]
     */
    public function toJson()
    {
        return json_encode($this->data);
    }

    /**
     * [toObject description]
     * @return [type] [description]
     */
    public function toObject()
    {
        return $this->data;
    }

    /**
     * [getQueryParameters description]
     * @return [type] [description]
     */
    public function getQueryParameters()
    {
        return (object) Extend($_GET, $_POST);
    }
}
