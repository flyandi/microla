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


class Router {

	/**
	 * [$parent description]
	 * @var null
	 */
	private $parent = null;

	/**
	 * [__construct description]
	 * @param [type] $parent [description]
	 */
	public function __construct($parent) {

		$this->parent = $parent;
	}


	/**
	 * [route description]
	 * @return [type] [description]
	 */
	public function route() {

		// check sapi 
		$sapiSource = php_sapi_name();

		// get request method
		$requestMethod = GetServerVar("REQUEST_METHOD");

		// check if requestMethod is available - we have a REST message
		if($requestMethod) {

			// parse REST
			if(method_exists($this, $requestMethod)) {

				if($result = $this->{$requestMethod}()) {
					return Response::respond($result);
				}

				return Response::error(Response::NOT_IMPLEMENTED);
			}

			return Response::error(Response::NOT_FOUND);
		}
	}

	/**
	 * REST: GET
	 * @return [type] [description]
	 */
	private function get() {
			
		$endpoint = $this->parent->getPool()->getEndpoint(GetDirVar(0));

		return $endpoint ? $endpoint->get() : false;
	}

}