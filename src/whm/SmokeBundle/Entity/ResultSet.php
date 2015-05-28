<?php

namespace whm\SmokeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultSet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="whm\SmokeBundle\Entity\ResultSetRepository")
 */
class ResultSet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=500)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_urls", type="integer")
     */
    private $numUrls;

    /**
     * @var array
     *
     * @ORM\Column(name="results", type="array")
     */
    private $results;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


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
     * Set url
     *
     * @param string $url
     * @return ResultSet
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
     * Set numUrls
     *
     * @param integer $numUrls
     * @return ResultSet
     */
    public function setNumUrls($numUrls)
    {
        $this->numUrls = $numUrls;

        return $this;
    }

    /**
     * Get numUrls
     *
     * @return integer
     */
    public function getNumUrls()
    {
        return $this->numUrls;
    }

    /**
     * Set results
     *
     * @param array $results
     * @return ResultSet
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get results
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return ResultSet
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

}
