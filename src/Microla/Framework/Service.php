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


class Service {

	/**
	 * @var string
	 */
	const SERVICE_NAMESPACE = "Service\\";


	/**
	 * @var string
	 */
	const SERVICE = "Service\Service";


	/**
	 * [$pool description]
	 * @var null
	 */
	private $pool = null;


	/**
	 * [$router description]
	 * @var null
	 */
	private $router = null;


	/**
	 * [construct description]
	 * @return [type] [description]
	 */
	public function __construct() {

		$this->pool = new Pool();

		$this->router = new Router($this);
	}


	/**
	 * [available description]
	 * @return [type] [description]
	 */
	public function available() {
		return (boolean) class_exists(self::SERVICE);
	}


	/**
	 * [getName description]
	 * @return [type] [description]
	 */
	public function getName() {

		$this->getServiceParameter("name");
	}


	/**
	 * [getPool description]
	 * @return [type] [description]
	 */
	public function getPool() {

		return $this->pool;
	}


	/**
	 * [__process description]
	 * @return [type] [description]
	 */
	public function getRouter() {

		return $this->router;
	}

}