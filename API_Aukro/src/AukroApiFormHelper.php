<?php
namespace AukroAPI;

/**
 * Description of AukroApiEvent
 * Events that needs a login to the application
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class FormHelper implements IFormHelper {
   
    /** @var Api */
    private $_client;
    
    /**
     * @param array $loginInformation
     * @param integer $apiVersionKey
     */
    function __construct(Api $client) {
        
        $this->_client = $client;
    }
    
    final function cartDataSelectBox() {
        $data = $this->_client->cartData();
        
        $nodes = array();
        $tree = array();
        foreach ($data->cats_list as &$node)
        {
            $node->children = array();
            $id = $node->{'cat-id'};
            $parent_id = $node->{'cat-parent'};

            $nodes[$id] =& $node;
            if(array_key_exists($parent_id, $nodes)) {
                $nodes[$parent_id]->children[] = &$node;
            }  else {
                $tree[] = &$node;
            }
        }
        
        return $tree;
    }
}