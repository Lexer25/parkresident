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