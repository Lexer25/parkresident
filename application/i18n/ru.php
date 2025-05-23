<?php
return array(
		'City'=>'Артонит Парк ЖК',
		'Load'=>'Контроллеры',
		'Load_order' => 'Очередь загрузки',
		'Show_all' => 'Показать все',
		'Show_error_only' => 'Только ошибки',
		'DB_connect_err' => 'Ошибка связи с базой данных СКУД:',
		'synctime_result' => 'Контроллер  <b>device_name</b>, время до синхронизации <b>time_befor</b>, время после синхронизации <b>time_after</b>, время выполнения during. сек.',
		'synctime_log_title' => 'Результат выполнения команды SyncTime',
		'command_time_start' => 'Время начала выполнения команды ',
		'command_time_end' => 'Время завершения выполнения команды ',
		'device_control' => 'Результат',
		'clear_key_result' => 'Контроллер  <b>:device_name</b>, точка прохода <b>:door_name</b>, количество карт до очистки <b>:keycount_befor</b>, результат выполнения команды <b>:result</b>, количество карт после очистки <b>:keycount_after</b>, время выполнения <b>:during</b> сек.',
		'clear_device_log_title' => 'Удалить карты из точки прохода',
		'load_card_result' => 'Загрузка <b>countKeyForLoad</b> карт в контроллер <b>:device_name</b>, точка прохода id_dev=key <b>:door_name</b>,  время выполнения <b>:during</b> сек.',
		'last_command'	=>	'Последние команды',
		'load_card_log_title'	=>	'Загрузить карты в точку прохода',
		'key_count'	=>	'Количество карт',
		'key_people'	=>	'Количество пользователей',
		'key_people_delete'	=>	'Количество удаленных пользователей',
		'ts_count'	=>	'Количество транспортный серверов ',
		'device_count'	=>	'Количество контроллеров ',
		'event_count_24'	=> 'Количество событий за последние 24 часа',
		'load_card'	=> 'Записать карты',
		'clear_device'	=> 'Очистить устройство',
		'settz'	=> 'Записать временные зоны',
		'settz_log_title'	=> 'Записать временные зоны',
		'settz_result'	=> 'Загрузка временных зон в контроллер <b>:device_name</b>, временная зона <b>:tz_number</b>, значение до установки <b>:tz_befor</b>, результ выполнения команды <b>:settz_result</b>, значение после установки <b>:tz_after</b>,  время выполнения :during',
		'events' 	=> 'События',
		'readkey_result'	=> 'Чтение :keycount карт из устройства :device_name, результат сохранен в файл <b>:file_name</b>, время выполнения <b>:during</b> сек. Найдено и поставлено в очередь на удаление :count_err карт.',
		'checkkey_result'	=> 'Чтение :keycount карт из устройства :device_name, результат сохранен в файл <b>:file_name</b>, время выполнения <b>:during</b> сек. Найдено и поставлено в очередь на запись :count_err карт.',
		'checkkey_result_equal'	=> 'Сравнение количества карт устройства :device_name. Количество карт в контроллере и в базе данных СКУД совпадает. Время выполнения <b>:during</b> сек. Записывать или удалеть карты не требуется.',
		'checkStatusOnLine_result'	=> 'Точка прохода: <b>:door_name</b> (id=:door_id),<br>обслуживается устройством: <b>:device_name</b> (id=:device_id),<br>reportstatus: <b>:reportstatus</b>,<br>version: <b>:version</b>,<br>карт в точке прохода: <b>:count_door</b>,<br> карт по базе данных: <b>:keyCountDB</b>',
		'checkStatusOnLine_title'	=> 'Чтение статуса on-line',
		'no_point_device_for_checkStatusOnLine'	=>	'Не указано устройство для получения данных',
		'UNLEGAL'	=>	'Не должны ходить в эту точку прохода',
		'LEGAL'	=>	'Имеют право ходить в эту точку прохода',
		'event_panel_title'	=>	'Статистика по событиям за последние 24 часа. Количество событий: count_event.',
		'loading_card_rfid'	=> 'Очередь RFID карт для загрузки в контроллеры',
		'overcount_card'	=> 'Карт с превышение попыток записи',
		'load_order_for_notactive'	=>	'Количество карт в очереди для неактивных устройств',
		'data_people_and_card'	=>	'1. Информация по сотрудникам и картам',
		'data_device'	=>	'2. Оборудование',
		'data_cardindev'	=>	'3. Очередь загрузок',
		'SERVER_NAME'	=>	'Имя сервера',
		'SERVER_IP'	=>	'IP сервера',
		'SERVER_PORT'	=>	'Порт сервера',
		'DEVICE_NAME'	=>	'Название контроллера<br>СКУД',
		'DEVICE_VERSION'	=>	'Версия контроллера<br>СКУД',
		'DOOR_NAME'	=>	'Название точки прохода',
		'BASE_COUNT'	=>	'Количество карт<br>по базе данных',
		'DEVICE_COUNT'	=>	'Количество карт<br>база данных/контроллер',
		'Comparekey'	=>	'Найти и удалить лишние карты',
		'checkStatus'	=>	'Записать текущего состояние в БД',
		'checkStatusOnLine'	=>	'Проверка состояния online',
		'Load_panel_title'	=>	'Управление очередью загрузки карт в контроллеры',
		'refusing_ok'	=>	'Отказ в проходе правомерный<br>Пользователь не должен ходить в эту точку прохода',
		'refusing_err'	=>	'Отказ в проходе ошибочный<br>Пользователь имеет право прохода в эту точку',
		'event_panel_desc'	=>	'Статистические данныее по проходам пользователей',
		'NAME'	=>	'Точка прохода',
		'SER_NUM'	=>	'Номер п/п',
		'event_stat'	=>	'4. События',
		'event_stat_for_dashboard'	=>	':name :count',
		'people'	=>	'Сотрудники',
		'log'	=>	'Журналы',
		'log_files'	=>	'Файлы журналов',
		'log1'	=>	'Журналы АСервера',
		'log2'	=>	'Журналы сравнения карт',
		'load_result_mess'	=>	'Дата загрузки карты в контроллер :LOAD_TIME,<br> результат :LOAD_RESULT , ячейка :DEVIDX',
		'result_analis_0'	=>	'Отказ в проходе правильный.<br>Карта не имеет права ходить в эту дверь.',
		'result_analis_1'	=>	'Отказ в проходе ошибочный',
		'DATE_EVENT'	=>	'Дата и время события',
		'NAME_EVENT'	=>	'Название события',
		'PEOPLE'	=>	'ФИО, ID, номер карты',
		'ANALIS'	=>	'Анализ отказа',
		'LOAD_RESULT'	=>	'Информация о загрузке карты в контроллер',
		'load_card_in_device'	=>	'Загружено карт в контроллеры',
		'load_card_in_device_with_error'	=>	'Из них с ошибками',
		'CARD_FOR_LOAD'	=>	'Количество карт<br>для загрузки в контроллеры',
		'CARD_FOR_DELETE'	=>	'Количество карт<br>для удаления из контроллеров',
		'result'	=>	'Результат выполнения команд',
		'no_data'	=>	'Нет данных',
		'ID_EVENTTYPE'	=>	'Код события',
		'COUNT_EVENT'	=>	'Количество событий',
		'people_panel_title'	=>	'Информация о сотрудниках',
		'pep_id'	=>	'ID пользователя',
		//'name'	=>	'ФИО пользователя',
		'org_name'	=>	'Организация, квартира',
		'card'	=>	'Номер карты',
		'tabmum'	=>	'Учетный (табельный) номер',
		'id_pep'	=>	'ID пользователя',
		'door'	=>	'Точка прохода',
		'load_result'	=>	'Результат загрузки карты в контроллер',
		'load_time'	=>	'Дата загрузки карты в контроллер',
		'timestamp'	=>	'Дата, время',
		'note'	=>	'Заметки',
		'event_name'	=>	'Событие',
		'COMMENT_LOAD'	=>	'Режим тест',
		'valid'	=>	'Данные актуальны. Дата последней загрузки в контроллер:',
		'no_valid'	=>	'Данные устарели.',
		'last_event'	=>	'Дата последнего события.',
		'No_event'	=>	'Нет событий.',
		'people_event_title'	=>	'События за период с :dateFrom по :dateTo',
		'old_count'	=>	'контроллеров было',
		'new_count'	=>	', стало',
		'system_events'	=>	'5. Выявлены изменения в системе за последние :stat_day_befor дней.',
		'check_panel_title'	=>	'Панель проверки оборудования.',
		'parameter_of_read_cell'	=>	'Параметры чтения карт.',
		'select_device'	=>	'Выбор транспортного сервера.',
		'parameter_of_write_cell'	=>	'Параметры записи данных в контроллер.',
		'check_key_result'	=>	'Проверка контроллера. Вычитка данных из устройства ":device_name" с ячейки :cellfrom по ячейку :cellto, результат сохранен в файл <b>:file_name</b>, время выполнения <b>:during</b> сек. Удалено :count_del карт.',
		'write_key_result'	=>	'Запись карт в устройств :device_name с ячейки :cellfrom по ячейку :cellto, результат сохранен в файл <b>:file_name</b>, время выполнения <b>:during</b> сек.',
		'parameter_of_test_device'	=>	'Работа с тестовым устройством.',
		'mess_about_test_device'	=>	'Внимание! Устройство для проверок должно иметь название <b> :device_name</b>.<br>В разделе :title_dev укажите Транспортный сервер, где находится устройство. В секции Device проверьте наличие тестируемого устройства.<br>Название тестового устройства определяется в файле конфигурации C:\xampp\htdocs\city\application\config\artonitcity_config.php <a href="file://C:\xampp\htdocs\city\application\config\artonitcity_config.php">config</a>',
		'check'	=>	'Тест',
		'getDeviceList'	=>	'Выбрать транспортный сервер',
		'read_data_from_device'	=>	'Прочитать данные из устройства',
		'write_data_to_device'	=>	'Запись данных в устройство',
		'note_fro_write_cell'	=>	'В указанный диапазон ячеек будет записан номер карты вида <номер ячейки>"001A"',
		'select_all'	=>	'Выбрать все',
		'DEVICE'	=>	'Контроллер',
		'SERVER'	=>	'Сервер',
		'ID_DEV'	=>	'ID точки прохода',
		'door_panel_title'	=>	'Информация о точке прохода (двери)',
		'door_panel_title'	=>	'Информация о точке прохода (двери)',
		'door_info'	=>	'Точка прохода id=:id_door,  :name, состояние :active.',
		'device_info'	=>	'Контроллер id=:id_dev, :name, состояние :active.',
		'server_info'	=>	'Транспортный сервер :name, состояние :active, IP адрес :ip, порт :port.',
		'door_info_title'	=>	'Информация о точке прохода',
		'name_order_for_load'	=>	':surname :name :patronymic',
		'list_card_for_load'	=>	'Очередь ФИО для загрузки (:count).',
		'list_card_for_delete'	=>	'Очередь ФИО для удаления (:count).',
		'count_attampt'	=>	'Количество попыток<br>записи/удаления',
		'date_set'	=>	'Карта поставлена в очередь',
		'del_queue_mess'	=>	'Внимание!\nОчередь загрузки для указанных контроллеров будет удалена.\nПодтвердите выполнение операции.',
		'reload_butt_mess'	=>	'Внимание!\nНачнется загрузка в указанные контроллеры!\nПодтвердите выполнение операции.',
		'parking_name'	=> 'Парковка: ',
		'about_parking'	=> 'Информация по парковке',
		'parking_errors'	=> 'Информация о нарушениях парковки:',
		'enter_time'	=> 'Дата и время заезда:',
		'people_load_card'	=> 'Точки прохода, разршенные пользователю',
		'events_for_dor'	=> 'События по указанной точке прохода',
		'logout' => 'Выход',
		'card_late_info' => 'Список карт с прошедшим сроком действия. Эти карты можно либо удалить, либо продлить до указанной Вами даты.',
		'unActiveCard' => 'Список неактивных карт. Эти карты можно либо удалить, либо продлить до указанной Вами даты.',
		'card_late_save_to_file' => 'Сохранить список в файл',
		'total_count' => 'Всего найдено записей',
		'confirm.delete' => 'Хотите выйти?',
		'count_card_late_next_week' => 'Срок действия завершиться до count_day_befor_end_time',
		'count_card_late' => '<b>Срок карты закончился</b>',
		'card_late_next_week_info' => 'Список карт, срок действия закончится',
		'load_table' => 'Очередь номеров карт для загрузки в контроллеры.<br>Количество точек прохода count_door.',
		'load_table' => 'Количество точек прохода count_door.',
		'string_about'=>'Подключение к СКУД db, версия ver, developer.',
		'no_dashboard_events'=>'Событий нет',
		'no_system_change'=>'Изменений в системе нет',
		'err_mess'=>'Страница сообщений об ошибках.',
		'guide'=>'Справка',
		'load_insert'=>'Стоит в очереди на загрузку',
		'load_del'=>'Стоит в очереди на удаление',
		'event_analit'=>'Есть ошибка?',
		'card_timestart'=>'Карта действует с даты:',
		'card_timeend'=>'Карта действует до даты:',
		'windows_disable'=>'Вывод информации выключен в файле конфигурации.',
		'keys_for_door'=>'Загруженные карты в точку прохода',
		'people_long'=>'Продлить до указанной даты',
		'people_delete'=>'Удалить карты',
		'card_delete'=>'Удалить карты',
		'people_delete_alert'=>'Внимание! Будут удалены пользователи! За один раз будет удалено не более 1500 записей. Подтверждаете удаление?',
		'people_long_alert'=>'Внимание! Срок действия карты для указанных пользователей будет продлен (не более 50 записей). Подтверждаете операцию?',
		'no_log'=>'Не log-файлов',
		'people_unactive'=> 'Сделать неактивными',
		'people_unactive_alert'=> 'Внимание! Отмеченные карты станут неактивными, проход везде будет запрещен. Подтверждаете операцию?',
		'card_date_end'=>'Срок действия карты',
		'overlate'=>'Просрочена, дн.',
		'overlong'=>'Осталось, дн.',
		'total_key_in_device'=>'Количество карт в точке прохода :count.',
		'people_without_card'=>'Сотрудники без карты',
		'people_without_events'=>'Сотрудники без событий',
		'card_late_next_week_info_2' => 'Сотрудники без событий',
		'delete'=>'Удалить',
		'people_without_card_delete'=>'Выбранные пользователи будут удалены! Вы согласны?',
		'view_events'=>'Просмотр событий',
		'no_load_time'=>'Нет данных о загрузке карты в контроллер.',
		'times'=>' раз',
		'total'=>'Всего',
		'timerefresh'=>'Страница обновлена в tr.',
		'org_tree'=>'Адрес (организация):',
		'test_mode_panel_title'=>'Проходы в режиме тест',
		'test_mode'=>'Контроллеров в режиме тест',
		'door_active_status'=>'Активность точки прохода :door_active активность контроллера :device_active',
		'device_type'=>'Тип устройства ":name_door_type" (:door_type)',
		'enable_card_type'=>'Идентификаторы',
		'card_is_active'=>'Состояние карты',
		'card_status_is_active'=>'Карта активна',
		'card_status_status_is_not_active'=>'Карта не активна',
		'analit_error' => '<b>Нарушения режима СКУД</b>',
		'ID_ANALIT' =>'Код аналитики',
		'NAME_ANALYT'=>'Пояснение',
		'COUNT_EVENT_ANALYT'=>'Количество',
		'analit_list' => 'Нарушение режима СКУД',
		'ANALIT_CODE' => 'Код аналитики',
		'analit_panel_title' => 'Анализ нарушений режимов прохода.',
		'count_unactive_card' => 'Количество неактивных карт',
		'analyt_result' => 'Результат анализа событий за последние 24 часа (с time_from по time_to).',
		'event_analyt_title' => 'Данные для анализа событий',
		'No data for analyt_code' => 'Для кода аналитики analyt_code страница анализа не подготовлена. Сообщите об этом разработчику', 
		'not_active_device_panel_desc' => 'Список неактивных точек прохода',
		'analyt_code_list' => 'Список кодов аналитики', 
		'loading_card' =>'Очередь карт для записи в память контроллеров',
		'result_load_card_in_device'=>' LOAD_RESULT(id=ID_DEV, Контроллер "CONTROLLER_NAME", devidx=DEVIDX, door=ID_READER, транспортный сервер "SERVER_NAME".)',
		'no_result_load_card_in_device'=>'Нет данных о загрузке карты в контроллер(id=ID_DEV,Контроллер "CONTROLLER_NAME", транспортный сервер "SERVER_NAME".)',
		'no_date_for_load_card_in_device'=>'Нет данных о загрузке карты в контроллер.',
		'device_panel_title' => 'Управление контроллерами',
		'device_panel_title_desc' => 'Для управления контроллером поставьте метку в колонке 1 и выберите команду в нижней части экрана.<br><b>Сбор статистики производился в период с date_from по date_to!!!</b>',
		'info_from_base'=>'Информация из базы данных СКУД (колонки 1-7)',
		'info_from_stat'=>'Результат сбора данных с контроллеров (колонки 8-10)',
		'recommendation' => 'Рекомендации',
		'DEVICEVERSION'=>'Версия контроллера',
		'single_list'=>'Единый список<br>БД/контроллере',
		'commont_list'=>'DB_COMMON_LIST / READ_COMMON_LIST',
		'on_rus'=>'Вкл',
		'off_rus'=>'Выкл',
		'title_not_select_in_load_table'=>'Точка доступа неактивна, т.к. установлен режим Единый список',
		'refresh'=>'Обновить',
		'TEST_MODE' => 'Режим ТЕСТ',
		'title_server'=>'<acronym title="Траснпортный сервер IP=SERVER_IP, порт = SERVER_PORT">SERVER_NAME</acronym>',
		'no'=>'нет',
		'count_for_laod_table'=>'<acronym title="Данные по базе данных за DBKEYCOUNTTIME, по точке прохода KEYCOUNTTIME ">BASE_COUNT_AT_TIME/DEVICE_COUNT</acronym>',
		'view_event_analit_657' => '(id_pep=e_id_pep, key="e_key", door=e_id_reader, cell=e_DEVIDX, загружена e_LOAD_TIME, e_LOAD_RESULT)',
		'event_analyt_3' =>'e_DEVICENAME (id_dev=e_id_dev сервер "e_serv")',
		'ID_CONTROLLER'=>'ID контроллера',
		'device_code_analyt'=>'Контроллеры с кодом аналитики analyt_code за период с time_from по time_to',
		'event_code_analyt'=>'События с кодом аналитики analyt_code за период с time_from по time_to',
		'service_list'=>'Содержимое файла для сбора статистической информации.',
		'services'=>'Сервисы',
		'name_skud'=>'Перечень объектов',
		'database_place'=>'Расположение базы данных',
		'countcar_check_connect'=>'Количество<br>идентификаторов<br>(карт)',
		'count_err657'=>'Количество необоснованных<br>отказов в проходе',
		'count_err_analyt'=>'Количество ошибок<br>в работе СКУД',
		'time_exec'=>'Время выполнения<br>запроса, сек',
		'skud_list'=>'Список объектов СКУД<br>(C:\xampp\htdocs\city\application\config\skud.php)',
		'Time_execute_page'=>'Время выполнения запроса time_exec сек.',
		'serverList'=>'Сервисы автоматизации работы системы',
		'top'=>'Вверх',
		'pp'=>'№ п/п',
		'comand_door_result'=>'Контроллер  <b>:device_name</b>, точка прохода <b>:door_name</b>, команда <b>:command</b>, результат выполнения команды <b>:result</b>, время выполнения <b>:during</b> сек.',
		'COUNT'=>'Количество',
		'rubic_tablo_IP'=>'IP адрес табло',
		'rubic_tablo_IP_format'=>'(формат записи IP:port. Адрес по умолчанию 172.28.40.124:5002.)',
		'Database_Exception'=>'Проблемы с базой данных',
		'Exception'=>'Выявлена проблема',
		'View_Exception'=>'Ошибка доступа к странице',
		'Parking_system_not_install.'=>'В базе данных не найдены таблицы парковочной системы.',
		'gate_list'=>'Список проездов',
		'gate_edit'=>'Редактировать',
		'gate_del'=>'Удалить',
		'gate_config'=>'Конфигурация КПП',
		'gate_name'=>'Название КПП',
		'id_gate '=>'ID КПП',
		'update'=>'Сохранить',
		'gate_menu'=>'#КПП',
		'del_grz_from_parking'=>'Удалить с территории парковки',
		'grz'=>'ГРЗ',
		'date_come_in'=>'Дата и время въезда',
		'todo'=>'Возможные дествия',
		'rp_add_parking'=>'Добавить парковку',
		'parking_load_list'=>'Список ГРЗ на парковке',
		'add_grz_in_parking'=>'Добавить ГРЗ на парковку',
		'delete_grz_from_parking'=>'Удалить ГРЗ grz с парковки?',
		'grz_list_on_parking'=>'Список ГРЗ с отметкой о въезде на парковку. Всего зарегистрировано транспортных средств count_grz .',
		'pep_name'=>'Модель транспортного средства',
		'time_from'=>'Действует с указанной даты',
		'time_to'=>'Действует по указанную дату',
		'in_parking'=>'На парковки<br>Дата въезда',
		'come_in_gate'=>'Въезд в гараж',
		'come_out_gate'=>'Выезд из гараж',
		'place_edit'=>'Машиноместо редактировать',
		'place_del'=>'Машиноместо удалить',
		'comment_for_search'=>'Укажите номер машиноместа',
);
