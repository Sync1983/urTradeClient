<?php
/* @var $this yii\web\View */
/* @var $model app\models\SearchForm */
\app\assets\TableAsset::register($this);
?>
<script>
  var articul = "<?= $model->articul ?>";
  var part_url= "<?= yii\helpers\Url::to(['site/parts'])?>";
</script>
<div class="panel panel-default panel-full-screen">
  <div class="panel-heading">
    <h3 class="panel-title">Выберите производителя артикула <b><?= $model->articul ?></b> 
      <?= kartik\checkbox\CheckboxX::widget([
            'id'  => 'analog',
            'name'  => 'analog',
            'value' => $model->analog,
            'pluginOptions'=>[
              'threeState'=>false,
            ],
            'pluginEvents' => [
              "change"=>"function() { "
              . "var analog = $('#analog').val();\n"
              . "console.log(analog);"              
              . "if( analog == 1 ) { \n $('tr[data-analog]').removeClass('hidden');\n }"
              . "else { \n $('tr[data-analog]').addClass('hidden');\n} \n}",              
            ]            
        ]);?> <label for="analog">Аналоги</label></h3>
  </div>
  <div class="panel-body">
    <div class="selector">      
      <div class="list-group">
        <?php foreach($makers as $maker=>$key): ?>
        <a href="#" class="list-group-item" data-name="<?= $maker ?>"><?= $maker ?><span class="glyphicon glyphicon-chevron-right" style="float: right;"></span></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="viewer">
      &nbsp;
    </div>
  </div>
</div>
<?php
$script = <<<SCRIPT
    var viewer = $('div.viewer');
    
    function loadParts(maker){
      var analog = $('#analog').val();
      $.ajax({
        url: part_url,
        method: "POST",
        data: {articul: articul,analog:analog,maker:maker},        
        beforeSend: function( xhr ) {
          $(viewer).html('<h4>Loading...</h4><div class="progress">' +
            '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">'+
              '<span class="sr-only"></span>'+
            '</div>'+
          '</div>');    
        }
      }).done(function(data){
        $(viewer).html(data);
        table = $("#parts").DataTable({
          paging: false,
          "order": [[ 0, 'asc' ], [ 3, 'asc' ]],
          language: {
            search: "Быстрый поиск:"
          }
        });
      });      
    };
    
    $('a[data-name]').each(function(index,item){
      $(item).click(function(event) {
        var target = event.currentTarget; 
        var name = $(target).attr('data-name');
        loadParts(name);
      });
    });
SCRIPT;
$this->registerJs($script);
