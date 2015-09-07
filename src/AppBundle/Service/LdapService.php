<?php

namespace AppBundle\Service;

/**
 * Class LdapService
 */
class LdapService
{
    private $ldapConnection = null;
    private $config;

    /**
     * Initialise LdapService
     *
     * La connecion au LDAP n'est pas initialisée ici car ce service est instancié à chaque appel (à chaque connexion d'un utilisateur par exemple).
     * On initialisera la connexion avant chaque appel groupé.
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    private function connect()
    {
        if($this->config['cti']['ssl']) {
            $host = 'ldaps://' . $this->config['cti']['host'];
        } else {
            $host = $this->config['cti']['host'];
        }

        // Mode débug en mode CLI
        // ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);

        $this->ldapConnection = ldap_connect($host);
        ldap_bind($this->ldapConnection, $this->config['cti']['bind_dn'], $this->config['cti']['password']);
    }

    private function getConnection()
    {
        if (null === $this->ldapConnection) {
            $this->connect();
        }

        return $this->ldapConnection;
    }

    /**
     * Get Data for a Uid
     *
     * @param string $ctiUid
     * @return array
     */
    public function getDataByUid($uid)
    {
        $resource = ldap_search($this->getConnection(), $this->config['cti']['base_dn'], 'uid=' . $uid, array('uid', 'givenName', 'sn', 'mail', 'jpegPhoto'));
        $results = ldap_get_entries($this->getConnection(), $resource);

        if($results['count'] == 1) {
            return $results[0];
        }

        return null;
    }
}
