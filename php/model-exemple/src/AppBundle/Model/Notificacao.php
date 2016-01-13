<?php

namespace AppBundle\Model;

use DateTime;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Mapping\ClassMetadata;
#app
use AppBundle\Model\Db\AbstractModel;
use AppBundle\Model\Db\Resultset;


class Notificacao extends AbstractModel
{
    /**
     * @var int O identificador do objeto/registro na base de dados
     */
    public $id;

    /**
     * @var string
     */
    public $titulo;

    /**
     * @var string Texto da notificação
     */
    public $info;

    /**
     * @var DateTime A data da notificação
     */
    public $criadoEm;

    /**
     * Se nofiticaçõe já foi lida.
     * Os valores possíveis são 1 (ou TRUE) e 0 (FALSE) para "lida" e "não lida", respectivamente.
     * @var bool
     */
    public $lida = false;

    /** {@inheritdoc} */
    public function getTable()
    {
        return 'notificacoes';
    }

    /** {@inheritdoc} */
    public function exchangeArray(array $data)
    {
        $this->id       = $data['id'];
        $this->titulo   = $data['titulo'];
        $this->info     = $data['info'];
        $this->criadoEm = $data['criado_em'];
        $this->lida     = $data['lida'];
    }

    /**
     * Persiste dados.
     *
     * @return mixed
     */
    public function adicionar()
    {
        $data = [];
        $data['titulo']    = $this->titulo;
        $data['info']      = $this->info;
        $data['criado_em'] = (new DateTime())->format('Y-m-d H:i:s');

        return $this->conn->insert($this->getTable(), $data);
    }

    public function all($options=[])
    {
        $limit = (isset($options['limit']) ?
            $this->formatSqlLimit($options['limit'], $options['offset']) : null);

        $sql  = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->getTable()} ORDER BY criado_em DESC {$limit}";
        $data =  $this->conn->fetchAll($sql);
        $rows = $this->conn->query('SELECT FOUND_ROWS() as total')->fetchColumn();

        return new Resultset($data, $rows);
    }

    /**
     * Marca a mensagem como lida
     * @see Connection::update()
     * @return int
     */
    public function marcarComoLida()
    {
        return $this->conn->update($this->getTable(), ['lida' => true], ['id' => $this->id]);
    }

    /**
     * @param string|array $camposRetorno
     * @param int $max Número máximo de registro a retornars
     * @return array
     */
    public function  notificacoesMaisRecentesNaoLidas($camposRetorno='*', $max=5)
    {
        if ($camposRetorno && is_array($camposRetorno)) {
            $camposRetorno = join(',', $camposRetorno);
        }

        $sql = sprintf('SELECT %s FROM %s WHERE lida = 0 ORDER BY criado_em DESC LIMIT %d',
            $camposRetorno, $this->getTable(), $max);

        return $this->conn->fetchAll($sql);


    }

    /**
     * Validaçao de dados antes de persistir na base de dados.
     *
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('titulo', [
            new Constraints\NotBlank(),
        ])
            ->addPropertyConstraints('info', [
                new Constraints\NotBlank(),
            ])
            ->addPropertyConstraints('criadoEm', [
                new Constraints\NotBlank(),
            ])
        ;
    }
}