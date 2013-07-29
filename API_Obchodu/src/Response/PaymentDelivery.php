<?php
/**
 * Description of PaymentDelivery
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class PaymentDelivery extends Response implements IPaymentDelivery {

    /** @var integer */
    private $_transportKey = -1;    
    
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
    public function addTransport($id, $type, $name, $price, $description)
    {
        $this->_transportKey = $this->_transportKey + 1;
        
        $this->_parameters['transport'][$this->_transportKey] = array(
                'id'            => (integer) $id,
                'type'          => (integer) $type,
                'name'          => (string) $name,
                'price'         => (float) $price,
                'description'   => (string) $description
        );
        
        return $this;
    }
    
    /**
     * identifikace pobočky
     * 
     * @param integer $id typ pobočky / výdejního místa
     * @param integer $type ID pobočky / výdejního místa
     * 
     * @return IPaymentDelivery
     */    
    public function addStore($id, $type)
    {
        $this->_parameters['transport'][$this->_transportKey]['store'] = array(
                'id'    => (integer) $id,
                'type'  => (integer) $type,
        );
    }
    
    /**
     * platba
     * 
     * @param integer $id
     * @param type $type
     * @param name $name
     * @param price $price
     */
    public function addPayment($id, $type, $name, $price)
    {
        $this->_parameters['payment'][] = array(
                'id'            => (integer) $id,
                'type'          => (integer) $type,
                'name'          => (string) $name,
                'price'         => (float) $price,
        );
    }
    
    /**
     * pole vazeb mezi dopravou a platbou
     * 
     * @param integer $id
     * @param integer $transportId
     * @param integer $pymentId
     */
    public function addBinding($id, $transportId, $pymentId)
    {
        $this->_parameters['binding'][] = array(
                'id'            => (integer) $id,
                'transportId'   => (integer) $transportId,
                'paymentId'     => (integer) $pymentId,
        );
    }
}
