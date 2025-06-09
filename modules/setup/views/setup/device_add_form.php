<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Добавление устройства'); ?></h3>
    </div>
    <div class="panel-body">
        <?php echo Form::open('wizard/saveDevice'); ?>
        <div class="form-group">
            <label><?php echo __('Название'); ?></label>
            <?php echo Form::input('NAME', '', array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('ID сервера'); ?></label>
            <?php
            $server_options = array('' => 'Выберите сервер');
            foreach ($servers as $server) {
                $server_options[$server['ID_SERVER']] = iconv('windows-1251', 'UTF-8', $server['NAME']);
            }
            echo Form::select('ID_SERVER', $server_options, '', array('class' => 'form-control'));
            ?>
        </div>
        <div class="form-group">
            <label><?php echo __('Активность'); ?></label>
            <?php echo Form::select('ACTIVE', array('0' => 'Нет', '1' => 'Да'), '', array('class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <label><?php echo __('IP-адрес (ID_CTRL)'); ?></label>
            <?php echo Form::input('ID_CTRL', '', array('class' => 'form-control', 'placeholder' => 'xxx.xxx.xxx.xxx')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::button('saveDevice', 'Сохранить', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>