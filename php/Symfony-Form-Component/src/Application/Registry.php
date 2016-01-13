<?php
namespace Application;


use Doctrine\Common\Persistence\AbstractManagerRegistry;

class Registry extends AbstractManagerRegistry
{
    public function __construct(array $connections, array $entityManagers, $defaultConnection, $defaultEntityManager)
    {
        parent::__construct('ORM', $connections, $entityManagers, $defaultConnection, $defaultEntityManager, 'Doctrine\ORM\Proxy\Proxy');
    }


    /**
     * Resolves a registered namespace alias to the full namespace.
     *
     * This method looks for the alias in all registered object managers.
     *
     * @param string $alias The alias.
     *
     * @return string The full namespace.
     */
    public function getAliasNamespace($alias)
    {
        // TODO: Implement getAliasNamespace() method.
    }

    /**
     * Fetches/creates the given services.
     *
     * A service in this context is connection or a manager instance.
     *
     * @param string $name The name of the service.
     *
     * @return object The instance of the given service.
     */
    protected function getService($name)
    {
        // TODO: Implement getService() method.
    }

    /**
     * Resets the given services.
     *
     * A service in this context is connection or a manager instance.
     *
     * @param string $name The name of the service.
     *
     * @return void
     */
    protected function resetService($name)
    {
        // TODO: Implement resetService() method.
    }
}