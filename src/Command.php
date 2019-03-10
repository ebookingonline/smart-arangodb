<?php
    /*
    | Author : Ata amini
    | Email  : ata.aminie@gmail.com
    | Date   : 2019-03-10
    | TIME   : 7:23 PM
    */

    namespace Smart\Arangodb;

    use Smart\Engine\Configurable;

    /**
     * Class Command
     * @package Smart\Arangodb
     */
    class Command extends Configurable
    {
        /** @var array Multiple rows document */
        private $documents = [];

        /** @var array On duplicate perform action */
        private $onDuplicate;

        /**
         * @inheritdoc
         * Command constructor.
         * @param array $config
         */
        public function __construct(array $config = [])
        {
            parent::__construct($config);
            $this->reset();
        }

        /**
         * Add document.
         * @param array $document
         * @return $this
         */
        public function addDocument(array $document)
        {
            $this->documents[] = $document;
            return $this;
        }

        /**
         * Put multiple documents.
         * @param array $documents
         * @return $this
         */
        public function addDocuments(array $documents)
        {
            foreach ($documents as $document)
                $this->addDocument($document);

            return $this;
        }

        /**
         * Return documents.
         * @return array
         */
        public function documents()
        {
            return $this->documents;
        }

        /**
         * Return documents count.
         * @return int
         */
        public function documentsCount()
        {
            return count($this->documents());
        }

        /**
         * Return arangodb component.
         * @return object|null|\Smart\Arangodb\Connection
         * @throws \yii\base\InvalidConfigException
         */
        public function arangodb()
        {
            return \Yii::$app->get('arangodb');
        }

        /**
         * Insert document(s).
         * @param       $collectionName
         * @param array $options
         * @return mixed
         * @throws \yii\base\InvalidConfigException
         */
        public function insert($collectionName, array $options = [])
        {
            return $this->arangodb()->getDocumentHandler()->insert($collectionName, $this->documents(), $options);
        }

        /**
         * Update/replace on duplicate.
         * @param array $fields
         * @param bool  $update
         * @return $this
         */
        public function onDuplicate(array $fields, bool $update = true)
        {
            $this->onDuplicate = [
                'operator' => $update ? 'update' : 'replace',
                'fields'   => $fields
            ];

            return $this;
        }

        /**
         * Reset documents.
         * @return void
         */
        public function reset()
        {
            $this->documents = [];
        }
    }