<?php

namespace TomDavidson\WPBlogBundle\Entity;

#use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TomDavidson\WPBlogBundle\Entity\Post
 *
 * @ORM\Entity
 * @ORM\Table(name="wp_posts")
 */
class Post
{
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var integer $author
	 *
	 * @ORM\Column(name="post_author", type="integer")
	 */
	private $author;

	/**
	 * @var datetime $date
	 *
	 * @ORM\Column(name="post_date", type="datetime")
	 */
	private $date;

	/**
	 * @var string $content
	 *
	 * @ORM\Column(name="post_content", type="text")
	 */
	private $content;

	/**
	 * @var string $title
	 *
	 * @ORM\Column(name="post_title", type="string", length=255)
	 */
	private $title;

	/**
	 * @var string $name
	 *
	 * @ORM\Column(name="post_name", type="string", length=255)
	 */
	private $name;

	/**
	 * @var string $status
	 *
	 * @ORM\Column(name="post_status", type="string", length=20)
	 */
	private $status;

	/**
	 * @var array $comments
	 *
	 * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
	 * @ORM\OrderBy({"date" = "ASC"})
	 */
	private $comments;


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
	 * Set author
	 *
	 * @param object $author
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
	}

	/**
	 * Get author
	 *
	 * @return object 
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * Set date
	 *
	 * @param date $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
	}

	/**
	 * Get date
	 *
	 * @return date 
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * Get content
	 *
	 * @return string 
	 */
	public function getContent()
	{
		$content = $this->content;
		$content = str_replace(
			array("\r",	"\n"),
			array('',	'<br />'),
			$content
		);
		# strip <img> classes
		$content = preg_replace('/<img class="(.*?)"/', '<img', $content);
		return $content;
	}

	/**
	 * Get an abstract of the content
	 *
	 * @return string 
	 */
	public function getAbstract()
	{
		$content = strip_tags($this->getContent(), '<b><i><u>');
		if(strlen($content) > 200){
			$sentences = explode('.', $content);
			$content = '';
			$sentencesUsed = 0;
			while(strlen($content) < 200 && $sentencesUsed < count($sentences)-2){
				$content .= $sentences[$sentencesUsed].'.';
				$sentencesUsed ++;
			}
		}
		return $content;
	}

	/**
	 * Set title
	 *
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * Get title
	 *
	 * @return string 
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Get name
	 *
	 * @return string 
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set status
	 *
	 * @param string $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * Get status
	 *
	 * @return string 
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Set comments
	 *
	 * @param array $comments
	 */
	public function setComments($comments)
	{
		$this->comments = $comments;
	}

	/**
	 * Get comments
	 *
	 * @param  boolean $approvedOnly Only give back the comments that have been approved by the blog admin, defaults to true.
	 * @return array 
	 */
	public function getComments($approvedOnly = true)
	{
		if($approvedOnly){
			$comments = array();
			foreach($this->comments as $comment){
				if($comment->isApproved()){
					array_push($comments, $comment);
				}
			}
			return $comments;
		}else{
			return $this->comments;
		}
	}

	/**
	 * Get comments
	 *
	 * @return integer 
	 */
	public function getNoComments()
	{
		$count = 0;
		foreach($this->comments as $comment){
			if($comment->isApproved()){
				$count ++;
			}
		}
		return $count;
	}
}