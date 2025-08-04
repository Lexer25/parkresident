rem "очищаю" гараж
rem тест 1-1: оба идентификатора принадлежа одному автомобилю. Перый идет UHF, затем ГРЗ. Ожидаю в таблице только один UHF
REM C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=clearGarage id=3
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF
REM ping localhost -n 1 > null
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdGRZ

rem тест 1-2: оба идентификатора принадлежа одному автомобилю. Перый идет ГРЗ, затем UHF. ОЖидаю в таблице ГРЗ
REM C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=clearGarage id=3
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdGRZ
REM ping localhost -n 1 > null				
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF	

rem тест 1-3: оба идентификатора принадлежа одному автомобилю. Перый идет ГРЗ, затем UHF. ГРЗ уже на парковке. Ожидаю Повторный въезд, в таблице будет ГРЗ
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdGRZ
REM ping localhost -n 1 > null				
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF	


rem тест 1-4: оба идентификатора принадлежа одному автомобилю. Перый идет UHF, затем ГРЗ. ГРЗ уже на парковке. Ожидаю Повторный въезд, в таблице будет UHF
			
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=50500505	
REM ping localhost -n 1 > null	
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdGRZ

rem ==================== идентификаторы действительный и недействительный
rem оба идентификатора чужие. Ожидаю: события о неизвестных идентификаторов.
rem test 2-0
REM set key=123456
REM set grz=A111AA99
REM echo test 2-0
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key%	
REM ping localhost -n 1 > null	
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdGRZ --grz=%grz%

rem test 2-1 сперва неизвестный UHF,  затем известный ГРЗ. Ожидаю ГРЗ на парковке
REM set key=123456
REM set grz=D014DD71
REM echo test 2-1
rem очищаю гаража
REM C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=clearGarage id=3
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key%	
REM ping localhost -n 1 > null	
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdGRZ --grz=%grz%


rem test 2-2 сперва неизвестный ГРЗ,  затем известный UHF. Ожидаю ГРЗ на парковке
set key=123456
set grz=D213DD71

set grz014=D014DD71
set key014=3665363

set grz005=A005BB177
set key005=50500505

set keyT=11223344

rem тест 2-3. Два разных валидных UHF . Первый 014, затем 005. Ожидаю в списке 014.
REM echo test 2-3
rem очищаю гаража
REM C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=clearGarage id=3
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key014%	
REM ping localhost -n 1 > null	
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key005%
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdGRZ --grz=%grz%


rem тест 2-4. Два разных валидных UHF . Первый 005, затем 014. Ожидаю в списке 005.
REM echo test 2-4
rem очищаю гаража
REM C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=clearGarage id=3
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key005%	
REM ping localhost -n 1 > null	
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key014%
rem start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdGRZ --grz=%grz%

rem ============ имитация последовательного проезда =====================
rem test 2-4 последовательный проезд одного и того же UHF на въезд и последующий выезд через ворота 3.1
REM echo test 2-5
rem очищаю гаража
rem ch=1 - въезд
rem ch=0 - выезд
rem C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=clearGarage id=3
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key005%	--ch=1
REM ping localhost -n 5 > null	
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key005%	--ch=0


rem test 2-6 последовательный проезд "чужого" UHF и "своего" UHF на въезд и последующий выезд через ворота 3.1. На парковке должен остаться 005
REM echo test 2-6
rem очищаю гаража
rem ch=1 - въезд
rem ch=0 - выезд
REM C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=clearGarage id=3
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key%	--ch=1
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key%	--ch=1
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key%	--ch=1
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key%	--ch=1
REM ping localhost -n 5 > null	
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key005%	--ch=1

rem ============================Счетчики тест серии 4
rem test 4-0 последовательно заежают машины . Ожидаю разрешение на въезд только первой, а остальные должны быть проигнорированы, т.к. очень часто ездят.
rem эта же ситуация будет происходит, когда в поле попадет сразу несколько UHF. Ожидаю на территории только первый UHF
REM echo test 4-0

REM ch=1 - въезд
REM ch=0 - выезд
C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=clearGarage id=3
start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key014%	--ch=1
ping localhost -n 1 > null
start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key005%	--ch=1
ping localhost -n 1 > null
start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%keyT%	--ch=1


rem ============================Счетчики тест серии 5 ==========================
rem test 5-1 выезд UHF, которого нет в гараже
REM echo test 4-0
rem ch=1 - въезд
rem ch=0 - выезд
REM C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=clearGarage id=3
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key014%	--ch=1
REM ping localhost -n 12 > null
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%key005%	--ch=0
REM ping localhost -n 12 > null
REM start C:\xampp\php\php.exe c:\xampp\htdocs\parkresident\modules\minion\minion --task=CheckDubleIdUHF --key=%keyT%	--ch=1





	