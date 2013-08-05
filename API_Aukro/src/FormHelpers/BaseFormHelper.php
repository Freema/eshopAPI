<?php
namespace AukroAPI;
/**
 * Description of AukroApiEvent
 * Events that needs a login to the application
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class BaseFormHelper {
   
    /** @var Api */
    protected $_client;
    
    /** @var array */
    private $_tree = array();
   
    function __construct(Api $client) {
        $this->_client = $client;
    }    
    
    final function cartDataSelectBox() {
        $data = $this->_client->cartData();
        
        $nodes = array();
        $tree = array();
        foreach ($data->cats_list as &$node)
        {
            $node = (array) $node;
            $node['children'] = array();
            $id = $node['cat-id'];
            $parent_id = $node['cat-parent'];
            $nodes[$id] = &$node;
            if(array_key_exists($parent_id, $nodes)) {
                $nodes[$parent_id]['children'][] = &$node;
            }  else {
                $tree[] = &$node;
            }            
        }
        $this->buildTree($tree);        
        return $this->_tree;
    }

    protected function buildTree(array $values, $name = null)
    {
        foreach ($values as $value)
        {
            if(empty($value['children']))
            {
                $this->_tree[$name][$value['cat-id']] = $value['cat-name'];
            }
            else
            {
                $name = $this->buildTree($value['children'], $value['cat-name']);
            }
        }
        return $name;
    }
}