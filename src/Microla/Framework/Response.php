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
	 * [http description]
	 * @param  [type]  $data        [description]
	 * @param  boolean $contentType [description]
	 * @param  boolean $headers     [description]
	 * @param  boolean $http        [description]
	 * @return [type]               [description]
	 */
	public static function http($data, $contentType = false, $headers = false) {

		// figure out what content type
		$contentType = DefaultValue($contentType, GetServerVar("CONTENT_TYPE"));

		// get content
		$content = self::content($data, $contentType);

		// start output buffering
		ob_start();

		// prepare headers
		foreach(Extend($headers, [

			"Content-Length" => strlen($content),

			"Content-Type" => $contentType,

		]) as $name => $value) {
			@header(sprintr("{0}: {1}", $name, $value));
		}

		// write content
		echo $content;
		
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
	public static function content($content, $type) {

		// figure out the protocol first
		switch(true) {

			// Text Only
			case Compare($type, Types::PLAIN):

				$content = self::formatAsString($content);
				break;

			default:

				$content = self::formatAsJson($content);
				break;
		}

		return $content;
	}

	/**
	 * [error description]
	 * @param  [type] $error [description]
	 * @return [type]        [description]
	 */
	public static function error($error) {

		return ["error" => $error];
	}


	/**
	 * [respondAsJson description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public static function formatAsJson($content) {

		// format content
		$content = !is_array($content) && !is_object($content) ? ["content" => $content] : $content;

		// format message
		return json_encode($content);
	}	


	/**
	 * [respondAsString description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public static function formatAsString($content) {

		return $content;
	}
}