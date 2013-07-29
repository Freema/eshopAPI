<?php
/**
 * Nastavení objednávky na storno
 * Storno objednávky je prováděno jen výjimečně, tak aby nedocházelo k problémům při expedici. 
 * 
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class OrderCancel extends Response implements IOrderCancel {
    
    /**
     * true došlo ke stornu, jinak false
     * 
     * @param boolean $status
     * @return IOrderCancel
     */
    function addCancelResponse($status)
    {
        $this->_parameters = array(
            "status"    => (bool) $status,
        );
        
        return $this;
    }    
}
