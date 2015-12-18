<?php
namespace Blog\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blog\BlogBundle\Entity\Comment;
use Blog\BlogBundle\Form\CommentType;

/**
* Comment controller.
*/
class CommentController extends Controller
{
public function newAction($entity_id)
{
$entity = $this->getBlog($entity_id);

$comment = new Comment();
$comment->setBlog($entity);
$comment->setUser($this->getUser()->getUsername());
$form   = $this->createForm(new CommentType(), $comment);

return $this->render('BlogBlogBundle:Comment:form.html.twig', array(
'comment' => $comment,
'form'   => $form->createView()
));
}

public function createAction($blog_id)
{
$entity = $this->getBlog($blog_id);

$comment  = new Comment();
$comment->setBlog($entity);
$request = $this->getRequest();
$form    = $this->createForm(new CommentType(), $comment);
$form->bind($request);

if ($form->isValid()) {
$em = $this->getDoctrine()
    ->getManager();

    $em->persist($comment);
    $em->flush();

    return $this->redirect($this->generateUrl('BlogBlogBundle_blog_show', array(
            'id' => $comment->getBlog()->getId())) .
        '#comment-' . $comment->getId()
);
}

return $this->render('BlogBlogBundle:Comment:create.html.twig', array(
'comment' => $comment,
'form'    => $form->createView()
));
}

protected function getBlog($blog_id)
{
$em = $this->getDoctrine()
->getManager();

$entity = $em->getRepository('BlogBlogBundle:Blog')->find($blog_id);

if (!$entity) {
throw $this->createNotFoundException('Unable to find Blog post.');
}

return $entity;
}

}