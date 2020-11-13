<?php

namespace App\Repository;

use Doctrine\ORM\UnitOfWork;
use Doctrine\ORM\EntityRepository;

class Repository extends EntityRepository 
{
    /**
     * Retorna uma referência da entidade
     *
     * @param int $id
     * @param string $class
     * @return void
     */
    public function getReference($id, $class = null) {
        if (!$class) {
            $class = $this->getClassName();
        }
        return $this->getEntityManager()->getReference($class, $id);
    }

    /**
     * Salva a entidade no banco de dados
     *
     * @param object $entity
     * @return object
     */
    public function save($entity) {
        if ($this->getEntityManager()->getUnitOfWork()->getEntityState($entity) == UnitOfWork::STATE_NEW) {
            $this->getEntityManager()->persist($entity);
        }
        $this->getEntityManager()->flush();
        return $entity;
    }

    /**
     * Remove a entidade do banco de dados
     *
     * @param int $id
     * @return void
     */
    public function delete($id) {
        $entity = $this->getReference($id);
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Executa uma SQL no banco de dados
     *
     * @param string $sql
     * @param boolean $isUpdate - Define se trata de uma operação update ou delete
     * @param array $bindParams - opção de usar bind parameters
     * @param boolean $returnArray - Se deve retornar um array ou um objeto no caso de uma SELECT
     * @return boolean|array|object
     */
    protected function runSql($sql, $isUpdate = false, array $bindParams = [], $returnArray = true)
    {
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        
        if(!empty($bindParams) && count($bindParams) > 0) {
            foreach($bindParams as $bindParam) {
                if(!empty($bindParam['param']) && !empty($bindParam['value'])){
                    $stmt->bindValue($bindParam['param'], $bindParam['value']);
                }
            }
        }
        
        if($isUpdate) {
            return $stmt->execute();
        } else  {
            $stmt->execute();
            return $returnArray ? $stmt->fetchAll() : $stmt->fetch();
        }
    }

}
