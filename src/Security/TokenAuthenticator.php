<?php


namespace App\Security;


use App\Repository\AuthAccessTokensRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private EntityManagerInterface $em;
    private AuthAccessTokensRepository $authAccessTokensRepository;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, AuthAccessTokensRepository $authAccessTokensRepository, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->authAccessTokensRepository = $authAccessTokensRepository;
        $this->userRepository = $userRepository;
    }


    public function supports(Request $request)
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }


    public function getCredentials(Request $request)
    {
        $jwt = $request->headers->get('X-AUTH-TOKEN');

        try {
            $response = $this->authAccessTokensRepository->find($jwt)->getUserId();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $this->userRepository->find($response)->getEmail();
    }


    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null === $credentials) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            return null;
        }

        // Dans SmartEvent, le Username est l'email
        return $userProvider->loadUserByUsername($credentials);
    }


    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        /*$data = [
            'message' => 'T\'as pas le droit'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);*/
        return new RedirectResponse('/login');
    }


    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }


    public function supportsRememberMe()
    {
        return false;
    }
}