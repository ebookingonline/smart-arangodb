<?php
    /*
    | Author : Ata amini
    | Email  : ata.aminie@gmail.com
    | Date   : 2019-03-07
    | TIME   : 8:00 PM
    */

    namespace Smart\Arangodb;

    use Yii;
    use yii\base\BaseObject;
    use ArangoDBClient\Document;
    use ArangoDBClient\Statement;
    use ArangoDBClient\UpdatePolicy;
    use ArangoDBClient\DocumentHandler;
    use ArangoDBClient\CollectionHandler;
    use ArangoDBClient\ConnectionOptions;

    /**
     * Class Connection
     * @package Smart\Arangodb
     */
    class Connection extends BaseObject
    {
        /** @var null Connection */
        private $connection = null;

        /** @var array Connection configuration */
        public $connectionOptions = [
            // server endpoint to connect to
            ConnectionOptions::OPTION_ENDPOINT      => 'tcp://127.0.0.1:9999',

            // authorization type to use (currently supported: 'Basic')
            ConnectionOptions::OPTION_AUTH_TYPE     => 'Basic',

            // user for basic authorization
            ConnectionOptions::OPTION_AUTH_USER     => 'root',

            // password for basic authorization
            ConnectionOptions::OPTION_AUTH_PASSWD   => '',

            // connection persistence on server. can use either 'Close'
            // (one-time connections) or 'Keep-Alive' (re-used connections)
            ConnectionOptions::OPTION_CONNECTION    => 'Close',

            // connect timeout in seconds
            ConnectionOptions::OPTION_TIMEOUT       => 3,

            // whether or not to reconnect when a keep-alive connection has timed out on server
            ConnectionOptions::OPTION_RECONNECT     => true,

            // optionally create new collections when inserting documents
            ConnectionOptions::OPTION_CREATE        => true,

            // optionally create new collections when inserting documents
            ConnectionOptions::OPTION_UPDATE_POLICY => UpdatePolicy::LAST,
        ];

        /** @var null|CollectionHandler $collectionHandler */
        private $collectionHandler = null;

        /** @var null|DocumentHandler $documentHandler */
        private $documentHandler = null;

        /**
         * @inheritdoc
         * @throws \Exception
         */
        public function init()
        {
            parent::init();

            $token = 'Opening ArangoDB connection: ' . $this->connectionOptions[ConnectionOptions::OPTION_ENDPOINT];
            try {
                Yii::info($token, 'Smart\Arangodb\Connection::open');
                Yii::beginProfile($token, 'Smart\Arangodb\Connection::open');
                $this->connection = new \ArangoDBClient\Connection($this->connectionOptions);
                $this->collectionHandler = new CollectionHandler($this->connection);
                $this->documentHandler = new DocumentHandler($this->connection);
                Yii::endProfile($token, 'Smart\Arangodb\Connection::open');
            } catch (\Exception $ex) {
                Yii::endProfile($token, 'Smart\Arangodb\Connection::open');
                throw new \Exception($ex->getMessage(), (int)$ex->getCode(), $ex);
            }
        }

        /**
         * @return null|CollectionHandler
         */
        public function getCollectionHandler()
        {
            return $this->collectionHandler;
        }

        /**
         * @param $collectionId
         * @return \ArangoDBClient\Collection
         * @throws \ArangoDBClient\Exception
         */
        public function getCollection($collectionId)
        {
            return $this->getCollectionHandler()->get($collectionId);
        }

        /**
         * @return null|DocumentHandler
         */
        public function getDocumentHandler()
        {
            return $this->documentHandler;
        }

        /**
         * @param $collectionId
         * @param $documentId
         * @return Document
         * @throws \ArangoDBClient\Exception
         */
        public function getDocument($collectionId, $documentId)
        {
            return $this->getDocumentHandler()->get($collectionId, $documentId);
        }

        /**
         * @param array $options
         * @return Statement
         * @throws \ArangoDBClient\Exception
         */
        public function getStatement($options = [])
        {
            return new Statement($this->connection, $options);
        }
    }
