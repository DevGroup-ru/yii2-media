<?php

/** @var \yii\web\View $this */
/** @var DynamicModel $model */

use DevGroup\Media\widgets\AttachmentWidget;
use yii\base\DynamicModel;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row">
    <div class="col-sm-6">
<?php
$form = ActiveForm::begin();

echo $form->field($model, 'files')->widget(AttachmentWidget::className());
echo '<hr>' . Html::submitButton('Submit', ['class'=>'btn btn-primary']);
ActiveForm::end();
?>
    </div>
    <div class="col-sm-6">
        <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur dignissimos earum error excepturi,
            neque nostrum pariatur repudiandae suscipit. Accusamus culpa eum fuga hic necessitatibus nemo nihil quo quod
            tenetur, veniam.
        </div>
        <div>Adipisci, alias aliquam blanditiis dolores eligendi facilis inventore labore maiores nemo nesciunt nisi
            nulla obcaecati, quod, repellat repellendus reprehenderit tempora temporibus. Architecto dignissimos
            doloremque esse fugit odio pariatur sit. Distinctio!
        </div>
        <div>Amet animi assumenda consequatur, dolorem doloribus eaque enim exercitationem fugiat porro possimus rem
            repellat sapiente sint? Consequuntur culpa deserunt laudantium maiores vitae. Cum deserunt eaque et optio
            quidem quo ratione!
        </div>
        <div>Aliquam assumenda cum cumque hic inventore ipsa labore nemo odio, officia qui ratione sed sunt voluptate? A
            accusantium aspernatur beatae, dignissimos error et facilis libero molestias repellat temporibus. Atque,
            repellendus.
        </div>
    </div>
</div>
