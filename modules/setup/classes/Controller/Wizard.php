<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package    ParkResident/Setup
 * @category   Base
 * @author     Artonit
 * @copyright  (c) 2025 Artonit Team
 * @license    http://artonit/ru
 */

/*
03.05.2025
Wizard - контроллера для настройки интеграции
*/

class Controller_Wizard extends Controller_Template {

    public $template = 'template';

    public function before()
    {
        parent::before();
        $session = Session::instance();
    }

    public function action_index()
    {
        $content = View::factory('setup/wizard', array());
        $this->template->content = $content;
    }

    /** 3.05.2025 Добавление категории доступа в БД СКУД. Название категории берется как название парковочной площадки. */
    public function action_addAccessname()
    {
        $post = Validation::factory($_POST);
        $post->rule('name', 'not_empty')
            ->rule('name', 'Model_wizard::checkAccessNameIsPresent');
        if ($post->check()) {
            $sql = 'INSERT INTO ACCESSNAME (ID_DB,NAME) VALUES (1,\'' . Arr::get($_POST, 'name') . '\')';
            Log::instance()->add(Log::NOTICE, $sql);
            Model::factory('Parkdb')->makeQuery(iconv('UTF-8', 'windows-1251', $sql));
        }
        $this->redirect('wizard');
    }

    public function action_addServer()
    {
        $content = View::factory('setup/server_add_form', array());
        $this->template->content = $content;
    }

    public function action_saveServer()
    {
        $post = Validation::factory($_POST)
            ->rule('name', 'not_empty')
            ->rule('ip', 'not_empty')
            ->rule('ip', 'regex', array(':value', '/^(\d{1,3}\.){3}\d{1,3}$/')) // Проверка формата xxx.xxx.xxx.xxx
            ->rule('ip', function($value) {
                $parts = explode('.', $value);
                if (count($parts) !== 4) return false;
                foreach ($parts as $part) {
                    if (!is_numeric($part) || $part < 0 || $part > 255) return false;
                }
                return true;
            }, array(':value', 'Неверный IP-адрес'))
            ->rule('port', 'not_empty')
            ->rule('port', 'numeric')
            ->rule('active', 'in_array', array(':value', array('0', '1')));

        if (!$post->check()) {
            Session::instance()->set('error', implode(', ', $post->errors('validation')));
            $this->redirect($post['id_server'] ? 'wizard/editServer/' . $post['id_server'] : 'wizard/addServer');
        }

        $ip_numeric = ip2long($post['ip']);
        if ($ip_numeric === false) {
            Session::instance()->set('error', 'Неверный формат IP-адреса');
            $this->redirect($post['id_server'] ? 'wizard/editServer/' . $post['id_server'] : 'wizard/addServer');
        }

        $data = array(
            'ID_DB' => 1,
            'NAME' => iconv('UTF-8', 'windows-1251//TRANSLIT', Arr::get($_POST, 'name')),
            'IP' => $ip_numeric,
            'PORT' => Arr::get($_POST, 'port'),
            'ACTIVE' => Arr::get($_POST, 'active')
        );
        $id_server = Arr::get($_POST, 'id_server');
        $parkdb = Model::factory('Parkdb');

        $debug_msg = 'Данные для сохранения: ' . print_r($data, true);
        Log::instance()->add(Log::DEBUG, $debug_msg);
        echo '<pre>' . $debug_msg . '</pre>';

        if ($id_server) {
            $result = $parkdb->updateServer($id_server, $data);
            if ($result) {
                Log::instance()->add(Log::DEBUG, 'Сервер с ID ' . $id_server . ' успешно обновлен');
                echo 'Обновление успешно<br>';
            } else {
                Log::instance()->add(Log::ERROR, 'Ошибка при обновлении сервера с ID ' . $id_server . ': ' . $parkdb->mess);
                echo 'Ошибка обновления: ' . $parkdb->mess . '<br>';
            }
        } else {
            $result = $parkdb->addServer($data);
            if ($result) {
                Log::instance()->add(Log::DEBUG, 'Новый сервер успешно добавлен');
                echo 'Добавление успешно<br>';
            } else {
                Log::instance()->add(Log::ERROR, 'Ошибка при добавлении сервера: ' . $parkdb->mess);
                echo 'Ошибка добавления: ' . $parkdb->mess . '<br>';
            }
        }
        $this->redirect('wizard');
    }

    public function action_editServer()
    {
        $id_server = $this->request->param('id');
        if (!$id_server) {
            $this->redirect('wizard');
        }

        $parkdb = Model::factory('Parkdb');
        $server = $parkdb->getServerById($id_server);

        if (!$server) {
            Log::instance()->add(Log::ERROR, 'Сервер с ID ' . $id_server . ' не найден');
            $this->redirect('wizard');
        }

        $server['NAME'] = iconv('windows-1251', 'UTF-8', $server['NAME']);

        $content = View::factory('setup/server_edit_form', array(
            'server' => $server,
        ));
        $this->template->content = $content;
    }

    public function action_deleteServer()
    {
        $id_server = $this->request->param('id');
        if (!$id_server) {
            $this->redirect('wizard');
        }

        $parkdb = Model::factory('Parkdb');
        $result = $parkdb->deleteServer($id_server);

        if ($result) {
            Log::instance()->add(Log::DEBUG, 'Сервер с ID ' . $id_server . ' успешно удален');
            echo 'Удаление успешно<br>';
        } else {
            Log::instance()->add(Log::ERROR, 'Ошибка при удалении сервера с ID ' . $id_server . ': ' . $parkdb->mess);
            echo 'Ошибка удаления: ' . $parkdb->mess . '<br>';
        }

        $this->redirect('wizard');
    }

    public function action_addDevice() {
        $parkdb = Model::factory('Parkdb');
        $servers = $parkdb->getServers();

        // Устанавливаем содержимое напрямую, без промежуточного шаблона setup/template
        $content = View::factory('setup/device_add_form', array(
            'servers' => $servers,
        ));

        $this->template->content = $content;
    }

    public function action_saveDevice() {
        $post = Validation::factory($_POST)
            ->rule('NAME', 'not_empty')
            ->rule('ID_SERVER', 'not_empty')
            ->rule('ID_SERVER', 'numeric')
            ->rule('ACTIVE', 'not_empty')
            ->rule('ACTIVE', 'in_array', array(':value', array('0', '1')))
            ->rule('ID_CTRL', 'not_empty')
            ->rule('ID_CTRL', 'regex', array(':value', '/^(\d{1,3}\.){3}\d{1,3}$/')); // Проверка формата IP

        if (!$post->check()) {
            Session::instance()->set('error', implode(', ', $post->errors('validation')));
            $this->redirect('wizard/addDevice');
        }

        $data = array(
            'ID_SERVER' => Arr::get($_POST, 'ID_SERVER'),
            'NAME' => Arr::get($_POST, 'NAME'), // Оставляем как есть, преобразование в модели
            'ACTIVE' => Arr::get($_POST, 'ACTIVE'),
            'NETADDR' => Arr::get($_POST, 'ID_CTRL'),
        );

        $parkdb = Model::factory('Parkdb');
        $debug_msg = 'Данные для сохранения: ' . print_r($data, true);
        Log::instance()->add(Log::DEBUG, $debug_msg);
        echo '<pre>' . $debug_msg . '</pre>';

        $result = $parkdb->addDevice($data);
        if ($result) {
            Log::instance()->add(Log::DEBUG, 'Новое устройство успешно добавлено');
            echo 'Добавление успешно<br>';
        } else {
            Log::instance()->add(Log::ERROR, 'Ошибка при добавлении устройства: ' . $parkdb->mess);
            echo 'Ошибка добавления: ' . $parkdb->mess . '<br>';
        }
        $this->redirect('wizard');
    }

    public function action_editDevice() {
        $id_dev = $this->request->param('id');
        if (!$id_dev) {
            $this->redirect('wizard');
        }

        $parkdb = Model::factory('Parkdb');
        $device = $parkdb->getDeviceById($id_dev);

        if (!$device) {
            Log::instance()->add(Log::ERROR, 'Устройство с ID ' . $id_dev . ' не найдено');
            $this->redirect('wizard');
        }

        $device['NETADDR'] = iconv('windows-1251', 'UTF-8', $device['NETADDR']);
        $device['NAME'] = iconv('windows-1251', 'UTF-8', $device['NAME']);
        $device['PSW'] = iconv('windows-1251', 'UTF-8', $device['PSW']);
        $device['CONFIG'] = iconv('windows-1251', 'UTF-8', $device['CONFIG']);
        $device['PARAM'] = iconv('windows-1251', 'UTF-8', $device['PARAM']);
        $device['TAGNAME'] = iconv('windows-1251', 'UTF-8', $device['TAGNAME']);

        $content = View::factory('setup/device_edit_form', array(
            'device' => $device,
        ));
        $this->template->content = $content;
    }

    public function action_deleteDevice() {
        $id_dev = $this->request->param('id');
        if (!$id_dev) {
            $this->redirect('wizard');
        }

        $parkdb = Model::factory('Parkdb');
        $result = $parkdb->deleteDevice($id_dev);

        if ($result) {
            Log::instance()->add(Log::DEBUG, 'Устройство с ID ' . $id_dev . ' успешно удалено');
            echo 'Удаление успешно<br>';
        } else {
            Log::instance()->add(Log::ERROR, 'Ошибка при удалении устройства с ID ' . $id_dev . ': ' . $parkdb->mess);
            echo 'Ошибка удаления: ' . $parkdb->mess . '<br>';
        }

        $this->redirect('wizard');
    }
	
	/**
	*Прием настроек дл
	*
	*/
	public function action_cvs()
	{
		//echo Debug::vars('260', $_POST);exit;
		$setting=new Setting();
		$setting->Update(Arr::get($_POST, 'name'), Arr::get($_POST, 'value', 0), Arr::get($_POST, 'type'));
	
		
		$referrer = Request::initial()->referrer();
		// Если Referer есть – делаем редирект, иначе – на дефолтную страницу
		if ($referrer) {
			$this->redirect($referrer);
		} else {
			$this->redirect('wizard'); // или другая страница по умолчанию
		}
		
		
	}
}
