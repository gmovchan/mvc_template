<?php

namespace Application\Models;

use Application\Core\Model;

class ConfigModel extends Model
{

    private $configArray;
    private static $instance;
    // путь к файлу с конфигурациями
    private $configFilePath;
    // вариант настроек для подключения БД
    const ONE_CONFIG = 1;
    const TWO_CONFIG = 2;

    public function __construct()
    {
        $this->configFilePath = __DIR__ . '/../configs/app.ini';
        $this->configArray = $this->getAllConfig();
    }

    public static function getInstance()
    {
        // проверяет, был ли уже создан объект и если нет, то создает его
        if (empty(self::$instance)) {
            // класс с закрытым конструктором может сам
            // себя создать
            self::$instance = new ConfigModel();
        }
        // возвращает ссылку на созданный объект
        return self::$instance;
    }

    /**
     * возвращает настройки для подключения к БД
     * @param type $settingValue значение для которого требуется получить настройки 
     * @return type массив с данными из заданной секции настроек
     */
    // TODO: для работы с конфигом надо создать отдельный класс, в котором будет
    // прописано какому классу какой конфиг отдавать
    public function getConfig($settingValue)
    {

        switch ($settingValue) {
            case self::ONE_CONFIG:

                $this->ensure(isset($this->configArray['db_name']), "Настройки для константы STK не найдены");

                return $this->configArray['db_name'];
                break;

            case self::TWO_CONFIG:

                $this->ensure(isset($this->configArray['db_name']), "Настройки для константы STKApps не найдены");

                return $this->configArray['db_name'];
                break;

            default:
                $this->ensure(false, "Переданная константа для получения настроек не задана");
                break;
        }
    }

    // получает массив с содержимым файла конфигурации
    private function getAllConfig()
    {
        $this->ensure(file_exists($this->configFilePath), "Файл с настройками не найден");

        $config_array = parse_ini_file($this->configFilePath, true);

        return $config_array;
    }

    // централизованная проверка условия и вызов исключения
    private function ensure($expr, $message)
    {
        
        if (!$expr) {
            throw new Exception($message);
        }
    }

}

