<?php
/**
 * Description of PaymentDelivery
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class ProductsAvailability extends Response implements IProductsAvailability, IProductsAvailabilityValues {
    
    /** @var integer */
    private $_paramsKey = -1;
    
    /**
     * @param string $id ID produktu
     * @param integer $count počet objednávaných kusů (vždy větší než 0)
     * @param bool $available zda je produkt dostupný (false pokud produkt nelze v žádném případě objednat, jinak true) 
     * @param integer|string $delivery  	počet dní k odeslání (číselník), pokud obchod nedisponuje číslenou hodnotou, může uvést textovou variantu např. "na dotaz", "do 2 dnů".
     * @param string $name název produktu (max. 255 znaků)
     * @param string $price cena zboží za kus (vč. DPH a všech poplatků)
     */
    public function __construct($id, $count, $available, $delivery, $name, $price)
    {
        $this->setId($id);
        $this->setCount($count);
        $this->setAvailable($available);
        $this->setDelivery($delivery);
        $this->setName($name);
        $this->setPrice($price);
        $this->_parameters['priceTotal'] = $price * $count;
    }
    
    /**
     * související položky k produktu
     * (Jedná se o nějakou přidanou hodnotu k produktu, která nemá vliv na cenu.) 
     * 
     * @param string $title
     * @return IProductsAvailability
     */
    public function addRelated($title)
    {
        $this->_parameters['related'][] = array(
            'title' => (string) $title,
        );
        
        return $this;
    }

    /**
     * pole s produkty
     * 
     * @param integer $id
     * @return \products
     */
    protected function setId($id)
    {
        $this->_parameters['id'] = (string) $id;
        
        return $this;
    }
    
    /**
     * počet objednávaných kusů (vždy větší než 0)
     * 
     * @param type $count
     * @return \ProductsAvailability
     */
    protected function setCount($count)
    {
        if ($count > 0)
        {
            $this->_parameters['count'] = (integer) $count;
        }
        
        return $this;
    }
    
    /**
     * zda je produkt dostupný (false pokud produkt nelze v žádném případě objednat, jinak true) 
     * 
     * 
     * @param type $available
     * @return \ProductsAvailability
     */
    protected function setAvailable($available)
    {
        $this->_parameters['available'] = (bool) $available;
        
        return $this;
    }
    
    /**
     * počet dní k odeslání (číselník), pokud obchod nedisponuje číslenou hodnotou, může uvést textovou variantu např. "na dotaz", "do 2 dnů".
     * 
     * @param type $delivery
     * @return \ProductsAvailability
     */
    protected function setDelivery($delivery)
    {
        $this->_parameters['delivery'] = $delivery;
        
        return $this;
    }
    
    /**
     * název produktu (max. 255 znaků)
     * 
     * @param type $name
     * @return \ProductsAvailability
     */
    protected function setName($name)
    {
        $this->_parameters['name'] = $name;
        
        return $this;
    }
    
    /**
     * cena zboží za kus (vč. DPH a všech poplatků)
     * 
     * @param type $price
     * @return \ProductsAvailability
     */
    protected function setPrice($price)
    {
        $this->_parameters['price'] = (float) $price;
        
        return $this;
    }
    
    /**
     * parametry k produktu 
     * 
     * @param integer $id id produktu
     * @param string $title popis položky 
     * @param string $type typ (text, selectbox, multiselectbox) 
     * @param string $name název produktu (max. 255 znaků)
     * @param string $unit cena zboží za kus (vč. DPH a všech poplatků)
     * @return IProductsAvailabilityValues
     */
    public function addParam($id, $type, $name, $unit)
    {
        $this->_paramsKey = $this->_paramsKey + 1;
        $this->_parameters['params'][$this->_paramsKey]['id'] = (integer) $id;
        $this->_parameters['params'][$this->_paramsKey]['type'] = $type;
        $this->_parameters['params'][$this->_paramsKey]['name'] = $name;
        $this->_parameters['params'][$this->_paramsKey]['unit'] = $unit;
        
        return $this;
    }
    
    /**
     * pole s hodnotami k jednotlivim parametrum
     * 
     * @param integer $id
     * @param bool $default
     * @param string $value
     * @param strin $price
     * @return IProductsAvailabilityValues
     */
    public function addValue($id, $default, $value, $price)
    {
        $this->_parameters['params'][$this->_paramsKey]['values'][] = array(
                'id'         => (int) $id,
                'default'    => (bool) $default,
                'value'      => (string) $value,
                'price'      => (float) $price,
        );
        
        return $this;
    }
}
