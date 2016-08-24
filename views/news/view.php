<?php

use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render('//site/partials/common/_admin_heading.php', [
    'title' => $this->title,
    'menu' => Yii::$app->user->can('news:pAdmin') ? [
        ['label' => 'News Page', 'url' => ['news/index'] ],
        ['label' => 'News Admin', 'url' => ['news/admin'] ],
        ['label' => 'Update this news', 'url' => ['news/update', 'id' => $model->id, 'name' => $model->slug] ],
    ] : [],
]);
?>
<div class="container style_external_links">
    <div class="row">
        <div class="content news-content">

            <div class="row">
                <div class="col-md-9">

                    <?php if (Yii::$app->user->can('news:pAdmin')) {

                        echo \yii\bootstrap\Alert::widget([
                            'body' =>
                                '<strong>News Status: </strong>' . Html::encode(\app\models\News::getStatusList()[$model->status])
                                . ($model->status != \app\models\News::STATUS_PUBLISHED ? ' &mdash; This is a preview, not visibile to non-admins.' : ''),
                            'options' => ['class' => 'alert-info'],
                            'closeButton' => false,
                        ]);

                    } ?>

                    <span class="date"><?= Yii::$app->formatter->asDate($model->news_date) ?></span>
                    <div class="text">


                        <?= Markdown::process($model->content, 'gfm') ?>

                    </div>
                </div>
                <div class="col-md-3">

                    <?php if (Yii::$app->user->can('news:pAdmin')): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Admin Info
                            </div>
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'id',
                                    'slug',
                                    'created_at:datetime',
                                    'creator_id',
                                    'updated_at:datetime',
                                    'updater_id',
                                ]
                            ]) ?>
                        </div>

                    <?php endif; ?>

                    <h2>Related News</h2>

                    <ul>
                    <?php foreach($model->relatedNews as $news) {
                        echo '<li>' . Html::a(
                            \yii\helpers\StringHelper::truncate($news->title, 64),
                            ['news/view', 'id' => $news->id, 'name' => $news->slug]
                        ). '</li>';
                    }
                    ?>
                    </ul>

                    <?= \app\widgets\NewsTaglist::widget(['news' => $model]) ?>

                    <?= \app\widgets\NewsArchive::widget() ?>

                </div>
            </div>

        </div>
    </div>
</div>
