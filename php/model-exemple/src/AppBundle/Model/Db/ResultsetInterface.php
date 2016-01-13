<?php

namespace AppBundle\Model\Db;

interface ResultsetInterface
{
    /**
     * número de elementos/linhas de uma matriz ou consulta SQL - Ti-
     * picamente um select count(*).
     *
     * number of elements/array count, like 'select count(*)'.
     *
     * @result int
     */
    public function getCount();

    /**
     * A data array, resultset.
     *
     * @result array
     */
    public function getDataset();
}
