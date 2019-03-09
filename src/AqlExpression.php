<?php
    /*
    | Author : Ata amini
    | Email  : ata.aminie@gmail.com
    | Date   : 2019-03-09
    | TIME   : 8:20 PM
    */

    namespace Smart\Arangodb;

    use yii\base\BaseObject;

    /**
     * Class AqlExpression
     * @package Smart\Arangodb
     */
    class AqlExpression extends BaseObject
    {
        /**
         * @var string the AQL expression represented by this object
         */
        public $expression;

        /**
         * Constructor.
         * @param string $expression the AQL expression represented by this object
         * @param array  $config additional configurations for this object
         */
        public function __construct($expression, $config = [])
        {
            $this->expression = $expression;
            parent::__construct($config);
        }

        /**
         * The PHP magic function converting an object into a string.
         * @return string the AQL expression.
         */
        public function __toString()
        {
            return $this->expression;
        }
    }