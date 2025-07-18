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