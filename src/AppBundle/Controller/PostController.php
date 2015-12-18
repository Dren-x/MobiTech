<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PostController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->get('doctrine')->getEntityManager();
        $posts = $em->getRepository('AcmeDemoBundle:Post')->findAll();
        return array('posts' => $posts);
    }

    /**
     * @Template()
     */
    public function newAction()
    {
        $post = new Post();
        // Получаем пользователя и присваиваем новому посту
        $post->user = $this->get('security.context')->getToken()->getUser();
        // Создаем форму
        $form = $this->get('form.factory')
            ->createBuilder('form', $post)
            ->add('title', 'text')
            ->add('description', 'textarea')
            ->getForm();
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getEntityManager();
                $em->persist($post);
                $em->flush();
                return $this->redirect($this->generateUrl('post_index'));
            }
        }
        return array('form' => $form->createView());
    }
}
