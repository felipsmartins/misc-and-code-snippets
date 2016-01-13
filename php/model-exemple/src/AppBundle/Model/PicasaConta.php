<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Mapping\ClassMetadata;
#app
use AppBundle\Model\Db\AbstractModel;
use AppBundle\Model\Db\Resultset;


class PicasaConta extends AbstractModel
{
    /**
     * @var string
     */
    public $conta;


    /** {@inheritdoc} */
    public function getTable()
    {
        return 'picasa_contas';
    }

    /** {@inheritdoc} */
    public function exchangeArray(array $data)
    {
        $this->conta = $data['conta'];
    }

    /** {@inheritdoc} */
    public function all($options=[])
    {
        $sql  = 'SELECT * FROM '. $this->getTable();
        $data =  $this->conn->fetchAll($sql);

        return new Resultset($data, null);
    }

    /**
     * Persiste dados.
     * @see Connection::insert()
     * @return mixed
     */
    public function adicionar()
    {
        $data = ['conta' => $this->conta];
        return $this->conn->insert($this->getTable(), $data);

        return $stmt;
    }

    /**
     * Validaçao de dados antes de persistir na base de dados.
     *
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        # Nome completo ou razão soicial
        $metadata->addPropertyConstraints('conta', [
            new Constraints\NotBlank(),
        ]);
    }

    /**
     * Total de contas do picasa
     *
     * @return bool|string
     * @throws \Doctrine\DBAL\DBALException
     */
    public function total()
    {
        return $this->conn->query('SELECT COUNT(*) FROM ' . $this->getTable())->fetchColumn();
    }

    /**
     * @see Connection::delete()
     * @param array $criteria (key-value pair)
     * @return int
     */
    public function delete(array $criteria)
    {
        return $this->conn->delete($this->getTable(), $criteria);
    }

    /**
     * @param array $criteria
     * @return int
     */
    public function atualizar(array $criteria)
    {
        $data = ['conta' => $this->conta];

        return $this->conn->update($this->getTable(), $data, $criteria);
    }
}