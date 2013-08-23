<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue\Tests\Fixtures\Entity;

class Car {
    
    private $model;
    private $year;
    private $price;
    
    public function getModel()
    {
        return $this->model;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

}