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