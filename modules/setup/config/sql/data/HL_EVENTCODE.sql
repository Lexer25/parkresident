INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (1, 'Въезд на парковку', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (2, 'Выезд с парковки', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (46, 'Неизвестный ГРЗ', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (65, 'Проезд запрещен', 16711680);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (13, 'Получен ГРЗ', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (50, 'Проезд разрешен', 5898050);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (51, 'Система распознавания ГРЗ работает нормально', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (52, 'Ошибка системы распознавания ГРЗ', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (61, 'Восстановлено подключение к базе данных', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (91, 'ПО запущено', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (92, 'ПО остановлено', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (3, 'Отметка о выезде поставлена оператором', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (4, 'Отметка о въезде поставлена оператором', NULL);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (81, 'Проезд запрещен. Нет свободных мест в гараже', 65535);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (5, 'Грз не распознан, въезд открыт оператором', 65535);
INSERT INTO HL_EVENTCODE (ID, NAME, COLOR) VALUES (6, 'Повторный въезд на парковку', 65535);


Update Rdb$Relations set Rdb$Description =
'Содержит названия для кодов внутренних событий парковки'
where Rdb$Relation_Name='HL_EVENTCODE';

Update Rdb$Relation_Fields set Rdb$Description =
'Код события'
where Rdb$Relation_Name='HL_EVENTCODE' and Rdb$Field_Name='ID';

Update Rdb$Relation_Fields set Rdb$Description =
'Наименование события'
where Rdb$Relation_Name='HL_EVENTCODE' and Rdb$Field_Name='NAME';

Update Rdb$Relation_Fields set Rdb$Description =
'Цвет фона при выводе на экран
'
where Rdb$Relation_Name='HL_EVENTCODE' and Rdb$Field_Name='COLOR';
