<?php

namespace TomDavidson\WPBlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TomDavidson\WPBlogBundle\Entity\Post;
use TomDavidson\WPBlogBundle\Entity\Comment;
use TomDavidson\WPBlogBundle\Entity\Subscriber;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="blog")
     * @Template()
     */
    public function indexAction()
    {
		return array();
    }

    /**
     * @Route("/feed", name="blog_feed")
     * @Template()
     */
    public function feedAction($count = 10)
    {
        return $this->render(
			'TomDavidsonWPBlogBundle:Default:feed.xml.twig',
			array(
				'posts' => $this->getPosts($count)
			)
		);
    }

    /**
     * @Route("/{permalink}", name="blog_post")
     * @Template()
     */
    public function postAction(Request $request, $permalink)
    {
		if(preg_match('/([0-9]+)\-(.*)/', $permalink, $matches)){
			$id = (int)$matches[1];
			$name = $matches[2];
			if(!empty($id) && is_int($id) && $id > 0){
				$post = $this->getDoctrine()
					->getRepository('TomDavidsonWPBlogBundle:Post')
					->find($id);
				// Set up the form
				$comment = new Comment();
				$comment->setPost($post);
				$comment->setAuthor('anonymous');
				$comment->setAuthorEmail('');
				$comment->setAuthorURL('');
				$comment->setAuthorIP($_SERVER['REMOTE_ADDR']);
				$comment->setDate();
				$comment->setContent('');
				$comment->setApproved(0);
				$comment->setType('');
				$form = $this->createFormBuilder($comment)
					->add('author',			'text',		array('label'=>'Your Name', 'required'=>true, 'max_length'=>100))
					->add('authorEmail',	'email',	array('label'=>'Your Email', 'required'=>true, 'max_length'=>100))
					->add('authorURL',		'text',		array('label'=>'Your Website', 'required'=>false, 'max_length'=>200))
					->add('content',		'textarea',	array('label'=>'Your Comment', 'required'=>true, 'max_length'=>5000))
					->add('captcha',		'captcha',	array('length' => 6))
					->getForm();
				$newcommentmessage = '';
				// Form handling
				if ($request->getMethod() == 'POST') {
					$form->bindRequest($request);
					if ($form->isValid()) {
						// Set any defaults lost in form submission
						if($comment->getAuthorURL()==null){ $comment->setAuthorURL(''); }
						$comment->setAuthorIP($_SERVER['REMOTE_ADDR']);
						$comment->setDate();
						// perform some action, such as saving the task to the database
						$em = $this->getDoctrine()->getEntityManager();
						$em->persist($comment);
						$em->flush();
						return $this->redirect($this->generateUrl('blog_post', array('permalink'=>$permalink, 'comment'=>'accepted')).'#blogpostnewcomment');
					}
				}
				// Form handling after redirect
				if($request->get('comment') == 'accepted'){
					$newcommentmessage = 'Thank you for commenting. Your comment is awaiting review.';
				}
				return array(
					'post'	=> $post,
					'form'	=> $form->createView(),
					'newcommentmessage' => $newcommentmessage
				);
			}else{
				throw new \Exception('Permalink invalid: id='.$id);
			}
		}else{
			throw new \Exception('Permalink not recognised.');
		}
    }
	public function listAction($count = 3){
        return $this->render(
			'TomDavidsonWPBlogBundle:Default:list.html.twig',
			array(
				'posts' => $this->getPosts($count)
			)
		);
	}
	public function sidebarAction($count = 3){
        return $this->render(
			'TomDavidsonWPBlogBundle:Sidebar:posts.html.twig',
			array(
				'posts' => $this->getPosts($count)
			)
		);
	}
	public function sidebarSubscribeAction(Request $request){
		// Set up the form
		$subscriber = new Subscriber();
		$form = $this->createFormBuilder($subscriber)
			->add('email',		'email',	array('label'=>'Your Email', 'required'=>true, 'max_length'=>64))
			//->add('captcha',	'captcha',	array('length' => 6))
			->getForm();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				// Set any defaults lost in form submission
				$subscriber->setIP($_SERVER['REMOTE_ADDR']);
				$subscriber->setDate();
				// perform some action, such as saving the task to the database
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($subscriber);
				$em->flush();
				return $this->render(
					'TomDavidsonWPBlogBundle:Sidebar:blogSubscribe.html.twig',
					array(
						'form'		=> $form->createView(),
						'message'	=> 'Thank you, your subscription has been successful.'
					)
				);
			}
		}
		return $this->render(
			'TomDavidsonWPBlogBundle:Sidebar:blogSubscribe.html.twig',
			array(
				'form'		=> $form->createView(),
				'message'	=> 'To subscribe to my blog please enter your email address below.'
			)
		);
	}
	private function getPosts($count = 5){
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT p FROM TomDavidsonWPBlogBundle:Post p WHERE p.status = :status ORDER BY p.date DESC')->setParameter('status', 'publish')->setMaxResults($count);
		return $query->getResult();
	}
}
