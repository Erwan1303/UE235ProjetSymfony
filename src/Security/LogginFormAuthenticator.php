<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class LogginFormAuthenticator extends AbstractGuardAuthenticator
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'security_login'
            // Je n’interviens que si la request possède dans ces attributs 
            // quelques chose qui s’appelle “_route “ et qui doit être égal a “security_login”. 
            && $request->isMethod('POST');
        // et uniquement si la requeste est en methode POST
    }

    public function getCredentials(Request $request)
    {
        // But : ressort les informations de connexion à partir de la request. Montrer moi vos papiers !
        // On affiche les infos d'identification
        // Affichage du tableau ('login')
        return $request->request->get('login');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // le but du getUser est de chercher dans la base de données si l'utilisateur y est bien et si ses papiers 
        //sont en régles. Dans notre cas il va chercher l'email
        // return $userProvider->loadUserByUsername($credentials['email']);
        try {
            return $userProvider->loadUserByUsername($credentials['email']);
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException("cette adresse email n'est pas connue");
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // le but de cette étape est de vérifier si les infos récupérées dans la base de données 
        // correspondent bien à ceux renseignés. Vérifier que le mot de passe fourni est bien identique aux mots de passe 
        // fournis ($credentials['password'] => $user->getpassword())
        // attention à l'encodage 
        $isValid = $this->encoder->isPasswordValid($user, $credentials['password']);
        // SI TOUT EST OK L'IDENTIFICATION EST FAITE
        if (!$isValid) {
            throw new AuthenticationException("Les informations de connexion ne correspondent pas");
        }
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

        $request->attributes->set(Security::AUTHENTICATION_ERROR, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // redirection sur la page d'accueil 
        return new RedirectResponse('/article');
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
