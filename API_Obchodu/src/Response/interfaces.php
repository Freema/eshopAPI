<?php
/**
 * Nastavení objednávky na storno
 * Storno objednávky je prováděno jen výjimečně, tak aby nedocházelo k problémům při expedici. 
 * 
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
interface IOrderCancel {
    
    /**
     * true došlo ke stornu, jinak false
     * 
     * @param boolean $status
     * @return IOrderCancel
     */
    function addCancelResponse($status);
    
}

/**
 * Odeslání objednávky do obchodu. 
 * 
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

interface IOrderSend {

    /**
     * Odeslání objednávky do obchodu. 
     * 
     * @param integer $order_id číslo objednávky
     * @param string $internal_id interní číslo objednávky v obchodu (typicky to které uvádíte zákazníkovi na faktuře) 
     * @param string $variableSymbol variabilní symbol (bez nevyýznamných nul, max. 10 čísel), bude sloužit ke spárování plateb při vybíjení kreditu 
     * @return IOrderSend
     */    
    function addSendResponse($order_id, $internal_id, $variableSymbol);
    
}

/**
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
interface IOrderStatus {

    /**
     * Vrátí stav objednávky v obchodě.
     * 
     * @param integer $id ID objednávky
     * @param integer $status ktuální stav objednávky (čísleník)
     * @return IOrderStatus
     */
    public function addStatus($id, $status);
}

/**
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
interface IPaymentDelivery {
    
    /**
     * identifikace pobočky
     * 
     * @param integer $id typ pobočky / výdejního místa
     * @param integer $type ID pobočky / výdejního místa
     * @return IPaymentDelivery
     */
    function addStore($id, $type);
    
    /**
     * doprava
     * 
     * @param integer $id
     * @param integer $type
     * @param string $name
     * @param strign $price
     * @param string $description
     * 
     * @return IPaymentDelivery
     */
     function addTransport($id, $type, $name, $price, $description);    
}

/**
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
interface IPaymentStatus {
    
    /**
     * stav platby
     * 
     * @param boolean $status  	zda se povedlo nastavit stav
     * @return IPaymentStatus
     */
    function addStatusResponse($status);
    
}

/**
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
interface IProductsAvailability {
    
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
    function addParam($id, $type, $name, $unit);
    
    /**
     * související položky k produktu
     * (Jedná se o nějakou přidanou hodnotu k produktu, která nemá vliv na cenu.) 
     * 
     * @param string $title
     * @return IProductsAvailability
     */
    public function addRelated($title);  
}

/**
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
interface IProductsAvailabilityValues {
    
    /**
     * 
     * @param integer $id
     * @param string $default
     * @param string $value
     * @param string $price
     */    
    function addValue($id, $default, $value, $price);
    
    
}
