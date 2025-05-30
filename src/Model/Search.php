<?php
namespace App\Model;

/**
 * Permet de créer un formulaire de recherche grace à un SearchType
 */
class Search
{
    /**
     * @var string
     */
    private $string = '';

    /**
     * @var array
     */
    private $categories = [];


    public function getString()
    {
        return $this->string;
    }

    /**

     * @return  self
     */ 
    public function setString($string)
    {
        $this->string = $string;

        return $this;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    /**

     * @return  self
     */ 
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }
}