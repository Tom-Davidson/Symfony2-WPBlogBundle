<?php

namespace TomDavidson\WPBlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TomDavidson\WPBlogBundle\Entity\Comment
 *
 * @ORM\Entity
 * @ORM\Table(name="wp_comments")
 */
class Comment
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="comment_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var $post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
	 * @ORM\JoinColumn(name="comment_post_ID", referencedColumnName="id")
     */
    private $post;

    /**
     * @var string $author
     *
     * @ORM\Column(name="comment_author", type="string", length=50)
     */
    private $author;

    /**
     * @var string $authorEmail
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="comment_author_email", type="string", length=100)
     */
    private $authorEmail;

    /**
     * @var string $authorURL
     *
     * @ORM\Column(name="comment_author_url", type="string", length=200)
     */
    private $authorURL;

    /**
     * @var string $authorIP
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="comment_author_IP", type="string", length=100)
     */
    private $authorIP;

    /**
     * @var datetime $date
     *
     * @Assert\NotBlank()
	 * @Assert\Type("\DateTime")
     * @ORM\Column(name="comment_date", type="datetime")
     */
    private $date;

    /**
     * @var text $content
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="comment_content", type="text")
     */
    private $content;

    /**
     * @var string $approved
     *
     * @ORM\Column(name="comment_approved", type="string", length=5)
     */
    private $approved;

    /**
     * @var string $type
     *
     * @ORM\Column(name="comment_type", type="string", length=20)
     */
    private $type;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set post
     *
     * @param object $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * Get post
     *
     * @return string 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set author
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set authorEmail
     *
     * @param string $authorEmail
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * Get authorEmail
     *
     * @return string 
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * Set authorURL
     *
     * @param string $authorURL
     */
    public function setAuthorURL($authorURL)
    {
        $this->authorURL = $authorURL;
    }

    /**
     * Get authorURL
     *
     * @return string 
     */
    public function getAuthorURL()
    {
        return $this->authorURL;
    }

    /**
     * Set authorIP
     *
     * @param string $authorIP
     */
    public function setAuthorIP($authorIP)
    {
        $this->authorIP = $authorIP;
    }

    /**
     * Get authorIP
     *
     * @return string 
     */
    public function getAuthorIP()
    {
        return $this->authorIP;
    }

    /**
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date = false)
    {
        if($date instanceof DateTime){
            $this->date = $date;
        }else{
            $this->date = new \DateTime("now");
        }
        
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set approved
     *
     * @param string $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * Get approved
     *
     * @return string 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Is approved
     *
     * @return boolean 
     */
    public function isApproved()
    {
		if($this->getApproved() == '1'){
			return true;
		}else{
			return false;
		}
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}