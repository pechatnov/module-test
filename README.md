### Порядок установки
1. Поместить в папку **/local/modules/pv.laptop**
2. Установить модуль **/bitrix/admin/partner_modules.php**
3. Разместить комплексный компонент Магазин ноутбуков "laptop", настроить ЧПУ

### Пример настроек компонента
```php
$APPLICATION->IncludeComponent(
	"pv:laptop",
	"",
	[
		"SEF_FOLDER" => "/laptop/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => [
		    "detail" => "detail/#LAPTOP#/",
		    "model" => "#BRAND#/#MODEL#/",
		    "vendor" => "#BRAND#/",
		    "vendors" => ""
                ]
	]
);
```

### Уровни URL:
1. /#SEF_FOLDER#/ - список производителей
2. /#SEF_FOLDER#/#BRAND#/ - список моделей производителя 
3. /#SEF_FOLDER#/#BRAND#/#MODEL#/ - список ноутбуков модели
4. /#SEF_FOLDER#/detail/#LAPTOP#/ - детальная страница ноутбука