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



class Request {

	/**
	 * [isRest description]
	 * @return boolean [description]
	 */
	public function isRest() {

		return $this->getRequestMethod() && GetServerVar("REQUEST_URI");
	}

	/**
	 * [isCrud description]
	 * @return boolean [description]
	 */
	public function isCrud() {

		return $this->isRest() && $this->getCrudMethod(GetDirVar(1));
	}


	/**
	 * [isCli description]
	 * @return boolean [description]
	 */
	public function isCli() {

	}

	/**
	 * [getRequestMethod description]
	 * @return [type] [description]
	 */
	public function getRequestMethod() {

		$requestMethod = strtoupper(GetServerVar("REQUEST_METHOD"));

		return in_array($requestMethod, ["GET", "POST", "PUT", "DELETE"]) ? $requestMethod : false;
	}


	/**
	 * [isCrudMethod description]
	 * @param  [type]  $crudMethod [description]
	 * @return boolean             [description]
	 */
	public function isCrudMethod($crudMethod) {

		$crudMethod = strtoupper($crudMethod);

		return in_array($crudMethod, ["CREATE", "READ", "UPDATE", "DELETE"]) ? $crudMethod : false;
	}

}