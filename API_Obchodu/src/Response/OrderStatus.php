<?php
/**
 * Description of OrderStatus
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class OrderStatus extends Response implements IOrderStatus {

    /**
     * Vrátí stav objednávky v obchodě.
     * 
     * @param integer $id ID objednávky
     * @param integer $status ktuální stav objednávky (čísleník)
     * @return IOrderStatus
     */

    public function addStatus($id, $status)
    {
        $this->_parameters = array(
            'order_id'  => $id,
            'status'    => $status,
        );
    }
}
