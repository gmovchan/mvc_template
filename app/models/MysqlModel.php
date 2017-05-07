<?php

namespace Application\Models;

use Application\Core\Model;
use PDO;
use Application\Models\ConfigModel;

/**
 * Объект класса подключается к БД и работает с запросами
 * Новый уровень абстракции для работы с БД и запросами необходим, чтобы
 * если изменится способ подключения и настройки, то достаточно было бы изменить
 * только этот класс
 */
class MysqlModel extends Model
{

    //хранит подключение к БД для доступа к нему из методов класса
    private $dbh;
    private $config_data;

    /**
     * 
     * @param Model $classModel класса из которого создается объект MysqlModel
     */
    public function __construct($settingValue)
    {
        // получает настройки для соединения с БД
        $mysqlConfig = ConfigModel::getInstance();
        $this->config_data = $mysqlConfig->getConfig($settingValue);

        $this->connect();
    }

    private function connect()
    {
        // отлов ошибок подключения к БД
        $this->dbh = new PDO('mysql:host=' . $this->config_data['host'] . ';dbname=' .
                $this->config_data['db'] . ';charset=utf8', $this->config_data['user'], $this->config_data['password']);
        // требуется чтобы PDO сообщало об ошибке и прерывало выполнение скрипта
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * запрос к БД
     * $section_name принимает массив с параметрами для подготавливаемого 
     * запроса с неименованными псевдопеременными для защиты от инъекций
     */
    public function query($query, $typeQuery = null, $num = null, array $query_param = array())
    {
        if ($q = $this->dbh->prepare($query)) {
            switch ($typeQuery) {
                case 'num_row':
                    $q->execute($query_param);
                    return $q->rowCount();
                    break;

                case 'result':
                    $q->execute($query_param);
                    return $q->fetchColumn($num);
                    break;

                // получает только одну строку
                case 'accos':
                    $q->execute($query_param);
                    return $q->fetch(PDO::FETCH_ASSOC);
                    break;

                // получает все строки в виде массива
                case 'fetchAll':
                    $q->execute($query_param);
                    return $q->fetchAll();
                    break;

                case 'none':
                    $q->execute($query_param);
                    return $q;
                    break;

                default:
                    // выкидывает исключение и завершает скрипт, если не найден переданный тип SQL запроса
                    throw new \Exception("Ошибка при указании типа SQL запроса");
            }
        }
    }

    public function getLastInsertId()
    {
        return intval($this->dbh->lastInsertId());
    }

}
