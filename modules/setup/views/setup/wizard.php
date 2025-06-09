<?php defined('SYSPATH') OR die('No direct access allowed.');
//echo Debug::vars('2', $tableList); //exit;
//echo Debug::vars('3', $tableListCheck); //exit;
//echo Debug::vars('4', $procedureList); //exit;
//echo Debug::vars('5', $procedureListCheck); //exit;
?>
<script type="text/javascript">
    $(function() {
        $("#tablesorter_ge").tablesorter({sortList:[[0,0]], headers: {}});
    });
</script>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Настройка СКУД для работы парковочной системы');?></h3>
    </div>
    <div class="panel-body">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo __('Категории доступа');?></h3>
            </div>
            <div class="panel-body">
                <table id="tablesorter_ge3" class="table table-striped table-hover table-condensed tablesorter">
                    <thead>
                    <tr>
                        <th><?echo __('№ п/п');?></th>
                        <th><?echo __('Парковочная площадка');?></th>
                        <th><?echo __('Наличие в БД СКУД.');?></th>
                        <th><?echo __('Время события');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=0;
                    //получить список парковочных площадок. Их названия будут переданы в СКУД как категории доступа
                    $list=Model::factory('ParkingPlace')->get_list();

                    foreach($list as $key=>$value)
                    {
                        $parking=new Parking(Arr::get($value, 'ID'));

                        echo '<tr>';
                        echo '<td>'.++$i.'</td>';

                        echo '<td>'.iconv('windows-1251','UTF-8', $parking->name).'</td>';

                        echo '<td>';
                        echo !Model_wizard::checkAccessNameIsPresent(iconv('windows-1251','UTF-8', $parking->name))? HTML::image('static/images/green-check.png', array('alt' => 'true')) : 'false';
                        echo '</td>';

                        echo '<td>';
                        echo Form::open('wizard/addAccessname');
                        echo Form::hidden('name', iconv('windows-1251','UTF-8', $parking->name));
                        echo Form::button('addAccessname2', 'Добавить категорию доступа в СКУД', array('value'=>$parking->name));
                        echo Form::close();
                        echo '</td>';

                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <?php
                echo Form::button('addAccessnameAll', 'Добавить категории доступа', array('value'=>23));
                ?>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo __('Шкафы управления');?></h3>
            </div>
            <div class="panel-body">
                <table id="tablesorter_ge4" class="table table-striped table-hover table-condensed tablesorter">
                    <thead>
                    <tr>
                        <th><?echo __('№ п/п');?></th>
                        <th><?echo __('Шкаф управления');?></th>
                        <th><?echo __('IP.');?></th>
                        <th><?echo __('TCP PORT');?></th>
                        <th><?echo __('add');?></th>
                        <th><?echo __('del');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=0;
                    //список шкафов управления
                    $boxCount=3;
                    $lic=array('56', '57', '58');

                    for($n=0; $n<count($lic); $n++)
                    {
                        echo '<tr>';
                        echo '<td>'.++$i.'</td>';

                        echo '<td>'.'Шкаф '.Arr::get($lic, $n).'</td>';

                        echo '<td>'.Form::input('ip').'</td>';
                        echo '<td>'.Form::input('port').'</td>';

                        echo '<td>'.Form::button('addProcedure', 'Добавить шкаф', array('value'=>$n)).'</td>';
                        echo '<td>'.Form::button('delProcedure', 'Удалить шкаф', array('value'=>$n)).'</td>';

                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <?php
                echo Form::open('setup/addControlBox');
                echo Form::button('addControlBox', 'Добавить шкафы управления', array('value'=>23));
                echo Form::close();
                ?>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo __('Транспортный сервер'); ?></h3>
            </div>
            <div class="panel-body">
                <table id="tablesorter_ge5" class="table table-striped table-hover table-condensed tablesorter">
                    <thead>
                    <tr>
                        <th><?php echo __('№ п/п'); ?></th>
                        <th><?php echo __('ID сервера'); ?></th>
                        <th><?php echo __('ID БД'); ?></th>
                        <th><?php echo __('Имя сервера'); ?></th>
                        <th><?php echo __('IP'); ?></th>
                        <th><?php echo __('Порт'); ?></th>
                        <th><?php echo __('Активность'); ?></th>
                        <th><?php echo __('Редактировать'); ?></th>
                        <th><?php echo __('Удалить'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $servers = Model::factory('Parkdb')->getServers();
                    foreach ($servers as $server) {
                        echo '<tr>';
                        echo '<td>'.++$i.'</td>';
                        echo '<td>'.htmlspecialchars($server['ID_SERVER']).'</td>';
                        echo '<td>'.htmlspecialchars($server['ID_DB']).'</td>';
                        echo '<td>'.htmlspecialchars(iconv('windows-1251', 'UTF-8', $server['NAME'])).'</td>';
                        echo '<td>'.htmlspecialchars(long2ip($server['IP'])).'</td>';
                        echo '<td>'.htmlspecialchars($server['PORT']).'</td>';
                        echo '<td>'.($server['ACTIVE'] ? 'Да' : 'Нет').'</td>';
                        echo '<td>';
                        echo Form::open('wizard/editServer/' . $server['ID_SERVER']);
                        echo Form::button('editServer', 'Редактировать сервер', array('value' => $server['ID_SERVER']));
                        echo Form::close();
                        echo '</td>';
                        echo '<td>';
                        echo Form::open('wizard/deleteServer/' . $server['ID_SERVER']);
                        echo Form::button('deleteServer', 'Удалить сервер', array(
                            'value' => $server['ID_SERVER'],
                            'onclick' => 'return confirm(\'Вы уверены, что хотите удалить сервер?\')'
                        ));
                        echo Form::close();
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <?php
                echo Form::open('wizard/addServer');
                echo Form::button('addServer', 'Добавить сервер', array('value' => 'add'));
                echo Form::close();
                ?>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo __('Устройства'); ?></h3>
            </div>
            <div class="panel-body">
                <table id="tablesorter_ge6" class="table table-striped table-hover table-condensed tablesorter">
                    <thead>
                    <tr>
                        <th><?php echo __('№ п/п'); ?></th>
                        <th><?php echo __('ID устройства'); ?></th>
                        <th><?php echo __('ID сервера'); ?></th>
                        <th><?php echo __('Сетевой адрес'); ?></th>
                        <th><?php echo __('Название'); ?></th>
                        <th><?php echo __('Активность'); ?></th>
                        <th><?php echo __('Редактировать'); ?></th>
                        <th><?php echo __('Удалить'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $devices = Model::factory('Parkdb')->getDevices();
                    foreach ($devices as $device) {
                        echo '<tr>';
                        echo '<td>' . ++$i . '</td>';
                        echo '<td>' . htmlspecialchars($device['ID_DEV']) . '</td>';
                        echo '<td>' . htmlspecialchars($device['ID_SERVER']) . '</td>';
                        echo '<td>' . htmlspecialchars(isset($device['NETADDR']) ? iconv('windows-1251', 'UTF-8', $device['NETADDR']) : '') . '</td>';
                        echo '<td>' . htmlspecialchars(isset($device['NAME']) ? iconv('windows-1251', 'UTF-8', $device['NAME']) : '') . '</td>';
                        echo '<td>' . (isset($device['ACTIVE']) && $device['ACTIVE'] ? 'Да' : 'Нет') . '</td>';
                        echo '<td>';
                        echo Form::open('wizard/editDevice/' . (isset($device['ID_DEV']) ? $device['ID_DEV'] : ''));
                        echo Form::button('editDevice', 'Редактировать устройство', array('value' => (isset($device['ID_DEV']) ? $device['ID_DEV'] : '')));
                        echo Form::close();
                        echo '</td>';
                        echo '<td>';
                        echo Form::open('wizard/deleteDevice/' . (isset($device['ID_DEV']) ? $device['ID_DEV'] : ''));
                        echo Form::button('deleteDevice', 'Удалить устройство', array(
                            'value' => (isset($device['ID_DEV']) ? $device['ID_DEV'] : ''),
                            'onclick' => 'return confirm(\'Вы уверены, что хотите удалить устройство?\')'
                        ));
                        echo Form::close();
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <?php
                echo Form::open('wizard/addDevice');
                echo Form::button('addDevice', 'Добавить устройство', array('value' => 'add'));
                echo Form::close();
                ?>
            </div>
        </div>
    </div>
</div>