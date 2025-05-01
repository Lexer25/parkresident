Update Rdb$Relations set Rdb$Description =
'Гараж - группировка машиномест.
В одном гараже может быть несколько машиномест.
В один гараж могут заезжать транспортные средства из нескольких организаций.'
where Rdb$Relation_Name='HL_GARAGE';

Update Rdb$Relation_Fields set Rdb$Description =
'Метка времени создания.
'
where Rdb$Relation_Name='HL_GARAGE' and Rdb$Field_Name='CREATED';

Update Rdb$Relation_Fields set Rdb$Description =
'Номер или иное обозначение машиноместа'
where Rdb$Relation_Name='HL_GARAGE' and Rdb$Field_Name='ID_PLACE';

Update Rdb$Relation_Fields set Rdb$Description =
'Ссылка на имя гаража'
where Rdb$Relation_Name='HL_GARAGE' and Rdb$Field_Name='ID_GARAGENAME';
