##ФИО автора:
Новопашин Антон Александрович
##Потраченное на тестовое задание время:
~10 часов
##Описание архитектуры модуля;
Модуль состоит из трех частей: класс Player, класс Team, контроллер index.php
Контролер сканирует папку matches создает массив с именами файлов в этой папке (в идеале из него должны быть исключены уже отпарщеные файлы)
Далее алгоритм проходит все события из файла json на первом событии периодСтарт, создаются обьекты Игроков и команд
В дальнейших событиях уже идет работа с какими то конкретными обьектами которые фигурируют в логе
##Пояснение принятых решений;
##Инструкция по интеграции и использованию модуля;
Никакая интеграция не требуется, для этого и писал все на голом php (без фреймворков)