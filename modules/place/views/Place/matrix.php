<?php
//http://itchief.ru/lessons/bootstrap-3/30-bootstrap-3-tables;
// страница отображения данных по машноместам
echo Debug::vars('3', $id_place);
$_parking=new Parking(Arr::get(Arr::flatten($id_place), 'ID'));//информация о парковочной площадке
$placeList=Model::factory('Place')->getChild($_parking->id);//список машиномест на этой парковочной площадке

$titleAddPlace=__('Регистрация машиноместа для парковочной площадки ":name". Зарегистрировано :regPlace. Количество мест на площадке :countPlace',
    array(
        ':name'=>iconv('windows-1251','UTF-8',$_parking->name),
        ':regPlace'=> count($placeList),
        ':countPlace'=>$_parking->count
    ));
    
$title=__('Список машиномест для парковочной площадки ":name". Зарегистрировано :regPlace. Количество мест на площадке :countPlace',
    array(
        ':name'=>iconv('windows-1251','UTF-8',$_parking->name),
        ':regPlace'=> count($placeList),
        ':countPlace'=>$_parking->count
    ));
?>
<script type="text/javascript">
$(function() {
    $("#tablesorter").tablesorter({
        sortList: [[0,0]], 
        headers: { 
            0: {sorter: false}
        }
    });
});

function initGarageHighlight() {
    $("[class*='garage-']").hover(
        function() {
            var garageClass = $(this).attr("class").match(/garage-(\d+)/);
            if (garageClass) {
                $("." + garageClass[0]).addClass("highlight");
            }
        },
        function() {
            $("[class*='garage-']").removeClass("highlight");
        }
    );
}
$(document).ready(function() {
    initGarageHighlight();
});
</script>

<style>
.highlight {
    background-color: #ffeb3b !important;
    opacity: 0.9;
    transition: background-color 0.3s ease;
    transform: scale(1.05);
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    z-index: 10;
    position: relative;
}
</style>

<?php
if(Auth::Instance()->logged_in() and false) {
    echo Form::open('place/control');    
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $titleAddPlace;?></h3>
        </div>
        <div class="panel-body">
            <div id="my-alert" class="alert alert-success alert-dismissible" role="alert">
                <?php 
                    //echo 'Номер парковочного места вводится как длинное десятичное число: 000123456789.<br>Остальные варианты ввода будут игнорироваться.';
                ?>
            </div>
            <?php
            echo __('Регистрация парковочного места').'<br>';
            echo Form::input('place','', array('placeholder'=>'Номер машиноместа','minlength '=>1,'maxlength  '=>5, 'required'=>'required')).'<br>';
            echo Form::hidden('parking', $_parking->id);
            echo Form::button('todo', 'Зарегистрировать новое машиноместо', array('value'=>'editMatrix','class'=>'btn btn-success', 'type' => 'submit'));    
            ?>    
        </div>
    </div>
    <?php
}

echo Form::open('place/control');
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo $title;?></h3>
    </div>
    <div class="panel-body">
        <?php
            echo __('Список машиномест');
            echo 'Матричное представление парковочного пространства для парковки "'. iconv('windows-1251','UTF-8', $_parking->name).'" ('.$_parking->id.')';
        ?>
        <table id="tablesorter" class="table table-striped table-hover table-condensed tablesorter">
        <thead>
        </thead>
        <tbody>
        <?php
        $countTotal=$_parking->count;//общее количество мест
        $rowMax=5;//количество строк для показа всех машиномест
        $currentPlace=1;//номер машиноместа, по которому выводится информация.
        $rowCount=10;//количество машиномест в строке
        $y=0;
        while($currentPlace<$countTotal) {
            echo '<tr>';
            for($x=1; $x<$rowCount+1;$x++) {
                if($currentPlace<$countTotal+1) {
                    $_place = new Place();
                    $_place->getFromNumberPlaceAndIdParking($currentPlace, $_parking->id);
                    $_mess='';
                    if($_place->placenumber>0) {
                        $_class='success';//желтый
                        echo '<td class="'.$_class.' garage-'.$_place->id_garage.'">';    
                        //echo '123<br>';    
                        //echo Debug::vars($_place->id_garage);
                        echo '<div class='.$_place->id_garage.'>';
                            echo $_place->id_garage;
                        echo $currentPlace.' '.$_mess;
                        echo Form::open('place/control');
                            echo Form::hidden('id', $_place->id);
                            echo Form::submit('todo', 'edit');
                            echo Form::submit('todo', 'del');
                        echo Form::close();
                    } else {
                        $_class='active';//серый
                        echo '<td class="'.$_class.'">';        
                        echo $currentPlace.' '.$_mess;
                        echo Form::open('place/control');
                            echo Form::hidden('place', $currentPlace);
                            echo Form::hidden('parking', $_parking->id);
                            echo Form::submit('todo', 'add');
                        echo Form::close();
                    }
                    echo '</td>';
                }
                $currentPlace++;
            }
            echo '</tr>';
        }
        ?>
        </tbody>
        </table>
    </div>
</div>