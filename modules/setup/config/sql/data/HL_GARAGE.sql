Update Rdb$Relations set Rdb$Description =
'����� - ����������� ����������.
� ����� ������ ����� ���� ��������� ����������.
� ���� ����� ����� �������� ������������ �������� �� ���������� �����������.'
where Rdb$Relation_Name='HL_GARAGE';

Update Rdb$Relation_Fields set Rdb$Description =
'����� ������� ��������.
'
where Rdb$Relation_Name='HL_GARAGE' and Rdb$Field_Name='CREATED';

Update Rdb$Relation_Fields set Rdb$Description =
'����� ��� ���� ����������� �����������'
where Rdb$Relation_Name='HL_GARAGE' and Rdb$Field_Name='ID_PLACE';

Update Rdb$Relation_Fields set Rdb$Description =
'������ �� ��� ������'
where Rdb$Relation_Name='HL_GARAGE' and Rdb$Field_Name='ID_GARAGENAME';
