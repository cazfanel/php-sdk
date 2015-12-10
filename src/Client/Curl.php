<?php
/**
 * Client implementation using CURL.
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @author Marcelo Gornstein <marcelog@portatext.com>
 * @copyright 2015 PortaText
 */
namespace PortaText\Client;

use PortaText\Exception\RequestError;

/**
 * All API client implementations should extend this class.
 */
class Curl extends Base
{
    /**
     * Executes the request. Will depend on the client implementation.
     * Returns an array with code, headers, and body.
     *
     * @param PortaText\Command\Descriptor $descriptor Command descriptor.
     *
     * @return array
     * @throws PortaText\Exception\RequestError
     */
    public function execute($descriptor)
    {
        $curl = @curl_init();
        $headers = array();
        foreach ($descriptor->headers as $k => $v) {
            $headers[] = "$k: $v";
        }
        @curl_setopt_array($curl, array(
          CURLOPT_URL => $descriptor->uri,
          CURLOPT_HEADER => true,
          CURLOPT_CUSTOMREQUEST => strtoupper($descriptor->method),
          CURLOPT_USERAGENT => "PortaText PHP SDK",
          CURLOPT_HTTPHEADER => $headers,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYPEER => true,
          //CURLOPT_VERBOSE => true,
          CURLOPT_POSTFIELDS => $descriptor->body
        ));
        $result = curl_exec($curl);
        if ($result === false) {
            $error = curl_error($curl);
            @curl_close($curl);
            throw new RequestError($descriptor, $error);
        }
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        @curl_close($curl);
        list($headers, $body) = $this->parseCurlResult($result);
        return array($code, $headers, $body);
    }

    /**
     * Given a curl result, will return headers and body.
     *
     * @param string $result The CURL result.
     *
     * @return array
     */
    protected function parseCurlResult($result)
    {
        list($preHeaders, $body) = explode("\r\n\r\n", $result, 2);
        $statusAndHeaders = explode("\r\n", $preHeaders, 2);
        $preHeaders = $statusAndHeaders[1];
        $preHeaders = explode("\r\n", $preHeaders);
        $headers = array();
        foreach ($preHeaders as $h) {
            list($k, $v) = explode(": ", $h, 2);
            $headers[strtolower($k)] = $v;
        }
        return array($headers, $body);
    }
}
