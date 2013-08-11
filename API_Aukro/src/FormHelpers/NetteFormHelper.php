<?php
namespace AukroAPI;

use Nette\Forms\Form;
/**
 * Description of AukroApiEvent
 * Events that needs a login to the application
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

class NetteFormHelper extends BaseFormHelper implements IFormHelper {
    
    /** @var Form */
    private $_formContainer;
    
    /**
    * @param array $loginInformation
    * @param integer $apiVersionKey
    */
    function __construct(Api $client) {
        parent::__construct($client);
        $this->_formContainer = new Form;
    }
    
    /**
     * nette form container
     * 
     * @param Form $form
     */
    public function setFormContainer($form)
    {
        $this->_formContainer = $form;
    }
    
    /**
     * Helper method to create API form
     * 
     * @param array $params
     * @return Form
     */
    public function buildFormFields(array $params)
    {
        if(isset($params['cat_id']))
        {
            $data = $this->_client->sellFormFields($params['cat_id']);
        }
        else
        {
            throw new Exception('Param cat_id is not isset!');
        }
        if(isset($params['method']))
        {
            $this->_formContainer->setMethod($params['method']);
        }
        else
        {
            throw new Exception('Param method is not isset!');
        }
        if(isset($params['action']))
        {
            $this->_formContainer->setAction($params['action']);
        }
        else
        {
            throw new Exception('Param action is not isset!');
        }

        foreach ($data->sell_form_fields_list as $value)
        {
            $value = (array) $value;
            $form = $this->_formContainer;
            
            switch ($value['sell-form-type']) {
                case 1:
                    $container = $form  ->addText('form_id_' . $value['sell-form-id'], $value['sell-form-title']);
                    break;
                case 2:
                case 3:
    
                    $container = $form  ->addText('form_id_' . $value['sell-form-id'], $value['sell-form-title'])
                                        ->addRule(Form::INTEGER, NULL)
                                        ->addRule(Form::RANGE, NULL, array($value['sell-min-value'],$value['sell-max-value']));
                    break;
                case 4:
                    $form_desc = (array) explode('|', $value['sell-form-desc']);
                    $form_opts = (array) explode('|', $value['sell-form-opts-values']);
                    $data = array_combine($form_opts, $form_desc);
                    $defaults = array_search($value['sell-form-opts-values'], $data);
                    
                    $container = $form  ->addSelect('form_id_' . $value['sell-form-id'], $value['sell-form-title'], $data)
                                        ->setDefaultValue($defaults);
                    break;
                case 5:
                    $form_desc = (array) explode('|', $value['sell-form-desc']);
                    $form_opts = (array) explode('|', $value['sell-form-opts-values']);
                    $data = array_combine($form_opts, $form_desc);
                    $defaults = array_search($value['sell-form-opts-values'], $data);
                    $container = $form  ->addRadioList('form_id_' . $value['sell-form-id'], $value['sell-form-title'], $data, $data)
                                        ->setDefaultValue($defaults);;    
                    break;
                case 6:
                    $container = $form  ->addCheckbox('form_id_' . $value['sell-form-id'], $value['sell-form-title']);
                    break;
                case 7:
                    $container = $form  ->addUpload('form_id_' . $value['sell-form-id'], $value['sell-form-title'])
                                        ->addRule(Form::IMAGE, NULL);
                    break;
                case 8:
                    $container = $form  ->addTextArea('form_id_' . $value['sell-form-id'], $value['sell-form-title']);
                    break;
                case 9:
                case 13:
                    $container = $form  ->addText('form_id_' . $value['sell-form-id'], $value['sell-form-title']);
                    break;
            }
            
            switch ($value['sell-form-id']) {
                case 2:
                    $container->setDefaultValue($params['cat_id'])
                              ->setDisabled(true);
            }
        }
        
        $this->_formContainer->addSubmit('add', 'Odeslat')->setAttribute('class', 'btn btn-success');;
        
        return $form = $this->_formContainer;
    }
}