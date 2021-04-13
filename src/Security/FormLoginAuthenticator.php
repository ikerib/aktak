<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PasaiaLdapService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;
    /**
     * @var CsrfTokenManagerInterface
     */
    private CsrfTokenManagerInterface $csrfTokenManager;
    /**
     * @var PasaiaLdapService
     */
    private PasaiaLdapService $pasaiaLdapSrv;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(UserRepository $userRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, PasaiaLdapService $pasaiaLdapSrv, EntityManagerInterface $em)
    {
        $this->userRepository   = $userRepository;
        $this->router           = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->pasaiaLdapSrv    = $pasaiaLdapSrv;
        $this->em               = $em;
    }

    public function supports(Request $request): bool
    {
        return 'app_login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            '_username'   => $request->request->get('_username'),
            '_password'   => $request->request->get('_password'),
            '_csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(Security::LAST_USERNAME, $credentials[ '_username' ]);

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials[ '_csrf_token' ]);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('Token CSRF invalido');
        }

        $dbUser = $this->userRepository->findOneBy([ 'username' => $credentials[ '_username' ] ]);

        if (!$dbUser) {
            // User is not present in the Database, let's create it
            return $this->pasaiaLdapSrv->createDbUserFromLdapData($credentials[ '_username' ]);
        }
        // The User exists in the database, let's update it's data
        return $this->pasaiaLdapSrv->updateDbUserDataFromLdapByUsername($credentials[ '_username' ]);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->pasaiaLdapSrv->checkCredentials( $credentials[ '_username' ], $credentials[ '_password' ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->router->generate('erabakia_index'));
    }

    protected function getLoginUrl(): string
    {
        return $this->router->generate('app_login');
    }
}
