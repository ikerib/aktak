<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Ldap\Adapter\ExtLdap\Adapter;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class PasaiaLdapService
{
    private $ip;
    private $ldap_username;
    private $basedn;
    private $passwd;
    private $auth_group;
    private $em;


    public function __construct($ip, $ldap_username, $basedn, $passwd, $auth_group, EntityManagerInterface $em)
    {
        $this->ip            = $ip;
        $this->ldap_username = $ldap_username;
        $this->basedn        = $basedn;
        $this->passwd        = $passwd;
        $this->em            = $em;
        $this->auth_group    = $auth_group;
    }

    public function checkCredentials($username, $password): bool
    {
        $ip       = $this->ip;
        $searchdn = "CN=$username,CN=Users,DC=pasaia,DC=net";

        /**
         * LDAP KONTSULTA EGIN erabiltzailearen bila
         */
        $srv = "ldap://$ip:389";
        $ldap = Ldap::create('ext_ldap', ['connection_string' => $srv]);
        try {
            $ldap->bind($searchdn, $password);
        } catch (ConnectionException $e) {
            //return false;
            throw new CustomUserMessageAuthenticationException('Pasahitza ez da zuzena.');
        }

        // check user is in LDAP_GROUP
        $groups = $this->getLdapUserMembershipGroupsRecursivelyByUsername( $username );

        if ( !in_array( $this->auth_group, $groups[ 'app' ], true ) ) {
            return false;
        }

        return true;
    }

    public function getLdapInfoByUsername($username)
    {
        $ip       = $this->ip;
        $searchdn = $this->ldap_username;
        $basedn   = $this->basedn;
        $passwd   = $this->passwd;

        /**
         * LDAP KONTSULTA EGIN erabiltzailearen bila
         */
        $srv = "ldap://$ip:389";
        $ldap = Ldap::create('ext_ldap', ['connection_string' => $srv]);
        $ldap->bind('CN=izfeprint,CN=Users,DC=pasaia,DC=net', $passwd);
        $query = $ldap->query($basedn, "(sAMAccountName=$username)", array());

        return $query->execute();
    }

    public function updateDbUserDataFromLdapByUsername($username): User
    {
        $dbUser    = $this->em->getRepository(User::class)->findOneBy(['username' => $username]);
        $ldapQuery = $this->getLdapInfoByUsername($username);
        $ldapData  = $ldapQuery[0];
        $dbUser    = $this->syncUserInfoFromLdap($dbUser, $ldapData);

        return $dbUser;
    }

    public function createDbUserFromLdapData($username): User
    {
        $ldapQuery = $this->getLdapInfoByUsername($username);
        /** @var Entry $ldapData */
        $ldapData = $ldapQuery[0];
        $user     = new User();
        $user->setUsername($username);
        $user = $this->syncUserInfoFromLdap($user, $ldapData);

        return $user;
    }

    /**
     * @param User  $user
     * @param Entry $ldapData
     *
     * @return User
     */
    private function syncUserInfoFromLdap(User $user, $ldapData): User
    {

        $ldap = $ldapData->getAttributes();

        if (array_key_exists('employeeID', $ldap)) {
            $user->setNan((string)$ldap['employeeID'][0]);
        }

        if (array_key_exists('preferredLanguage', $ldap)) {
            $user->setHizkuntza((string)$ldap['preferredLanguage'][0]);
        }

        if (array_key_exists('mail', $ldap)) {
            $user->setEmail((string)$ldap['mail'][0]);
        }

        if (array_key_exists('givenName', $ldap)) {
            $user->setFirstname((string)$ldap['givenName'][0]);
        }

        if (array_key_exists('sn', $ldap)) {
            $user->setSurname((string)$ldap['sn'][0]);
        }

        if ((array_key_exists('givenName', $ldap)) && (array_key_exists('sn', $ldap))) {
            $user->setDisplayname((string)$ldap['givenName'][0] . ' ' . (string)$ldap['sn'][0]);
        } elseif (!array_key_exists('givenName', $ldap)) {
            $user->setDisplayname((string)$ldap['sn'][0]);
        } elseif (!array_key_exists('sn', $ldap)) {
            $user->setDisplayname((string)$ldap['givenName'][0]);
        } else {
            $user->setDisplayname($user->getUsername());
        }

        if (array_key_exists('description', $ldap)) {
            $user->setLanpostua((string)$ldap['description'][0]);
        }

        if (array_key_exists('department', $ldap)) {
            $user->setDeparment((string)$ldap['department'][0]);
        }

        $sailburuArr = $this->checkSailburuada($user->getUsername());
        $rol   = ['ROLE_USER'];
        if ($sailburuArr['sailburuada']){
            $rol[] = 'ROLE_SAILBURUA';
        }
        $user->setSailburuada($sailburuArr['sailburuada']);
        $ldapTaldeak = $this->getLdapUserMembershipGroupsRecursivelyByUsername($user->getUsername());
        $user->setLdapTaldeak($ldapTaldeak);

        /**
         * TODO: Rolak zehaztu
         */
        if ($this->in_array_r('APP-Web_Aktak-Kudeatzailea', $ldapTaldeak,false)) {
            $rol[] = 'ROLE_ADMIN';
        }


        $user->setRoles($rol);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function in_array_r( $needle, $haystack, $strict = false): bool
    {
        foreach ($haystack as $item) {

            if( ! $strict && is_string( $needle ) && ( is_float( $item ) || is_int( $item ) ) ) {
                $item = (string)$item;
            }

            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }

    public function checkSailburuada($username): array
    {
        $ip            = $this->ip;
        $basedn        = $this->basedn;
        $passwd        = $this->passwd;
        $resp          = [];

        $srv = "ldap://$ip:389";
        $ldap = Ldap::create('ext_ldap', ['connection_string' => $srv]);
        $ldap->bind('CN=izfeprint,CN=Users,DC=pasaia,DC=net', $passwd);

        // Sailburuada
        $gFilter = "(&(samAccountName=$username)(memberOf:1.2.840.113556.1.4.1941:=CN=Taldea-Sailburuak,CN=Users,DC=pasaia,DC=net))";
        $query = $ldap->query($basedn, $gFilter);
        $result =$query->execute();
        $resp['sailburuada'] = $result['count'];

        // Saila
        $gFilter = "(member:1.2.840.113556.1.4.1941:=cn=$username,cn=users,dc=pasaia,dc=net)";
        $query = $ldap->query($basedn, $gFilter);
        $result2 =$query->execute();

        /**
         * @var Entry $entry
         * @var int $key
         */
        foreach ($result2 as $key => $entry) {
            if ($key !== 'count') {
                $taldea = $entry->getAttribute( 'name' )[ 0 ];
                if (strpos($taldea, 'Saila') === 0) {
                    $resp['saila'] = explode('-', $taldea)[1];
                }
            }
        }

        return $resp;
    }

    public function getLdapUserMembershipGroupsRecursivelyByUsername($username): array
    {
        $ip            = $this->ip;
        $basedn        = $this->basedn;
        $passwd        = $this->passwd;
        $ldapSarbideak = [];
        $ldapRolak     = [];
        $ldapSailak    = [];
        $ldapTaldeak   = [];
        $ldapApp       = [];

        $srv = "ldap://$ip:389";
        $ldap = Ldap::create('ext_ldap', ['connection_string' => $srv]);
        $ldap->bind('CN=izfeprint,CN=Users,DC=pasaia,DC=net', $passwd);
        $gFilter = "(member:1.2.840.113556.1.4.1941:=cn=$username,cn=users,dc=pasaia,dc=net)";
        $query = $ldap->query($basedn, $gFilter);
        $allGroups = $query->execute();

        /**
         * @var int $key
         * @var Entry $group
         */
        foreach ($allGroups as $key => $group) {
            if ($key !== 'count') {
                $taldea = $group->getAttribute( 'name' )[0];
                switch ($taldea) {
                    case strpos($taldea,'APP-')===0:
                        $ldapApp[] = $taldea;
                        break;
                    case strpos($taldea,'ROL-'):
                        $ldapRolak[] = $taldea;
                        break;
                    case strpos($taldea,'Saila-'):
                        $ldapSailak[] = $taldea;
                        break;
                    case strpos( $taldea, 'SARBIDE-'):
                        $ldapSarbideak[] = $taldea;
                        break;
                    case strpos($taldea, 'TALDEA-'):
                        $ldapTaldeak[] = $taldea;
                        break;
                }
            }
        }

        return [
            'app'     => $ldapApp,
            'rol'     => $ldapRolak,
            'saila'   => $ldapSailak,
            'sarbide' => $ldapSarbideak,
            'taldeak' => $ldapTaldeak,
        ];
    }


    /** TODO: REMOVE?
     *
     * @param $groupname
     *
     * @return array
     */

    public function getGroupUsersRecurive($groupname): array
    {
        $ip            = $this->ip;
        $ldap_username = $this->ldap_username;
        $basedn        = $this->basedn;
        $passwd        = $this->passwd;


        $ldap = ldap_connect($ip) or die('Could not connect to LDAP');

        ldap_bind($ldap, $ldap_username, $passwd) or die('Could not bind to LDAP');


        $gFilter = "(&(objectClass=posixAccount)(memberOf:1.2.840.113556.1.4.1941:=CN=$groupname,CN=Users,DC=pasaia,DC=net))";
        $gAttr   = array('samAccountName');
        $result = ldap_search($ldap, $basedn, $gFilter, $gAttr) or exit('Unable to search LDAP server');
        $ldapusers = ldap_get_entries($ldap, $result);
        $users     = [];
        foreach ($ldapusers as $key => $value) {
            if ($key !== 'count') {
                $username = $value['samaccountname'][0];
                $users[]  = $username;
            }
        }

        return $users;
    }

    public function checkArduraduna($username): array
    {
        $ip            = $this->ip;
        $ldap_username = $this->ldap_username;
        $basedn        = $this->basedn;
        $passwd        = $this->passwd;
        $resp          = [];

        $ldap = ldap_connect($ip) or die('Could not connect to LDAP');
        ldap_bind($ldap, $ldap_username, $passwd) or die('Could not bind to LDAP');

        // Sailburuada
        $gFilter = "(&(samAccountName=$username)(memberOf:1.2.840.113556.1.4.1941:=CN=App-Web_Egutegia-Arduraduna,CN=Users,DC=pasaia,DC=net))";
        $gAttr   = array('samAccountName');
        $result = ldap_search($ldap, $basedn, $gFilter, $gAttr) or exit('Unable to search LDAP server');
        $result            = ldap_get_entries($ldap, $result);
        $resp['alkateada'] = $result['count'];

        return $resp;
    }

}
