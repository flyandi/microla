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
	 * @var integer
	 */
	const OK = 200;

	/**
	 * @var integer
	 */
	const NOT_FOUND = 404;

	/**
	 * @var integer
	 */
	const NOT_IMPLEMENTED = 501;

	/**
	 * @var integer
	 */
	const NOT_SUPPORTED = 600;

	/**
	 * @var string
	 */
	const RESPONSE_STATUS = "responseStatus"; 

	/**
	 * @var string
	 */
	const RESPONSE_HEADERS = "responseHeaders";

	/**
	 * @var string
	 */
	const RESPONSE_BODY = "responseBody";

	/**
	 * @var string
	 */
	const RESPONSE_ERROR = "error";

    /**
     * @param $endpoint
     * @param $data
     * @param bool $contentType
     * @param bool $headers
     * @return object
     */
	public static function http($endpoint, $data, $contentType = false, $headers = false)
	{
		// transform data
		$original = (object) $data;

		// figure out what content type
		$contentType = DefaultValue($contentType, GetServerVar("CONTENT_TYPE"));

		// get content
		$content = self::content($endpoint, $data, $contentType);

		// start output buffering
		ob_start();

		// http code
		http_response_code(DefaultValue(@$content->{self::RESPONSE_STATUS}, self::NOT_SUPPORTED));

		// prepare headers
		foreach(Extend($headers, @$content->{self::RESPONSE_HEADERS}, [

			"Content-Length" => strlen($content->{self::RESPONSE_BODY}),

			"Content-Type" => $contentType,

		]) as $name => $value)
		{
			@header(sprintr("{0}: {1}", $name, $value));
		}

		// write content
		echo $content->{self::RESPONSE_BODY};
		
		// end and flush
		ob_end_flush();

		return $content;
	}


	/**
	 * [content description]
	 * @param  [type] $data [description]
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public static function content($endpoint, $content, $type)
	{
		// prepare
		$parameters = $endpoint ? $endpoint->getParameters()->toArray() : false;

		// initialize
		$body = false;

		$headers = false;

		// set status
		$status = !is_callable($content) && is_object($content) && isset($content->{self::RESPONSE_ERROR}) ? $content->{self::RESPONSE_ERROR} : false;

		// figure out the protocol first
		switch(true) {

			// Custom
			case is_callable($content) || (is_array($content) && is_callable($content[0])):

				if(!is_array($content)) $content = [$content];

				$body = is_callable($content[0]) ? $content[0]($type) : false;

				$headers = isset($content[1]) && is_callable($content[1]) ? $content[1]() : false;

				if(isset($content[2]) && is_callable($content[2])) {
					$status = $content[2]();
				}

				break;

			// Text Only
			case Compare($type, Types::PLAIN):

				$body = self::formatAsString($content, $parameters);

				break;

			default:

				$body = self::formatAsJson($content, $parameters);

				break;
		}

		// return
		return (object) [

			self::RESPONSE_BODY => $body,

			self::RESPONSE_HEADERS => $headers,

			self::RESPONSE_STATUS => $status === false ? (!empty($body) ? self::OK : self::NOT_SUPPORTED) : $status
		];
	}

	/**
	 * [error description]
	 * @param  [type] $error [description]
	 * @return [type]        [description]
	 */
	public static function error($error)
	{
		return (object) [self::RESPONSE_ERROR => $error];
	}


	/**
	 * [respondAsJson description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public static function formatAsJson($content, $parameters)
	{
		// format content
		$content = !is_array($content) && !is_object($content) ? ["content" => self::formatAsString($content, $parameters)] : $content;

		// format message
		return json_encode($content);
	}	

	/**
	 * [formatAsString description]
	 * @param  [type]  $content    [description]
	 * @param  boolean $parameters [description]
	 * @return [type]              [description]
	 */
	public static function formatAsString($content, $parameters = false)
	{	
		if(!is_string($content)) return false;

		return $parameters ? sprintr($content, $parameters) : $content;
	}
}