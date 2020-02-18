<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class UserController extends AbstractController
{
   
    public function registrarse(Request $solicitud, UserPasswordEncoderInterface $encoder)
    {
        $usuario = new User();
        $formulario = $this->createForm(RegisterType::class, $usuario);

        /**se comprueba de que existan datos */
        $formulario->handleRequest($solicitud);

        /*se definen los datos rfecibidos */
        if($formulario->isSubmitted() && $formulario->isvalid()){
           $usuario->setRole('ROLE_USER');
           $usuario->setCreatedAt(new \Datetime('now'));
           $encoded =$encoder->encodePassword($usuario, $usuario->getPassword());
           $usuario->setPassword($encoded);
          
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            return $this->redirectToRoute('login');
        }
        return $this->render('user/registrarse.html.twig',[
            'form'=> $formulario->createView()
        ]);
    }

    public function login (AuthenticationUtils $autenticacion)
    {
        $error =$autenticacion->getLastAuthenticationError();
        $last_username = $autenticacion->getLastUsername();
        return $this->render('user/login.html.twig', [
            'error' => $error,
            'last_user' => $last_username
        ]);
    }
}
