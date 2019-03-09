<?php
    /*
    | Author : Ata amini
    | Email  : ata.aminie@gmail.com
    | Date   : 2019-03-09
    | TIME   : 8:20 PM
    */

    namespace Smart\Arangodb;

    use yii\base\Arrayable;

    /**
     * Class Serializer
     * @package Smart\Arangodb
     */
    class Serializer
    {
        /**
         * Encode.
         * @param     $value
         * @param int $options
         * @return false|string
         */
        public static function encode($value, $options = 0)
        {
            $expressions = [];
            $value = static::processData($value, $expressions, uniqid());
            $json = json_encode($value, $options);

            return empty($expressions) ? $json : strtr($json, $expressions);
        }

        /**
         * Process data.
         * @param $data
         * @param $expressions
         * @param $expPrefix
         * @return array|mixed|\stdClass|string
         */
        protected static function processData($data, &$expressions, $expPrefix)
        {
            if (is_object($data)) {
                if ($data instanceof AqlExpression) {
                    $token = "!{[$expPrefix=" . count($expressions) . ']}!';
                    $expressions['"' . $token . '"'] = $data->expression;

                    return $token;
                } elseif ($data instanceof \JsonSerializable) {
                    $data = $data->jsonSerialize();
                } elseif ($data instanceof Arrayable) {
                    $data = $data->toArray();
                } else {
                    $result = [];
                    foreach ($data as $name => $value) {
                        $result[$name] = $value;
                    }
                    $data = $result;
                }

                if ($data === []) {
                    return new \stdClass();
                }
            }

            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    if (is_array($value) || is_object($value)) {
                        $data[$key] = static::processData($value, $expressions, $expPrefix);
                    }
                }
            }

            return $data;
        }
    }