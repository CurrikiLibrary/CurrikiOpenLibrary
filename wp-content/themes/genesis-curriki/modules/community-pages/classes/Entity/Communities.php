<?php

/**
 * Communities
 */
class Communities
{
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="communityid", type="bigint", unique=true)
     */
    private $communityid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tagline", type="string", length=500)
     */
    private $tagline;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=500)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=500)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=500)
     */
    private $logo;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set communityid
     *
     * @param integer $communityid
     *
     * @return Communities
     */
    public function setCommunityid($communityid)
    {
        $this->communityid = $communityid;

        return $this;
    }

    /**
     * Get communityid
     *
     * @return int
     */
    public function getCommunityid()
    {
        return $this->communityid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Communities
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set tagline
     *
     * @param string $tagline
     *
     * @return Communities
     */
    public function setTagline($tagline)
    {
        $this->tagline = $tagline;

        return $this;
    }

    /**
     * Get tagline
     *
     * @return string
     */
    public function getTagline()
    {
        return $this->tagline;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Communities
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Communities
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Communities
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }
}

