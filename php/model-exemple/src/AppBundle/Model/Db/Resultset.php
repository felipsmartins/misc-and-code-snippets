<?php

namespace AppBundle\Model\Db;

class Resultset implements ResultsetInterface
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @var array|countable
     */
    protected $dataset;

    public function __construct($dataset, $count)
    {
        $this->dataset = $dataset;
        $this->count   = $count;
    }

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataset()
    {
        return $this->dataset;
    }
}
