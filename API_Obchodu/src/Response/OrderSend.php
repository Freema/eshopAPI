<?php
/**
 * Odeslání objednávky do obchodu. 
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class OrderSend extends Response implements IOrderSend {

    /**
     * Odeslání objednávky do obchodu. 
     * 
     * @param integer $order_id číslo objednávky
     * @param string $internal_id interní číslo objednávky v obchodu (typicky to které uvádíte zákazníkovi na faktuře) 
     * @param string $variableSymbol variabilní symbol (bez nevyýznamných nul, max. 10 čísel), bude sloužit ke spárování plateb při vybíjení kreditu 
     * @return IOrderSend
     */    
    function addSendResponse($order_id, $internal_id, $variableSymbol)
    {
        $this->_parameters = array(
            "order_id"          => $order_id,
            "internal_id"       => $internal_id,
            "variableSymbol"    => $variableSymbol,
        );
    }
}

