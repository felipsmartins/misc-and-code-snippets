<?php

namespace AppBundle\Model\Video;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Mapping\ClassMetadata;
#app
use AppBundle\Model\Db\AbstractModel;
use AppBundle\Model\Db\Resultset;
use AppBundle\Service\ShortCode;
use AppBundle\Model\Video\Categoria;


class Video extends AbstractModel
{
    /**
     * @var int O identificador do objeto/registro na base de dados
     */
    public $id;

    /**
     * @var string Uma string única de 7 caracteres
     */
    public $shortcode;

    /**
     * @var string Conta de usuário do Picasa. Geralmente é uam conta do Google.
     */
    public $conta;

    /**
     * @var number ID do item na galeria do PicasaWeb
     */
    public $picasaVideoId;

    /**
     * @var string
     */
    public $titulo;

    /**
     * @var int ID da categoria
     */
    public $categoria;

    /**
     * @var string
     */
    public $legenda;

    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        return 'videos';
    }

    /** {@inheritdoc} */
    public function exchangeArray(array $data)
    {
        $this->id = $data['id'];
        $this->shortcode = $data['shortcode'];
        $this->categoria = $data['categoria_id'];
        $this->titulo = $data['titulo'];
        $this->legenda = $data['legenda'];
        $this->picasaVideoId = $data['picasa_video_id'];
        $this->conta = $data['picasa_conta'];
    }

    /**
     * @param array $options Opções para consulta, exemplo:
     *  $options['limit']  SQL LIMIT
     *  $options['offset'] SQL OFFSET
     * @return Resultset
     */
    public function all($options=[])
    {
        $limit = (isset($options['limit']) ?
            $this->formatSqlLimit($options['limit'], $options['offset']) : null);

        $sql = 'SELECT SQL_CALC_FOUND_ROWS v.*, c.id AS categoria_id, c.nome AS categoria FROM %s AS v
                    INNER JOIN %s AS c ON c.id = v.categoria_id %s';
        # format...
        $sql = sprintf($sql, $this->getTable(), (new Categoria())->getTable(), $limit);
//        dump($sql); die;
        $data =  $this->conn->fetchAll($sql);
        $rows = $this->conn->query('SELECT FOUND_ROWS() as total')->fetchColumn();

        return new Resultset($data, $rows);
    }

    /**
     * Persiste dados.
     *
     * @return mixed
     */
    public function adicionar()
    {
        $shortCodeGen = new ShortCode();
        $this->shortcode = $shortCodeGen->gerar(7);
        /* NOTA:
        Desde que o short code deve ser único, devemos checar antes.
        É claro, id na tabela é uma PK, se o valor de "$code" já existir, o SGBD lançará um erro.
        Mas não queremos manipular erro no cadastro,então faremos a checagem antes de inserir por ser
        mais simples e transparente para o usuário. */
        while($this->getOne($this->shortcode, 'shortcode')) {
            $this->shortcode = $shortCodeGen->gerar(7); # Novamente...
        }

        $data = [
            'shortcode'       => $this->shortcode,
            'categoria_id'    => $this->categoria,
            'titulo'          => $this->titulo,
            'legenda'         => $this->legenda,
            'picasa_video_id' => $this->picasaVideoId,
            'picasa_conta'    => $this->conta,
        ];

        return $this->conn->insert($this->getTable(), $data);
    }

    /**
     * @see Connection::update()
     * @param array $criteria
     * @return int
     */
    public function atualizar(array $criteria = [])
    {
        $data = [
            'categoria_id'    => $this->categoria,
            'titulo'          => $this->titulo,
            'legenda'         => $this->legenda,
            'picasa_video_id' => $this->picasaVideoId,
            'picasa_conta'    => $this->conta,
        ];
        $criteria = $criteria ? $criteria : ['id' => $this->id];

        return $this->conn->update($this->getTable(), $data, $criteria);
    }

    /**
     * Total de vídeos
     *
     * @return bool|string
     * @throws \Doctrine\DBAL\DBALException
     */
    public function total()
    {
        return $this->conn->query('SELECT COUNT(*) FROM ' . $this->getTable())->fetchColumn();
    }

    /**
     * Retorna um registro pelo shortcode
     *
     * @param string $shortcode
     * @return array
     */
    public function getVideoPeloShortcode($shortcode)
    {
        $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE shortcode = ?';

        return  $this->conn->fetchAssoc($sql, [$shortcode]);
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
        ])
            ->addPropertyConstraints('picasaVideoId', [
                new Constraints\NotBlank(),
            ])
            ->addPropertyConstraints('categoria', [
                new Constraints\NotBlank(),
            ])
        ;
    }

}