<?php
namespace Blog\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blog\BlogBundle\Entity\Enquiry;
use Blog\BlogBundle\Form\EnquiryType;


class PageController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()
            ->getManager();

        $blogs = $em->getRepository('BlogBlogBundle:Blog')
            ->getLatestBlogs();

        return $this->render('BlogBlogBundle:Page:index.html.twig', array('blogs' => $blogs));
    }
    public function aboutAction()
    {
        return $this->render('BlogBlogBundle:Page:about.html.twig');
    }
    public function contactAction()
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {

                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from blog')
                    ->setFrom('enquiries@symblog.co.uk')
                    ->setTo($this->container->getParameter('blog_blog.emails.contact_email'))
                    ->setBody($this->renderView('BlogBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add('blog-notice', 'Ваш запрос успешно отправлен. Ожидайте скорого ответа! ');

                return $this->redirect($this->generateUrl('BlogBlogBundle_contact'));
            }
        }


        return $this->render('BlogBlogBundle:Page:contact.html.twig', array('form' => $form->createView()));
    }

}