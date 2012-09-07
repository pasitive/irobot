<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denisboldinov
 * Date: 9/29/11
 * Time: 12:48 PM
 * To change this template use File | Settings | File Templates.
 */

class IndexAction extends CAction
{
    /**
     * @var CActiveRecord
     */
    public $model;

    /**
     * @var array
     */
    public $data = array();

    /**
     * @var string
     */
    public $view = 'index';

    public function run()
    {
        if (is_null($this->model)) {
            throw new CException(Yii::t('yii', 'Property "{class}.{property}" is not defined.',
                                        array('{class}' => get_class($this), '{property}' => 'model')));
        }

        $this->model->setScenario('search');
        $this->model->unsetAttributes();
        //$this->model->pagination->pageSize = 999;

        $this->model->attributes = Yii::app()->request->getQuery(get_class($this->model), array());

        $data = array();
        foreach ($this->data as $key => $var) {
            // Если элемент массива является валидной callback-функцией выполняем функция, получая значение.
            if (is_string($key) && is_callable($var)) {
                $data[$key] = call_user_func($var, $this);
            } elseif (is_string($var) && method_exists($this, 'get' . ucfirst($var))) {
                $data[is_string($key) ? $key : $var] = call_user_func(array(
                                                                           $this,
                                                                           'get' . ucfirst($var)
                                                                      ));
            }
        }
        $this->getController()->render($this->view, $data);
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getModel()
    {
        return $this->model;
    }
}
