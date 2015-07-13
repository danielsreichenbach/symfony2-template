<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller used to display user related pages in the application
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class UserController extends Controller
{
    /**
     * Display a list of recently registered users
     *
     * @return Response
     */
    public function latestAction()
    {
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        $users = $userRepository->findLatestUsers(10);

        return $this->render('user/latest.html.twig', array('users' => $users));
    }

    /**
     * Display a User profile
     *
     * @param  string $username
     * @return Response
     */
    public function profileAction($username)
    {
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        $user = $userRepository->findOneBy(
            array(
                'username' => $username,
                'enabled' => 1,
            )
        );

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf(
                    'The user with username "%s" was not found.',
                    $username
                )
            );
        }

        return $this->render('user/profile.html.twig', array('user' => $user));
    }
}
