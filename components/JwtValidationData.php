<?php

namespace app\components;

class JwtValidationData extends \sizeg\jwt\JwtValidationData
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->validationData->setIssuer(\Yii::$app->params[ 'hostInfo' ]);
        $this->validationData->setAudience(\Yii::$app->params[ 'hostInfo' ]);
        $this->validationData->setId('4f1g23a12aa');

        parent::init();
    }
}