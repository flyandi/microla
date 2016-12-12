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

		// parse request
		$request = new Request();

		// check request type
		switch(true) {

			case $request->isRest(): 

				// get endpoint
				if($endpoint = $this->parent->getPool()->getEndpoint(GetDirVar(0))) {

					if($result = $endpoint->{$request->getRequestMethod()}()) {

						return Response::http($result);
					} 

					return Response::http(Response::error(Response::NOT_IMPLEMENTED));
				}

				return Response::http(Response::error(Response::NOT_FOUND));

				break;

			case $request->isCrud():

				break;

			case $request->isCli():

				break;
		}
	}

}