<?php
/**
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class PaymentStatus extends Response implements IPaymentStatus {
    
    /**
     * stav platby
     * 
     * @param boolean $status
     * @return IOrderCancel
     */
    function addStatusResponse($status)
    {
        $this->_parameters = array(
            "status"    => (bool) $status,
        );
        
        return $this;
    }    
}
