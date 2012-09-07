<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 01.03.11
 */

class SignatureFilter extends CFilter
{
    protected function preFilter($filterChain)
    {
        $rand = Yii::app()->request->getQuery('rand', null);
        $sig = Yii::app()->request->getQuery('sig', null);

        if (empty($rand)) {
            throw new CHttpException(400, 'rand parameter is missing', Error::ERROR_INVALID_PARAM);
        }

        if (empty($sig)) {
            throw new CHttpException(400, 'sig parameter is missing', Error::ERROR_INVALID_PARAM);
        }

        if (!Signature::check($sig, $_GET)) {
            $errorMessage = 'Request failed. Invalid signature';
            if (YII_DEBUG) {
                $errorMessage .= ' [' . Signature::generate($_GET) . ']';
            }
            throw new CHttpException(400, $errorMessage, Error::ERROR_INVALID_SIGNATURE);
        }

        return true;
    }

    protected function postFilter($filterChain)
    {
        return true;
    }

}
