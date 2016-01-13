<?php

namespace AppBundle\Model\Db;

interface ModelInterface
{
    /**
     * Description.
     *
     * @param array $options SQL SELECT options.
     *                       You must provide $options['offset'] and $options['limit']
     *
     * @return type
     */
    public function all($options);

    /**
     * This method make a copy of data passaed through $data array  and map it
     * to model. In other words it map each array key as a model attribute.
     * That is roughly like Zend Framework 2 does
     * @link http://framework.zend.com/manual/current/en/user-guide/database-and-models.html
     * @param array $data key-value pair
     */
    public function exchangeArray(array $data);

}
