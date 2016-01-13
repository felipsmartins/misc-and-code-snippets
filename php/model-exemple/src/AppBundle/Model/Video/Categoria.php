<?php

namespace AppBundle\Model\Video;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Mapping\ClassMetadata;
#app
use AppBundle\Model\Db\AbstractModel;
use AppBundle\Model\Db\Resultset;


class Categoria extends AbstractModel
{
    /**
     * @var int O identificador do objeto/registro na base de dados
     */
    public $id;

    /**
     * @var string O nome da categoria
     */
    public $nome;

    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        return 'videos_categorias';
    }

    /** {@inheritdoc} */
    public function exchangeArray(array $data)
    {
        $this->id   = $data['id'];
        $this->nome = $data['nome'];
    }

    /**
     * Persiste dados.
     *
     * @return mixed
     */
    public function adicionar()
    {
        $data = ['nome' => $this->nome];
        return $this->conn->insert($this->getTable(), $data);

        return $stmt;
    }

    public function all($options=[])
    {
        $limit = (isset($options['limit']) ?
            $this->formatSqlLimit($options['limit'], $options['offset']) : null);

        $sql  = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->getTable()} {$limit}";
        $data =  $this->conn->fetchAll($sql);
        $rows = $this->conn->query('SELECT FOUND_ROWS() as total')->fetchColumn();

        return new Resultset($data, $rows);
    }

    /**
     * @see Connection::update()
     * @param array $criteria
     * @return int
     */
    public function atualizar(array $criteria = [])
    {
        $data = ['nome' => $this->nome];
        $criteria = $criteria ? $criteria : ['id' => $this->id];

        return $this->conn->update($this->getTable(), $data, $criteria);
    }

    /**
     * Validaçao de dados antes de persistir na base de dados.
     *
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('nome', [
            new Constraints\NotBlank(),
        ]);
    }
}