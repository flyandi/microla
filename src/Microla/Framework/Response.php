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



class Response {

	/**
	 * @var string
	 */
	const NOT_FOUND = 404;

	/**
	 * @var string
	 */
	const NOT_IMPLEMENTED = 501;


	/**
	 * [respond description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public static function respond($data, $contentType = false, $headers = false, $http = false) {

		$contentType = DefaultValue($contentType, GetServerVar("CONTENT_TYPE"));

		// figure out the protocol first
		switch(true) {

			// Text Only
			case Compare($contentType, "text/plain"):

				self::respondAsString($data, $headers);
				break;

			case Compare($contentType, "text/html"):

				self::respondAsHtml($data, $headers);
				break;

			default:

				self::respondAsJson($data, $headers);
				break;
		}
	}

	/**
	 * [error description]
	 * @param  [type] $error [description]
	 * @return [type]        [description]
	 */
	public static function error($error) {

		return self::respond([
			"error" => $error
		], Types::JSON, false, $error);
	}


	/**
	 * [respondAsJson description]
	 * @param  [type]  $data   [description]
	 * @param  boolean $header [description]
	 * @return [type]          [description]
	 */
	public static function respondAsJson($data, $headers = false) {

		// format data
		$data = !is_array($data) && !is_object($data) ? ["data" => $data] : $data;

		// format message
		$content = json_encode($data);

		// output
		self::output($content, Extend($headers, self::contentType(Types::JSON)));
	}	


	/**
	 * [output description]
	 * @param  [type] $content [description]
	 * @param  [type] $headers [description]
	 * @return [type]          [description]
	 */
	public static function output($content, $headers) {

		// start output buffering
		ob_start();

		// prepare headers
		foreach(Extend($headers, [

			"Content-Length" => strlen($content)

		]) as $name => $value) {
			@header(sprintr("{0}: {1}", $name, $value));
		}

		// write content
		echo $content;
		
		// end and flush
		ob_end_flush();
	}


	/**
	 * [contentType description]
	 * @param  [type] $contentType [description]
	 * @return [type]              [description]
	 */
	public static function contentType($contentType) {

		return ["Content-Type" => $contentType];
	}

}