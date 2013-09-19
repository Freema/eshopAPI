<?php
namespace AukroAPI;

use stdClass;
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
    
    /** @var AukroApiResult */
    protected $_form_fields;
            
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
    
    protected function getFormFields($cat_id)
    {
        if(!isset($this->_form_fields))
        {
            $this->_form_fields = $this->_client->sellFormFields($cat_id);
            return $this->_form_fields;
        }
        else
        {
            return $this->_form_fields;
        }
    }


    /**
     * Helper method to create API form
     * 
     * @param array $params
     * @return array
     */
    public function buildFormFields(array $params)
    {
        if(isset($params['cat_id']))
        {
            $data = $this->getFormFields($params['cat_id']);
        }
        else
        {
            throw new Exception('Param cat_id is not isset!');
        }
        
        foreach ($data->sell_form_fields_list as $value)
        {
            $value = (array) $value;
            switch ($value['sell-form-type']) {
                case 1:
                    $form['form_id_' . $value['sell-form-id']] = array (
                        'field' => 'input',
                        'type'  => 'text',
                    );
                    break;
                case 2:
                case 3:
                    $form['form_id_' . $value['sell-form-id']] = array (
                        'field' => 'input',
                        'type'  => 'text',
                        'rule'  => array (
                            'integer'   => 'integer',
                            'range'     => array (
                                        'min'   => $value['sell-min-value'],
                                        'max'   => $value['sell-max-value'],
                            ),
                        ),
                    );
                    break;
                case 4:
                    $form_desc = (array) explode('|', $value['sell-form-desc']);
                    $form_opts = (array) explode('|', $value['sell-form-opts-values']);
                    $data = array_combine($form_opts, $form_desc);
                    $default = array_search($value['sell-form-opts-values'], $data);
                    $form['form_id_' . $value['sell-form-id']] = array (
                        'field'     => 'select',
                        'options'   => $data,
                        'default'   => $default,
                    );
                    break;
                case 5:
                    $form_desc = (array) explode('|', $value['sell-form-desc']);
                    $form_opts = (array) explode('|', $value['sell-form-opts-values']);
                    $data = array_combine($form_opts, $form_desc);
                    $default = array_search($value['sell-form-opts-values'], $data);
                    $form['form_id_' . $value['sell-form-id']] = array (
                        'field'     => 'input',
                        'type'      => 'radio',
                        'options'   => $data,
                        'default'   => $default,
                    );
                    break;
                case 6:
                    $form['form_id_' . $value['sell-form-id']] = array (
                        'field'     => 'input',
                        'type'      => 'checkbox',
                    );

                    break;
                case 7:
                    $form['form_id_' . $value['sell-form-id']] = array (
                        'field'     => 'input',
                        'type'      => 'file',
                        'rule'      => 'file',
                    );
                    break;
                case 8:
                    $form['form_id_' . $value['sell-form-id']] = array (
                        'field'     => 'textarea',
                    );
                    break;
                case 9:
                case 13:
                    $form['form_id_' . $value['sell-form-id']] = array (
                        'field' => 'input',
                        'type'  => 'text',
                    );
                    break;
            }
            $form['form_id_' . $value['sell-form-id']]['label'] = $value['sell-form-title'];
            
            switch ($value['sell-form-id']) {
                case 2:
                    unset($form['form_id_' . $value['sell-form-id']]['label']);
                    $form['form_id_' . $value['sell-form-id']]['type'] = 'hidden';
                    $form['form_id_' . $value['sell-form-id']]['value'] = $params['cat_id'];
            }
        }
        
        return $form;
    }    
    
    public function sendNewAuction($form)
    {
        if(!is_array($form))
        {
            throw new Exception('Argument must be array.');
        }
        if(!isset($form['form_id_2']))
        {
            throw new \AukroAPI('Neznama kategorie');
        }
        $data = $this->getFormFields($form['form_id_2']);
        
        $fields_arr = array();
        $empty = new stdClass();
        $empty->{'fid'} = 0;
        $empty->{'fvalue-string'} = '';
        $empty->{'fvalue-int'} = 0;
        $empty->{'fvalue-float'} = (float) 0;
        $empty->{'fvalue-image'} = 0;
        $empty->{'fvalue-datetime'} = 0;
        $empty->{'fvalue-date'} = 0;
        $empty->{'fvalue-range-int'} = 0;
        $empty->{'fvalue-range-float'} = 0;
        $empty->{'fvalue-range-date'} = 0;
        
        foreach ($data->sell_form_fields_list as $value)
        {
            $value = (array) $value;
            $id = $value['sell-form-id'];
            if(isset($form['form_id_' . $id]))
            {
                $field = clone $empty;
                $field->{'fid'} = $id;

                switch ($value['sell-form-res-type']) {
                    case 1:
                        $field->{'fvalue-string'} =  $form['form_id_' . $id];
                        break;
                    case 2:
                        $field->{'fvalue-int'} =  $form['form_id_' . $id];
                        break;
                    case 3:
                        $field->{'fvalue-float'} =  $form['form_id_' . $id];
                        break;
                    case 7:
                        $field->{'fvalue-image'} =  $form['form_id_' . $id];
                        break;
                    case 9:
                        $field->{'fvalue-datetime'} =  $form['form_id_' . $id];
                        break;
                    case 13:
                        $field->{'fvalue-date'} =  $form['form_id_' . $id];
                        break;
                    }
                
                $fields_arr[] = (array) $field; 
            }
        }
        
        return $fields_arr;
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