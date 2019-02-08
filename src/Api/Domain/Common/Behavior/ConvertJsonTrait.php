<?php

namespace Mottor\Api\Domain\Common\Behavior;

use InvalidArgumentException;

trait ConvertJsonTrait
{
    /**
     * @param string $json
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function decodeJson($json) {
        json_decode(null);

        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $message = sprintf(
                'Unable to decode data from JSON in %s: %s',
                __CLASS__,
                json_last_error_msg()
            );

            throw new InvalidArgumentException($message);
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return string
     * @throws InvalidArgumentException
     */
    protected function encodeJson(array $data) {
        json_encode(null);

        $json = json_encode($data);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $message = sprintf(
                'Unable to encode data to JSON in %s: %s',
                __CLASS__,
                json_last_error_msg()
            );

            throw new InvalidArgumentException($message);
        }

        return $json;
    }
}